<?php

use app\components\Tools;
use app\components\UrlHelper;
use app\models\db\Motion;
use app\models\db\User;
use yii\helpers\Html;
use app\views\motion\LayoutHelper as MotionLayoutHelper;

/**
 * @var \yii\web\View $this
 * @var Motion $motion
 * @var int[] $openedComments
 * @var string|null $adminEdit
 * @var null|string $supportStatus
 * @var bool $consolidatedAmendments
 * @var \app\controllers\Base $controller
 */

echo '<div class="content">';

$replacedByMotions = $motion->getVisibleReplacedByMotions();
if (count($replacedByMotions) > 0) {
    echo '<div class="alert alert-danger motionReplayedBy" role="alert">';
    echo \Yii::t('motion', 'replaced_by_hint');
    if (count($replacedByMotions) > 1) {
        echo '<ul>';
        foreach ($replacedByMotions as $newMotion) {
            echo '<li>';
            $newLink = UrlHelper::createMotionUrl($newMotion);
            echo Html::a(Html::encode($newMotion->getTitleWithPrefix()), $newLink);
            echo '</li>';
        }
        echo '</ul>';
    } else {
        echo '<br>';
        $newLink = UrlHelper::createMotionUrl($replacedByMotions[0]);
        echo Html::a(Html::encode($replacedByMotions[0]->getTitleWithPrefix()), $newLink);
    }
    echo '</div>';
}

echo '<table class="motionDataTable">
                <tr>
                    <th>' . Yii::t('motion', 'consultation') . ':</th>
                    <td>' .
    Html::a(Html::encode($motion->getMyConsultation()->title), UrlHelper::createUrl('consultation/index')) . '</td>
                </tr>';

if ($motion->agendaItem) {
    echo '<tr><th>' . \Yii::t('motion', 'agenda_item') . ':</th><td>';
    echo Html::encode($motion->agendaItem->getShownCode(true) . ' ' . $motion->agendaItem->title);
    echo '</td></tr>';
}

$initiators = $motion->getInitiators();
if (count($initiators) > 0) {
    if (count($initiators) == 1) {
        echo '<tr><th>' . Yii::t('motion', 'initiators_1') . ':</th><td>';
    } else {
        echo '<tr><th>' . Yii::t('motion', 'initiators_x') . ':</th><td>';
    }
    echo MotionLayoutHelper::formatInitiators($initiators, $controller->consultation);

    echo '</td></tr>';
}
echo '<tr class="statusRow"><th>' . \Yii::t('motion', 'status') . ':</th>';
echo '<td>' . $motion->getFormattedStatus() . '<td>';
echo '</tr>';

if ($motion->replacedMotion) {
    $oldLink = UrlHelper::createMotionUrl($motion->replacedMotion);
    echo '<tr class="replacesMotion"><th>' . Yii::t('motion', 'replaces_motion') . ':</th><td>';
    echo Html::a(Html::encode($motion->replacedMotion->getTitleWithPrefix()), $oldLink);

    $changesLink = UrlHelper::createMotionUrl($motion, 'view-changes');
    echo '<div class="changesLink">';
    echo '<span class="glyphicon glyphicon-chevron-right"></span> ';
    echo Html::a(\Yii::t('motion', 'replaces_motion_diff'), $changesLink);
    echo '</div>';
    echo '</td></tr>';
}

$proposalAdmin = User::havePrivilege($consultation, User::PRIVILEGE_CHANGE_PROPOSALS);
if (($motion->isProposalPublic() && $motion->proposalStatus) || $proposalAdmin) {
    echo '<tr class="proposedStatusRow"><th>' . \Yii::t('amend', 'proposed_status') . ':</th><td class="str">';
    echo $motion->getFormattedProposalStatus(true);
    echo '</td></tr>';
}

if ($motion->dateResolution != '') {
    echo '<tr><th>' . \Yii::t('motion', 'resoluted_on') . ':</th>
       <td>' . Tools::formatMysqlDate($motion->dateResolution, null, false) . '</td>
     </tr>';
}

echo '<tr><th>' . \Yii::t('motion', ($motion->isSubmitted() ? 'submitted_on' : 'created_on')) . ':</th>
       <td>' . Tools::formatMysqlDateTime($motion->dateCreation, null, false) . '</td>
                </tr>';

$admin = User::havePrivilege($controller->consultation, User::PRIVILEGE_SCREENING);
if ($admin && count($motion->getMyConsultation()->tags) > 0) {
    echo '<tr><th>' . \Yii::t('motion', 'tag_tags') . ':</th><td class="tags">';

    $tags         = [];
    $used_tag_ids = [];
    foreach ($motion->tags as $tag) {
        $used_tag_ids[] = $tag->id;
        $str            = Html::encode($tag->title);
        $str            .= Html::beginForm('', 'post', ['class' => 'form-inline delTagForm delTag' . $tag->id]);
        $str            .= '<input type="hidden" name="tagId" value="' . $tag->id . '">';
        $str            .= '<button type="submit" name="motionDelTag">' . \Yii::t('motion', 'tag_del') . '</button>';
        $str            .= Html::endForm();
        $tags[]         = $str;
    }
    echo implode(', ', $tags);

    echo '&nbsp; &nbsp; <a href="#" class="tagAdderHolder">' . \Yii::t('motion', 'tag_new') . '</a>';
    echo Html::beginForm('', 'post', ['id' => 'tagAdderForm', 'class' => 'form-inline hidden']);
    echo '<select name="tagId" title="' . \Yii::t('motion', 'tag_select') . '" class="form-control">
        <option>-</option>';

    foreach ($motion->getMyConsultation()->tags as $tag) {
        if (!in_array($tag->id, $used_tag_ids)) {
            echo '<option value="' . IntVal($tag->id) . '">' . Html::encode($tag->title) . '</option>';
        }
    }
    echo '</select>
            <button class="btn btn-primary" type="submit" name="motionAddTag">' .
        \Yii::t('motion', 'tag_add') .
        '</button>';
    echo Html::endForm();
    echo '</td> </tr>';

} elseif (count($motion->tags) > 0) {
    echo '<tr>
       <th>' . (count($motion->tags) > 1 ? \Yii::t('motion', 'tags') : \Yii::t('motion', 'tag')) . '</th>
       <td>';

    $tags = [];
    foreach ($motion->tags as $tag) {
        $tags[] = $tag->title;
    }
    echo Html::encode(implode(', ', $tags));

    echo '</td></tr>';
}

if ((!isset($skip_drafts) || !$skip_drafts) && $motion->getMergingDraft(true)) {
    echo '<tr class="mergingDraft"><th>';
    echo \Yii::t('motion', 'merging_draft_th');
    echo '</th><td>';
    $url = UrlHelper::createMotionUrl($motion, 'merge-amendments-public');
    echo str_replace('%URL%', Html::encode($url), \Yii::t('motion', 'merging_draft_td'));
    echo '</td></tr>' . "\n";
}

echo '</table></div>';
