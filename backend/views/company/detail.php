<?php

use yii\helpers\BaseUrl;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JqueryAsset;
use yii\widgets\ActiveForm;

$baseUrl = BaseUrl::base();
?>
<?php $this->registerJsFile('@web/js/company.js', ['depends' => [JqueryAsset::className()]]); ?>
<div class="row">
    <?php
    $form = ActiveForm::begin([
                'id' => 'companyForm',
                'action' => $baseUrl . '/company/detail',
                'enableAjaxValidation' => true,
                'validateOnBlur' => true,
                'validationUrl' => Yii::$app->urlManager->createUrl("company/company-validation"),
                //'validateOnChange' => false,
                //'enableClientValidation' => true,
                'fieldConfig' => ['template' => "{input}{error}"],
                'options' => ['enctype' => 'multipart/form-data']
    ]);
    ?>
    <?php
    if (!isset($model->logo) || $model->logo === NULL || $model->logo === '') {
        $logo_url = Url::to('@web/images/upload-logo.png');
    } else {
        $logo_url = Url::to('@web/uploads/' . $model->logo);
    }
    ?>
    <div class="col-lg-12">
        <div class="new-comp-content">
            <div class="signup-addnew-form">
                <h2>Add New Company</h2>
                <div class="row">
                    <div class="col-lg-4 col-md-3 col-sm-3">
                        <div class="upload-img">
                            <img src="<?= $logo_url ?>" class="img-responsive"  alt="" id="logo_img"/>
                        </div>
                    </div>
                    <div class="col-lg-7 col-md-9 col-sm-9">
                        <div class="upload-logo">
                            <label for="logo">Upload Logo</label>
                            <?= $form->field($model, 'logo', ['inputOptions' => ['id' => 'logo', 'hidden' => '', 'onchange' => 'logoChange(this)']])->fileInput() ?>
                        </div>
                    </div>
                </div>
                <div class="row row-margin-t">
                    <div class="col-lg-4 col-md-3 col-sm-3">
                        <div class="school-name">Company Name:</div>
                    </div>
                    <div class="col-lg-7 col-md-7 col-sm-7">
                        <div>
                            <?= $form->field($model, 'name', ['inputOptions' => ['class' => 'school-name-textbx', 'placeholder' => 'Company Name']])->textInput()->label(false); ?>
                        </div>
                    </div>
                </div>     
                <div class="row row-margin-top">
                    <div class="col-lg-4 col-md-3 col-sm-3">
                        <div class="school-name">Contact Name:</div>
                    </div>
                    <div class="col-lg-7 col-md-7 col-sm-7">
                        <div>
                            <?= $form->field($model, 'contact_name', ['inputOptions' => ['class' => 'school-name-textbx', 'placeholder' => 'Contact Name']])->textInput()->label(false); ?>
                        </div>
                    </div>

                </div>
                <div class="row row-margin-top">
                    <div class="col-lg-4 col-md-3 col-sm-3">
                        <div class="school-name">Phone:</div>
                    </div>
                    <div class="col-lg-7 col-md-7 col-sm-7">
                        <div>
                            <?= $form->field($model, 'phone', ['inputOptions' => ['class' => 'school-name-textbx', 'placeholder' => 'Phone number']])->textInput()->label(false); ?>
                        </div>
                    </div>
                </div>  
                <div class="row row-margin-top">
                    <div class="col-lg-4 col-md-3 col-sm-3">
                        <div class="school-name">Email:</div>
                    </div>
                    <div class="col-lg-7 col-md-7 col-sm-7">
                        <div>
                            <?= $form->field($model, 'email', ['inputOptions' => ['class' => 'school-name-textbx', 'placeholder' => 'email']])->textInput()->label(false); ?>
                        </div>
                    </div>
                </div>         
                <div class="row row-margin-top">
                    <div class="col-lg-4 col-md-3 col-sm-3">
                        <div class="school-name">Street:</div>
                    </div>
                    <div class="col-lg-7 col-md-7 col-sm-7">
                        <div>
                            <?= $form->field($model, 'street', ['inputOptions' => ['class' => 'school-name-textbx', 'placeholder' => 'Street']])->textInput()->label(false); ?>
                        </div>
                    </div>
                </div>         
                <div class="row row-margin-top">
                    <div class="col-lg-4 col-md-3 col-sm-3">
                        <div class="school-name">City:</div>
                    </div>
                    <div class="col-lg-7 col-md-7 col-sm-7">
                        <div>
                            <?= $form->field($model, 'city', ['inputOptions' => ['class' => 'school-name-textbx', 'placeholder' => 'City']])->textInput()->label(false); ?>
                        </div>
                    </div>
                </div>         
                <div class="row row-margin-top">
                    <div class="col-lg-4 col-md-3 col-sm-3">
                        <div class="school-name">State:</div>
                    </div>
                    <div class="col-lg-7 col-md-7 col-sm-7">
                        <div>
                            <?= $form->field($model, 'state', ['inputOptions' => ['class' => 'school-name-textbx', 'placeholder' => 'State']])->textInput()->label(false); ?>
                        </div>
                    </div>
                </div>         
                <div class="row row-margin-top">
                    <div class="col-lg-4 col-md-3 col-sm-3">
                        <div class="school-name">Zip:</div>
                    </div>
                    <div class="col-lg-7 col-md-7 col-sm-7">
                        <div>
                            <?= $form->field($model, 'zip', ['inputOptions' => ['class' => 'school-name-textbx', 'placeholder' => 'zip code']])->textInput()->label(false); ?>
                        </div>
                    </div>
                </div>         
                <div class="add-new-btn">
                    <?= Html::submitButton('Submit', ['class' => 'btn']) ?>
                <!--<img src="<?php echo Url::to('@web/images/loader.gif') ?>" height="20px" width="20px" style='margin-top: 8px;margin-left: 50px;display:none;' class='loader' >-->
                </div>
            </div>         
        </div>
    </div>
    <?php $form->end(); ?>
</div>
