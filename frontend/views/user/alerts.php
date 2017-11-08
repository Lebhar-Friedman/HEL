<?php

use common\functions\GlobalFunctions;
use yii\helpers\BaseUrl;
use yii\helpers\Url;
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
$this->registerCss(
        ".btn-create-alert {
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
    .btn-create-alert:hover,.field>.btn-add-alert:hover{background-color: #D38735;}
    @media (max-width: 767px) and (min-width: 0px){
        .field {margin-left: 0px;}
        .field.field-zipcode,.field.field-keyword,.field.field-sort,.field.add{width: 100%;}
        .form-create-alert {margin-bottom: 30px;display: block;}
    }
    @media only screen and (device-width: 768px) {
        .field.field-zipcode {
            width: 100%;
            margin-left: 0px;
        }
        .field.field-keyword {
            width: 100%;
            margin-left: 0px;
        }
        .field.field-sort {
            width: 100%;
            margin-left: 0px;
        }
        .field.add {
            width: 100%;
            margin-left: 0px;
        }
    }
    .chosen-choices{
        min-height: 45px;
        display: block;
        border: 1px solid #dbdbdb;
        background: #FFF;
        padding-top: 3px !important;
        padding-bottom: 3px !important;
        background-image: none !important;
    }
    .chosen-container-multi .chosen-choices li.search-field input[type=text]{
        /*color: #000 !important;*/
    }
    .chosen-container{
        width: 100% !important;
    }
    .chosen-container-multi .chosen-choices {padding:0 14px}
    .chosen-container-multi .chosen-choices li.search-field {padding-top: 4px;}
    .chosen-container-multi .chosen-choices li.search-field input[type=text]{
        /*text-align: center !important;*/
        font-size: initial;
    }

    /* NEW */
    .chosen-container-multi 
    .chosen-choices {
        padding: 0 6px;
    }
    .chosen-container-multi 
    .chosen-choices 
    li.search-choice {
        background: #fff;
        border-radius: 2px;
        box-shadow: none;
        padding: 8px 24px 8px 8px;
    }
    .chosen-container-multi 
    .chosen-choices 
    li.search-choice 
    .search-choice-close {
        top: 9px;
        right: 5px;
    }");
?>

<div class="container alert-cust-container">

    <div class="profile-alert-nav clearfix">
        <a href="<?= Url::to(['user/profile']) ?>">Saved Events</a>
        <a href="<?= BaseUrl::base() ?>/user/alerts" class="active">Alerts</a>
    </div>
    <div class="profile-alert-container">
        <div class="alert-container">
            <div class="alert-h">Alerts
                <div class="btn-create-alert" onclick="showAddalertForm()">Create Alert</div>
            </div>
            <div class="form-create-alert hidden" id="form-create-alert">
                <form action="#" id="alert_form">
                    <div class="clearfix" style="margin: 0px;">
                        <div class=" field field-zipcode">
                            <label class="">Zip Code</label>
                            <input type="text" class="" name="zipcode">
                        </div>
                        <div class=" field field-keyword">
                            <label class="">Keyword (optional)</label>
                            <select class="html-multi-chosen-select" multiple="multiple" style="width:100%;" name="keywords[]" id="keywords">
                                <?php foreach (GlobalFunctions::getKeywords() as $keyword) { ?>
                                    <option data-option-category=<?= $keyword['type'] ?> value="<?= $keyword['text'] ?>"><?= $keyword['text'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class=" field field-sort">
                            <label class="">Sort By</label>
                            <select class="" name="sortBy">
                                <option value="closest">Closest</option>
                                <option value="soonest">Soonest</option>
                            </select>
                        </div>
                        <div class="field add">
                            <label class="">&nbsp;</label>
                            <input type="submit" class="btn-add-alert" value="Add">
                        </div>
                    </div>
                </form>
            </div>
            <?php if (isset($selected_alerts['alerts'])) { ?>
                <?php foreach ($selected_alerts['alerts'] as $single_alert_obj) { ?>
                    <?php $alerts = []; ?>
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
