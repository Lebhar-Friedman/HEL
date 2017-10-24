<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Health Events Live: Reset Password';
$this->params['breadcrumbs'][] = $this->title;
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
<?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>
        <center>
            <div class="middle-content">
                <div class="signup-form">
                    <h1>Forgot Password</h1>
                    <div>
<?= $form->field($model, 'email', ['inputOptions' => ['class' => 'txtbx', 'placeholder' => 'Enter Your Email']])->textInput(['autofocus' => true])->label(false) ?>
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
