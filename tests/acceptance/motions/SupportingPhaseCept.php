<?php

/** @var \Codeception\Scenario $scenario */
$I = new AcceptanceTester($scenario);
$I->populateDBData1();

function gotoSupportingMotion($I)
{
    \app\tests\_pages\MotionPage::openBy($I, [
        'subdomain'        => 'supporter',
        'consultationPath' => 'supporter',
        'motionSlug'       => 116,
    ]);
}

$I->wantTo('enable/disable liking and disliking');
gotoSupportingMotion($I);
$I->see('Dieser Antrag ist noch nicht offiziell eingereicht.');
$I->see('Du musst dich einloggen, um Anträge unterstützen zu können.');
$I->dontSeeElement('button[name=motionSupport]');
$I->dontSeeElement('section.likes');

$I->loginAsStdUser();
gotoSupportingMotion($I);
$I->seeElement('button[name=motionSupport]');
$I->dontSeeElement('button[name=motionLike]');
$I->dontSeeElement('button[name=motionDislike]');


$I->logout();
$I->loginAndGotoStdAdminPage('supporter', 'supporter')->gotoMotionTypes(10);
$I->dontSeeCheckboxIsChecked('.motionDislike');
$I->dontSeeCheckboxIsChecked('.motionLike');
$I->checkOption('.motionLike');
$I->checkOption('.motionDislike');
$I->submitForm('.adminTypeForm', [], 'save');


$I->logout();

$I->loginAsStdUser();
gotoSupportingMotion($I);
$I->seeElement('section.likes');
$I->seeElement('button[name=motionLike]');
$I->seeElement('button[name=motionDislike]');
$I->seeElement('button[name=motionSupport]');


$I->wantTo('support this motion');

$I->submitForm('.motionSupportForm', [], 'motionSupport');
$I->see('Du unterstützt diesen Antrag nun.');
$I->dontSeeElement('button[name=motionSupport]');
$I->see('Du!', 'section.supporters');
$I->see('Testuser', 'section.supporters');
$I->see('Die Mindestzahl an Unterstützer*innen (1) wurde erreicht');
$I->seeElement('button[name=motionSupportRevoke]');


$I->wantTo('revoke the support');

$I->submitForm('.motionSupportForm', [], 'motionSupportRevoke');
$I->see('Du stehst diesem Antrag wieder neutral gegenüber');
$I->see('aktueller Stand: 0');


$I->wantTo('support it again');

$I->submitForm('.motionSupportForm', [], 'motionSupport');
$I->see('Du unterstützt diesen Antrag nun.');


$I->logout();


$I->wantTo('submit the motion');

$I->loginAsStdAdmin();
gotoSupportingMotion($I);
$I->see('Testuser', 'section.supporters');
$I->submitForm('.motionSupportFinishForm', [], 'motionSupportFinish');
$I->see('Der Antrag ist nun offiziell eingereicht');
$I->see('Eingereicht (ungeprüft)', '.motionData');


$I->logout();


$I->wantTo('check that motions created as normal person are in supporting phase');

$I->loginAsStdUser();
$I->gotoConsultationHome(false, 'supporter', 'supporter')->gotoMotionCreatePage(10, true, 'supporter', 'supporter');
$I->fillField('#sections_30', 'Title as normal person');
$I->executeJS('CKEDITOR.instances.sections_31_wysiwyg.setData("<p><strong>Test</strong></p>");');
$I->submitForm('#motionEditForm', [], 'save');
$I->submitForm('#motionConfirmForm', [], 'confirm');
$I->see('Um ihn offiziell einzureichen, benötigt er nun mindestens 1 Unterstützer*innen.');


$I->wantTo('check that motions created as organizations are not in supporting phase');

$I->gotoConsultationHome(false, 'supporter', 'supporter')->gotoMotionCreatePage(10, true, 'supporter', 'supporter');
$I->fillField('#sections_30', 'Title as organization');
$I->executeJS('CKEDITOR.instances.sections_31_wysiwyg.setData("<p><strong>Test</strong></p>");');
$I->checkOption('#personTypeOrga');
$I->fillField('#resolutionDate', '01.01.2016');
$I->submitForm('#motionEditForm', [], 'save');
$I->submitForm('#motionConfirmForm', [], 'confirm');
$I->see('Du hast den Antrag eingereicht. Er wird nun auf formale Richtigkeit geprüft und dann freigeschaltet.');


$I->gotoConsultationHome(false, 'supporter', 'supporter');
$I->see('Eingereicht (ungeprüft)', '.myMotionList');
$I->see('Unterstützer*innen sammeln', '.myMotionList');
