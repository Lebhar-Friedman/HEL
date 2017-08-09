<?php

use common\functions\GlobalFunctions;
use yii\helpers\BaseUrl;
use yii\web\JqueryAsset;
use yii\widgets\Pjax;
?>
<?php $this->registerCssFile('@web/css/chosen.min.css'); ?>
<?php $this->registerJsFile('@web/js/chosen.jquery.min.js', ['depends' => [JqueryAsset::className()]]); ?>
<?php $this->registerJsFile('@web/js/user.js', ['depends' => [JqueryAsset::className()]]); ?>

<?php Pjax::begin(['id' => 'alerts-view', 'timeout' => 30000, 'enablePushState' => false]); ?>
<?php $id = 0; ?>

<div class="container alert-cust-container">

    <div class="profile-alert-nav clearfix">
        <a href="<?= BaseUrl::base() ?>/user/profile">Profile</a>
        <a href="<?= BaseUrl::base() ?>/user/alerts" class="active">Alerts</a>
    </div>
    <div class="profile-alert-container">
        <div class="alert-container">
            <div class="alert-h">Alerts</div>
            <?php foreach ($selected_alerts as $alert) { ?>
                <?php foreach ($alert['alerts'] as $single_alert) { ?>
                    <div class="alert-text clearfix" id="alert_<?= ++$id ?>">
                        <?= $single_alert ?>
                        <a href="javascript:;" onclick="delete_alert('<?= addslashes($single_alert) ?>',<?= $id ?>)"><img src="<?= BaseUrl::base() ?>/images/crose-btn2.png" alt="" /></a>
                    </div>
                <?php } ?>
            <?php } ?>
            <div class="alert-text alert-with-save clearfix ">
                <select class="alert_select"  style="width:70%;" name="alert" id="alert_select">
                    <option value="0">Select Category for alert</option>
                    <?php foreach (GlobalFunctions::getKeywords() as $keyword) { ?>
                        <?php if (isset($selected_alerts) && !empty($selected_alerts)) { ?>
                            <?php if (in_array($keyword['text'], $selected_alerts)) continue; ?> 
                        <?php } ?>
                        <option value="<?= $keyword['text'] ?>"><?= $keyword['text'] ?></option>
                    <?php } ?>
                </select>
                <a href="javascript:;" onclick="saveAlert()" class="save-new-alert">Save</a>
            </div>
        </div>

    </div>
</div>

<?php Pjax::end() ?>