<?php

namespace app\models\forms;

use app\models\settings\Privileges;
use app\models\db\{Consultation, ConsultationAgendaItem, ConsultationMotionType, IMotion, Motion, User};
use app\models\exceptions\Inconsistency;

class MotionMover
{
    private Consultation $consultation;
    private Motion $motion;
    private User $mover;

    public function __construct(Consultation $consultation, Motion $motion, User $user)
    {
        $this->consultation = $consultation;
        $this->motion       = $motion;
        $this->mover        = $user;
    }

    public function getMotion(): Motion
    {
        return $this->motion;
    }

    /**
     * @return Consultation[]
     */
    public function getConsultationTargets(): array
    {
        $consultations = [];
        foreach ($this->consultation->site->consultations as $consultation) {
            if ($consultation->id === $this->consultation->id) {
                continue;
            }
            if (!$this->mover->hasPrivilege($consultation, Privileges::PRIVILEGE_MOTION_INITIATORS, null)) {
                continue;
            }
            if (count($this->getCompatibleMotionTypes($consultation)) > 0) {
                $consultations[] = $consultation;
            }
        }

        return $consultations;
    }

    /**
     * @return ConsultationMotionType[]
     */
    public function getCompatibleMotionTypes(Consultation $consultation): array
    {
        $types = [];
        foreach ($consultation->motionTypes as $motionType) {
            if ($this->motion->motionType->isCompatibleTo($motionType)) {
                $types[] = $motionType;
            }
        }

        return $types;
    }

    /**
     * @throws Inconsistency
     */
    public function move(array $post): ?Motion
    {
        if (!isset($post['target']) || !isset($post['operation']) || !isset($post['titlePrefix'])) {
            return null;
        }

        $titlePrefix = $post['titlePrefix'];

        switch ($post['target']) {
            case 'agenda':
                $agendaItemId = intval($post['agendaItem'][$this->consultation->id]);
                $agendaItem   = $this->consultation->getAgendaItem($agendaItemId);
                if ($post['operation'] === 'copy') {
                    return $this->copyToAgendaItem($agendaItem, $titlePrefix, true);
                }
                if ($post['operation'] === 'copynoref') {
                    return $this->copyToAgendaItem($agendaItem, $titlePrefix, false);
                }
                if ($post['operation'] === 'move') {
                    return $this->moveToAgendaItem($agendaItem, $titlePrefix);
                }
                break;
            case 'consultation':
                /** @var Consultation|null $consultation */
                $consultation = null;
                foreach ($this->getConsultationTargets() as $con) {
                    if ($con->id === intval($post['consultation'])) {
                        $consultation = $con;
                    }
                }
                if (!$consultation) {
                    throw new Inconsistency('Consultation not found');
                }
                /** @var ConsultationMotionType|null $motionType */
                $motionType = null;
                foreach ($this->getCompatibleMotionTypes($consultation) as $type) {
                    if ($type->id === intval($post['motionType'][$consultation->id])) {
                        $motionType = $type;
                    }
                }
                if (!$motionType) {
                    throw new Inconsistency('Motion type not found');
                }
                foreach ($consultation->motions as $oMotion) {
                    if (mb_strtolower($oMotion->titlePrefix) === mb_strtolower($titlePrefix)) {
                        $oMotion->titlePrefix = $consultation->getNextMotionPrefix($motionType->id);
                        $oMotion->save();
                    }
                    if (mb_strtolower($oMotion->slug ?: '') === mb_strtolower($this->motion->slug ?: '')) {
                        $oMotion->slug = null;
                        $oMotion->save();
                    }
                }
                if ($post['operation'] === 'copy') {
                    return $this->copyToConsultation($motionType, $titlePrefix, true);
                }
                if ($post['operation'] === 'copynoref') {
                    return $this->copyToConsultation($motionType, $titlePrefix, false);
                }
                if ($post['operation'] === 'move') {
                    return $this->moveToConsultation($motionType, $titlePrefix);
                }
                break;
        }

        return null;
    }

    private function copyToAgendaItem(ConsultationAgendaItem $agendaItem, string $titlePrefix, bool $markAsMoved): Motion
    {
        $newMotion = MotionDeepCopy::copyMotion($this->motion, $this->motion->getMyMotionType(), $agendaItem, $titlePrefix);

        if ($markAsMoved) {
            $newMotion->parentMotionId = $this->motion->id;
            $newMotion->save();

            $this->motion->status = IMotion::STATUS_MOVED;
            $this->motion->save();
        }

        return $newMotion;
    }

    private function moveToAgendaItem(ConsultationAgendaItem $agendaItem, $titlePrefix): Motion
    {
        $this->motion->agendaItemId = $agendaItem->id;
        $this->motion->titlePrefix  = $titlePrefix;
        $this->motion->save();
        $this->motion->refresh();

        return $this->motion;
    }

    private function copyToConsultation(ConsultationMotionType $motionType, string $titlePrefix, bool $markAsMoved): Motion
    {
        $newMotion = MotionDeepCopy::copyMotion($this->motion, $motionType, null, $titlePrefix);

        if ($markAsMoved) {
            $newMotion->parentMotionId = $this->motion->id;
            $newMotion->save();

            $this->motion->status = IMotion::STATUS_MOVED;
            $this->motion->save();
        }

        return $newMotion;
    }

    private function moveToConsultation(ConsultationMotionType $motionType, string $titlePrefix): Motion
    {
        $oldConsultation              = $this->motion->getMyConsultation();
        $newConsultation              = $motionType->getConsultation();
        $this->motion->agendaItemId   = null;
        $this->motion->titlePrefix    = $titlePrefix;
        $this->motion->consultationId = $newConsultation->id;
        $this->motion->setMotionType($motionType);

        $oldConsultation->flushMotionCache();
        $newConsultation->flushMotionCache();

        return $this->motion;
    }
}
