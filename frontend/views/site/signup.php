<?php
/* @var $this View */
/* @var $form ActiveForm */
/* @var $model SignupForm */

use frontend\models\SignupForm;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

$this->title = 'Health Events Live: Sign up';
$this->params['breadcrumbs'][] = $this->title;

$get_email = '';
if (isset($_GET['email'])) {
    $get_email = $_GET['email'];
}
?>
<style>
    body{
        background:#eaeaea !important;
    }
    .result-header{
        background: #FFF !important;
        margin-bottom: 15px !important;
        border-bottom: 1px solid #2aaae2;
    }
</style>
    <div class="row">
        <div class="col-lg-1">
        </div>
        <div class="col-lg-10">
            <div class="middle-content">
                <div class="signup-form">
                    <h1><?= Html::encode($this->title) ?></h1>
                    <h2>Create an Account</h2>
                    <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <?= $form->field($model, 'first_name', ['inputOptions' => ['placeholder' => 'First Name', 'class' => 'f-name-txtbx',]])->textInput(['autofocus' => true])->label(false) ?>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <?= $form->field($model, 'last_name', ['inputOptions' => ['placeholder' => 'Last Name', 'class' => 'f-name-txtbx',]])->textInput(['autofocus' => true])->label(false) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <?= $form->field($model, 'email', ['inputOptions' => ['placeholder' => 'Email', 'class' => 'f-name-txtbx', 'value' => $get_email,]])->textInput(['autofocus' => true])->label(false) ?>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <?= $form->field($model, 'username', ['inputOptions' => ['placeholder' => 'Username', 'class' => 'f-name-txtbx',]])->textInput(['autofocus' => true])->label(false) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <?= $form->field($model, 'password', ['inputOptions' => ['placeholder' => 'Password', 'class' => 'f-name-txtbx']])->passwordInput()->label(false) ?>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <?= $form->field($model, 'confirm_password', ['inputOptions' => ['placeholder' => 'Password', 'class' => 'f-name-txtbx']])->passwordInput()->label(false) ?>
                        </div>
                    </div>
                    <div class="create-account">
                        <!--<a href="#">Create Account</a>-->
                        <?= Html::submitButton('Create Account', ['class' => 'btn btn-radius-0', 'name' => 'signup-button']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                    <div class="sign-in">
                        Already have an account? <a href="login">Sign In</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

