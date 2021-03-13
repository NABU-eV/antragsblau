<?php

declare(strict_types=1);

namespace app\plugins\discourse\controllers;

use app\controllers\Base;
use app\plugins\discourse\Module;
use app\plugins\discourse\Tools;

class MotionController extends Base
{
    /**
     * @param string $motionSlug
     *
     * @return string
     * @throws \Yii\base\ExitException
     */
    public function actionGotoDiscourse(string $motionSlug)
    {
        $motion = $this->getMotionWithCheck($motionSlug);

        $discourseData = $motion->getExtraDataKey('discourse');
        if (!$discourseData) {
            try {
                $discourseData = Tools::createMotionTopic($motion);
            } catch (\Exception $e) {
                $this->showErrorpage(500, \Yii::t('discourse', 'error_create'));
            }
        }

        $discourseConfig = Module::getDiscourseConfiguration();
        $url = $discourseConfig['host'] . 't/' . $discourseData['topic_id'];

        return $this->redirect($url);
    }
}
