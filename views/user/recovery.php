<?php

use app\components\Captcha;
use app\components\UrlHelper;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var string $preEmail
 * @var string $preCode
 */

/** @var \app\controllers\UserController $controller */
$controller = $this->context;
$layout     = $controller->layoutParams;

$this->title = Yii::t('user', 'recover_title');
$layout->addBreadcrumb('Passwort');
$layout->robotsNoindex = true;

echo '<h1>' . Yii::t('user', 'recover_title') . '</h1>';

echo $controller->showErrors();

$url = UrlHelper::createUrl('user/recovery');

echo Html::beginForm($url, 'post', ['class' => 'sendConfirmationForm', 'aria-labelledby' => 'step1title']);

// Create the same one for both forms, so the value in the session doesn't get overridden by the second
$inlineCaptcha = (Captcha::needsCaptcha(null) ? Captcha::createInlineCaptcha() : null);

?>
    <h2 class="green" id="step1title"><?= Yii::t('user', 'recover_step1') ?></h2>
    <div class="content">
        <div class="row">
            <div class="form-group col-sm-6">
                <label for="sendEmail"><?= Yii::t('user', 'recover_email') ?>:</label>
                <input class="form-control" name="email" id="sendEmail" type="email" required
                       placeholder="<?= Html::encode(Yii::t('user', 'recover_email_place')) ?>" value="">
            </div>
        </div>
        <?php
        if (Captcha::needsCaptcha(null)) {
            ?>
            <div class="row">
                <div class="col-md-12">
                    <label for="captchaInput"><?= Yii::t('user', 'login_captcha') ?>:</label>
                </div>
                <div class="form-group col-md-3">
                    <img src="<?= $inlineCaptcha ?>" alt="" width="150">
                </div>
                <div class="form-group col-md-3">
                    <input type="text" value="" autocomplete="off" name="captcha" id="captchaInput" class="form-control" required>
                </div>
            </div>
            <?php
        }
        ?>
        <div class="row">
            <div class="col-sm-6">
                <button type="submit" class="btn btn-primary" name="send">
                    <span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>
                    <?= Yii::t('user', 'recover_send_email') ?>
                </button>
            </div>
        </div>
    </div>
<?= Html::endForm() ?>

<?= Html::beginForm($url, 'post', ['class' => 'resetPasswortForm', 'aria-labelledby' => 'step2title']) ?>
    <h2 class="green" id="step2title"><?= Yii::t('user', 'recover_step2') ?></h2>
    <div class="content">
        <div class="row">
            <div class="form-group col-sm-6">
                <label for="recoveryEmail"><?= Yii::t('user', 'recover_email') ?>:</label>
                <input class="form-control" name="email" id="recoveryEmail" type="email" required
                       placeholder="<?= Html::encode(Yii::t('user', 'recover_email_place')) ?>"
                       value="<?= Html::encode($preEmail) ?>">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-6">
                <label for="recoveryCode"><?= Yii::t('user', 'recover_code') ?>:</label>
                <input class="form-control" name="recoveryCode" id="recoveryCode" type="text" required
                       value="<?= Html::encode($preCode) ?>">
            </div>
        </div>

        <div class="row">
            <div class="form-group col-sm-6">
                <label for="recoveryPassword"><?= Yii::t('user', 'recover_new_pwd') ?>:</label>
                <input class="form-control" name="newPassword" id="recoveryPassword" type="password" required value="">
            </div>
        </div>
        <?php
        if (Captcha::needsCaptcha(null)) {
            ?>
            <div class="row">
                <div class="col-md-12">
                    <label for="captchaInput"><?= Yii::t('user', 'login_captcha') ?>:</label>
                </div>
                <div class="form-group col-md-3">
                    <img src="<?= $inlineCaptcha ?>" alt="" width="150">
                </div>
                <div class="form-group col-md-3">
                    <input type="text" value="" autocomplete="off" name="captcha" id="captchaInput" class="form-control" required>
                </div>
            </div>
            <?php
        }
        ?>
        <div class="row">
            <div class="col-sm-6">
                <button type="submit" class="btn btn-primary" name="recover">
                    <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                    <?= Yii::t('user', 'recover_set_pwd') ?>
                </button>
            </div>
        </div>
    </div>

<?php
echo Html::endForm();

if ($preCode != '' && $preEmail != '') {
    $layout->addOnLoadJS('$("#recoveryPassword").scrollintoview().focus();');
}
