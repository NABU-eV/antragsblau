<?php

declare(strict_types=1);

namespace app\models\db;

use app\models\settings\AntragsgruenApp;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $title
 * @property int|null $votingStatus
 * @property int $votingBlockId
 * @property string|null $votingData
 * @property VotingBlock|null $votingBlock
 * @property Vote[] $votes
 */
class VotingQuestion extends ActiveRecord implements IVotingItem
{
    use VotingItemTrait;

    /**
     * @return string
     */
    public static function tableName()
    {
        return AntragsgruenApp::getInstance()->tablePrefix . 'votingQuestion';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVotingBlock()
    {
        return $this->hasOne(VotingBlock::class, ['id' => 'votingBlockId'])
            ->andWhere(VotingBlock::tableName() . '.votingStatus != ' . VotingBlock::STATUS_DELETED);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVotes()
    {
        return $this->hasMany(Vote::class, ['questionId' => 'id']);
    }

    public function getMyConsultation(): ?Consultation
    {
        // TODO: Improve
        return $this->votingBlock->getMyConsultation();
    }

    public function getAgendaApiBaseObject(): array
    {
        return [
            'type' => 'question',
            'id' => $this->id,
            'prefix' => '',
            'title_with_prefix' => $this->title,
            'url_json' => null,
            'url_html' => null,
            'initiators_html' => null,
            'procedure' => null,
            'item_group_same_vote' => $this->getVotingData()->itemGroupSameVote,
            'item_group_name' => $this->getVotingData()->itemGroupName,
            'voting_status' => $this->votingStatus,
        ];
    }
}
