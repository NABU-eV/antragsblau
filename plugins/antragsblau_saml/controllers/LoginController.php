<?php

namespace app\plugins\antragsblau_saml\controllers;

use app\components\UrlHelper;
use app\controllers\Base;
use app\plugins\antragsblau_saml\Module;
use yii\helpers\Html;

class LoginController extends Base
{

    public $enableCsrfValidation = false;

    // Login and Mainainance mode is always allowed
    public ?bool $allowNotLoggedIn = true;

    public function actionLogin(string $backUrl = ''): void
    {
        /**
         * Hint: if this plugin is enabled, login in by it is always enabled, so don't perform a check for that here.
         */

        if ($backUrl === '') {
            $backUrl = $this->getPostValue('backUrl', UrlHelper::homeUrl());
        }

        try {
            Module::getDedicatedLoginProvider()->performLoginAndReturnUser();

            $this->redirect($backUrl);
        } catch (\Exception $e) {
            $this->showErrorpage(
                500,
                \Yii::t('user', 'err_unknown') . ':<br> "' . Html::encode($e->getMessage()) . '"'
            );
        }
    }
}
