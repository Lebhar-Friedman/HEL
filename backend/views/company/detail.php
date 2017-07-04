<?php

use yii\helpers\BaseUrl;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$baseUrl = BaseUrl::base();
?>


<h1>Company detail</h1>
<?php
$form = ActiveForm::begin([
            'id' => 'companyForm',
            'action' => $baseUrl . '/company/detail',
//            'enableAjaxValidation' => true,
            'validateOnBlur' => true,
            //'validateOnChange' => false,
            //'enableClientValidation' => true,
//            'validationUrl' => $baseUrl . 'company/validate',
            'fieldConfig' => ['template' => "{input}{error}"],
            'options' => ['enctype' => 'multipart/form-data']
        ]);
?>

<?= $form->field($model, 'name', ['inputOptions' => ['class' => 'form-control', 'placeholder' => 'Company Name']])->textInput()->label(false); ?>
<?= $form->field($model, 'contact_name', ['inputOptions' => ['class' => 'form-control', 'placeholder' => 'Contact Name']])->textInput()->label(false); ?>
<?= $form->field($model, 'phone', ['inputOptions' => ['class' => 'form-control', 'placeholder' => 'Phone number']])->textInput()->label(false); ?>
<?= $form->field($model, 'email', ['inputOptions' => ['class' => 'form-control', 'placeholder' => 'email']])->textInput()->label(false); ?>
<?= $form->field($model, 'logo')->fileInput() ?>
<!--<input type="file" name="companyForm[image]" id="image" value="">-->
<?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
<?php $form->end(); ?>