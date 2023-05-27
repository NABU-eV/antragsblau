<?php

use app\components\UrlHelper;
use app\models\settings\AntragsgruenApp;
use yii\helpers\Html;

/**
 * @var bool $loginActive
 * @var string $backUrl
 */

$app = AntragsgruenApp::getInstance();
$action = $app->domainPlain . 'nabu-login';
$absoluteBack = UrlHelper::absolutizeLink($backUrl);
?>
<section class="loginSimplesaml">
    <h2 class="green"><?= Yii::t('antragsblau_saml', 'login_title') ?></h2>
    <div class="content row">
        <?= Html::beginForm($action, 'post', ['class' => 'col-sm-4', 'id' => 'samlLoginForm']); ?>
        <input type="hidden" name="backUrl" value="<?= Html::encode($absoluteBack) ?>">
        <button type="submit" class="btn btn-primary" name="samlLogin">
            <span class="glyphicon glyphicon-log-in" aria-hidden="true"></span> <?= Yii::t('antragsblau_saml',
                'login_action') ?>
        </button>
        <?= Html::endForm() ?>
        <div id="loginSamlHint">
            <?= Yii::t('antragsblau_saml', 'login_info',
                ['login_action' => Yii::t('antragsblau_saml', 'login_action')]) ?>
        </div>
    </div>
</section>
