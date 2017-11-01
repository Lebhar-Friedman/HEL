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
<?php
$id = 0;
$alerts = array();
?>
<style>
    .btn-create-alert {
        border-radius: 3px;
        float: right;
        width: 130px;
        height: 42px;
        background-color: #f59a38;
        color: #ffffff;
        text-align: center;
        padding: 11px 7px;
        cursor: pointer;
        margin: -12px auto;
        transition: all 0.3s ease;
    }
    .form-create-alert {
        background-color: #f9f9f9;
        width: 100%;
        min-height: 122px;
        border-bottom: 1px solid #dddddd;
        padding: 25px 20px;
        font-size: 16px;
    }
    .field{float: left;margin-left: 20px;}
    .field>label {
        margin-bottom: 5px; 
        padding-left: 13px; 
    }
    .field>input,.field>select {
        border: 1px solid #dbdbdb;
        background-color: #ffffff;
        width: 100%;
        height: 44px;
        font-size: 16px;
        padding: 0px 12px;
    }
    .field.field-zipcode{width: 82px;margin-left: 0px;}
    .field.field-keyword{width: 506px;}
    .field.field-sort{width: 102px;}
    .field.add{width: 80px;}
    .field>.btn-add-alert{ 
        border-radius: 3px;        
        height: 42px;
        color: #ffffff;
        text-align: center;
        padding: 11px 7px;
        background-color: #f59a38;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .btn-create-alert:hover,.field>.btn-add-alert:hover{background-color: #1e5ba9;}
    @media (max-width: 767px) and (min-width: 0px){
        .field {margin-left: 0px;}
        .field.field-zipcode,.field.field-keyword,.field.field-sort,.field.add{width: 100%;}
        .form-create-alert {margin-bottom: 30px;display: block;}
    }
</style>

<div class="container alert-cust-container">

    <div class="profile-alert-nav clearfix">
        <a href="<?= yii\helpers\Url::to(['user/profile']) ?>">Saved Events</a>
        <a href="<?= BaseUrl::base() ?>/user/alerts" class="active">Alerts</a>
    </div>
    <div class="profile-alert-container">
        <div class="alert-container">
            <div class="alert-h">Alerts
                <!--<div class="btn-create-alert">Create Alert</div>-->
            </div>
<!--            <div class="form-create-alert">
                <div class="clearfix" style="margin: 0px;">
                    <div class=" field field-zipcode">
                        <label class="">Zip Code</label>
                        <input type="text" class="">
                    </div>
                    <div class=" field field-keyword">
                        <label class="">Keyword (optional)</label>
                        <input type="text" class="">
                    </div>
                    <div class=" field field-sort">
                        <label class="">Sort By</label>
                        <select class="">
                            <option value="Closest">Closest</option>
                            <option value="Nearest">Nearest</option>
                        </select>
                    </div>
                    <div class="field add">
                        <label class="">&nbsp;</label>
                        <div class="btn-add-alert">Add</div>
                    </div>
                </div>
            </div>-->
            <?php if (isset($selected_alerts['alerts'])) { ?>
                <?php foreach ($selected_alerts['alerts'] as $single_alert_obj) { ?>
                    <div class="alert-text clearfix" id="alert_<?= ++$id ?>">
                        <a href="javascript:;" class="single_alert" onclick="delete_alert('<?= (string) $single_alert_obj['_id'] ?>',<?= $id ?>)">
                            <img src="<?= BaseUrl::base() ?>/images/crose-btn2.png" alt="" class="single_alert_img" />
                        </a>
                        <?php if ($single_alert_obj['type'] === "exact_location") { ?>
                            <?= $single_alert_obj['zip_code'] . ' | ' . $single_alert_obj['street'] . ', ' . $single_alert_obj['city'] . ', ' . $single_alert_obj['state']; ?>
                        <?php } else { ?>
                            <?php
                            if ($single_alert_obj['zip_code'] !== null) {
                                echo $single_alert_obj['zip_code'] . ' | ';
                            }
                            ?>
                            <?php
                            foreach ($single_alert_obj['keywords'] as $value) {
                                array_push($alerts, $value);
                            }
                            ?>
                            <?php foreach ($single_alert_obj['filters'] as $filter) { ?>
                                <?php array_push($alerts, $filter); ?>
                            <?php } ?>
                            <?= implode(", ", $alerts); ?>
                        <?php } ?>

                    </div>
                <?php } ?>
            <?php } else { ?>
                <h2 class="text-center">No alerts saved yet</h2>
            <?php } ?>
        </div>

    </div>
</div>

<?php Pjax::end() ?>
