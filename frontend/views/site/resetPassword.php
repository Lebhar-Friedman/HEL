<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Health Events Live: Reset Password';
//$this->params['breadcrumbs'][] = $this->title;
$this->registerCss(
        "body{
        background:#eaeaea !important;
    }
    .result-header{
        background: #FFF !important;
        margin-bottom: 15px !important;
        border-bottom: 1px solid #2aaae2;
    }");
?>
<div class="row">
    <div class="col-lg-2"></div>
    <div class="col-lg-8">
        <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

        <div class="middle-content">
            <div class="forget-signup-form">
                <h1>Reset Password</h1>
                <div>
                    <?= $form->field($model, 'password', ['inputOptions' => ['class' => 'txtbx', 'placeholder' => 'Enter your new password']])->passwordInput(['autofocus' => true])->label(false) ?>
                </div>
            </div>
            <div class="submit">
                <?= Html::submitButton('Submit', ['class' => 'btn']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
