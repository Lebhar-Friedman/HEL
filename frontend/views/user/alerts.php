<?php

use common\functions\GlobalFunctions;
use yii\helpers\BaseUrl;
use yii\web\JqueryAsset;
use yii\widgets\Pjax;

$this->title = 'Health Events Live: Alerts';
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
            <?php if (isset($selected_alerts['alerts'])) { ?>
                <div class="alert-h">Alerts</div>
                <?php foreach ($selected_alerts['alerts'] as $single_alert_obj) { ?>
                    <div class="alert-text clearfix" id="alert_<?= ++$id ?>">
                        <a href="javascript:;" class="single_alert" onclick="delete_alert('<?= (string) $single_alert_obj['_id'] ?>',<?= $id ?>)">
                            <img src="<?= BaseUrl::base() ?>/images/crose-btn2.png" alt="" class="single_alert_img" />
                        </a>
                        <?php
                        foreach ($single_alert_obj['keywords'] as $value) {
                            echo $value . ', ';
                        }
                        ?>
                        <?php foreach ($single_alert_obj['filters'] as $filter) { ?>
                            <?= $filter . ', ' ?>
                        <?php } ?>
                        <?php if($single_alert_obj['zip_code'] !== null){
                            echo 'Zip='.$single_alert_obj['zip_code'];
                        }?>
                        <?php
                        if($single_alert_obj['sort'] !== null){
                            echo ',Sort='.$single_alert_obj['sort'];
                        }
                        ?>
                        
                    </div>
                <?php } ?>
            <?php } else { ?>
                <h2 class="text-center">No alerts saved yet</h2>
            <?php } ?>
        </div>

    </div>
</div>

<?php Pjax::end() ?>