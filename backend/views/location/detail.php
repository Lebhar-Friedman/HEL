<?php

use backend\components\CustomLinkPager;
use yii\helpers\BaseUrl;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\web\JqueryAsset;
use components\GlobalFunction;
?>
<?php
//var_dump($locations);
$this->registerJsFile('@web/js/location.js', ['depends' => [JqueryAsset::className()]]);
$this->title = 'Location Detail';
$baseUrl = Yii::$app->request->baseUrl;
?>
<style>
    .help-block{margin-top: 0px;
                min-height: 22px;
                font-size: medium;}
    .form-group {
        margin-bottom: 0px; 
    }
    .mrgdd{margin-bottom: 0px;}
</style>

<div class="col-lg-12">
    <div class="csv-coommp-content-1">
        <div class="upload clearfix">
            <div >
                <h3>Location</h3>
            </div>
        </div>

        <div class ="row">
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 uplooad-btn-2">
                <a href="#"><img src="<?= $baseUrl ?>/images/Shape3.png"></a>
            </div>

            <div class ="col-lg-8 col-md-8 col-sm-6"></div>
            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6 edit-btn-1">
                <a id="edit_btn_location" href="javascript:;" style="display: <?=($model->hasErrors())? 'none':'block'?>">Edit</a>
                <a id="cancel_btn_location" href="javascript:;" style="display: <?=($model->hasErrors())? 'block':'none'?>">Cancel</a>
            </div>
        </div> 
        <br>
        <br>
        <div id="detailLocation" style="display: <?=($model->hasErrors())? 'none':'block'?>">
            <div class ="row mrgd">
                <div class="col-lg-3 col-md-4 col-sm-5  ">
                    <strong>Store#:</strong>
                </div>
                <div class="col-lg-9 col-md-8 col-sm-7   ">
                    <?= $detail->location_id ?>
                </div>
            </div>
            <div class ="row mrgd">
                <div class="col-lg-3 col-md-4 col-sm-5  ">
                    <strong>Name:</strong>
                </div>
                <div class="col-lg-9 col-md-8 col-sm-7   ">
                    <?= $detail->company ?>
                </div>
            </div>

            <div class ="row mrgd">
                <div class="col-lg-3 col-md-4 col-sm-5 ">
                    <strong>Address(Street):</strong>
                </div>
                <div class="col-lg-9 col-md-8 col-sm-7 ">
                    <?= $detail->street ?> 
                </div>
            </div>

            <div class ="row mrgd">
                <div class="col-lg-3 col-md-4 col-sm-5  ">
                    <strong>State:</strong>
                </div>
                <div class="col-lg-9 col-md-8 col-sm-7 ">
                    <?= $detail->state ?>
                </div>
            </div>

            <div class ="row mrgd">
                <div class="col-lg-3 col-md-4 col-sm-5 ">
                    <strong>Zip Code:</strong>
                </div>
                <div class="col-lg-9 col-md-8 col-sm-7 ">
                    <?= $detail->zip ?>
                </div>
            </div>

            <div class ="row mrgd">
                <div class="col-lg-3 col-md-4 col-sm-5 ">
                    <strong>Phone Number:</strong>
                </div>
                <div class="col-lg-9 col-md-8 col-sm-7 ">
                    <?= $detail->phone ?>
                </div>
            </div>

            <div class ="row mrgd">
                <div class="col-lg-3 col-md-4 col-sm-5 ">
                    <strong>Contact Number:</strong>
                </div>
                <div class="col-lg-9 col-md-8 col-sm-7 ">
                    <?= $detail->contact_name ?>
                </div>
            </div>            
        </div>

        <div id="editLocation" style="display: <?=($model->hasErrors())? 'block':'none'?>">
            <?php
            $form = yii\widgets\ActiveForm::begin([
                        'fieldConfig' => ['template' => "{input}{error}"],
                        'options' => ['enctype' => 'multipart/form-data']
            ]);
            ?>
            <div class ="row mrgdd">
                <div class="col-lg-3 col-md-4 col-sm-5  ">
                    <strong>Name:</strong>
                </div>
                <div class="col-lg-6 col-md-8 col-sm-7   ">
                    <?= $form->field($model, 'company', ['inputOptions' => ['class' => 'txetbx', 'placeholder' => 'John Smith']])->textInput()->label(false); ?>
                </div>
            </div>

            <div class ="row mrgdd">
                <div class="col-lg-3 col-md-4 col-sm-5 ">
                    <strong>Address(Street):</strong>
                </div>
                <div class="col-lg-6 col-md-8 col-sm-7 ">
                    <?= $form->field($model, 'street', ['inputOptions' => ['class' => 'txetbx', 'placeholder' => 'Q-357 Dha phase 2 Lahore']])->textInput()->label(false); ?>
                </div>
            </div>

            <div class ="row mrgdd">
                <div class="col-lg-3 col-md-4 col-sm-5  ">
                    <strong>State:</strong>
                </div>
                <div class="col-lg-6 col-md-8 col-sm-7 ">
                    <?= $form->field($model, 'state', ['inputOptions' => ['class' => 'txetbx', 'placeholder' => 'NY']])->textInput()->label(false); ?>
                </div>
            </div>

            <div class ="row mrgdd">
                <div class="col-lg-3 col-md-4 col-sm-5 ">
                    <strong>Zip Code:</strong>
                </div>
                <div class="col-lg-6 col-md-8 col-sm-7 ">
                    <?= $form->field($model, 'zip', ['inputOptions' => ['class' => 'txetbx', 'placeholder' => '108000']])->textInput()->label(false); ?>
                </div>
            </div>

            <div class ="row mrgdd">
                <div class="col-lg-3 col-md-4 col-sm-5 ">
                    <strong>Phone Number:</strong>
                </div>
                <div class="col-lg-6 col-md-8 col-sm-7 ">
                    <?= $form->field($model, 'phone', ['inputOptions' => ['class' => 'txetbx', 'placeholder' => '0900-78601']])->textInput()->label(false); ?>
                </div>
            </div>

            <div class ="row mrgdd">
                <div class="col-lg-3 col-md-4 col-sm-5 ">
                    <strong>Contact Name:</strong>
                </div>
                <div class="col-lg-6 col-md-8 col-sm-7 ">
                    <?= $form->field($model, 'contact_name', ['inputOptions' => ['class' => 'txetbx', 'placeholder' => 'Marshel']])->textInput()->label(false); ?>
                </div>
            </div>

            <div class ="row mrgnd">
                <center>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-6 save-btn-1">
                        <?= \yii\helpers\Html::submitButton('Submit', ['id'=>'btnSubmit', 'class' => 'hidden']) ?>
                        <a href="javascript:void(0);"  onclick="$('#btnSubmit').click()">Save</a>
                    </div> 
                </center>
            </div>
            <?php $form->end(); ?>
        </div>

    </div>
</div>