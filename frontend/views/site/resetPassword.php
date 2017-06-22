<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-lg-2"></div>
    <div class="col-lg-8">
        <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>
        <center>
            <div class="middle-content">
                <div class="signup-form">
                    <h1>Reset Password</h1>
                    <div>
                        <?= $form->field($model, 'password',['inputOptions'=> ['class'=> 'txtbx','placeholder' => 'Enter your new password']])->passwordInput(['autofocus' => true])->label(false) ?>
                    </div>
                </div>
                <div class="submit">
                    <?= Html::submitButton('Submit', ['class' => 'btn']) ?>
                </div>
            </div>
        </center>
        <?php ActiveForm::end(); ?>
    </div>
</div>
