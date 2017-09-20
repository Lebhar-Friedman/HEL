<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-content">
    <div class="login-container">
        <div class="text-center">
            <img src="<?= Yii::$app->request->baseUrl . '/' ?>images/logo.png" alt="" class="img-cust-responsive">
        </div>
        <div class="login-contents">
            <h1>Login</h1>
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

            <?= $form->field($model, 'username', ['inputOptions' => ['placeholder' => 'Email', 'class' => 'login-txtbx emailinputbg', 'label' => '',]])->label(false); ?>
            <!--<?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>-->

            <?= $form->field($model, 'password', ['inputOptions' => ['placeholder' => 'Password', 'class' => 'login-txtbx lock-img',]])->passwordInput()->label(false) ?>
            <!--<?= $form->field($model, 'password')->passwordInput() ?>-->

            <?= $form->field($model, 'rememberMe')->checkbox() ?>

            <div>
                <?= Html::submitButton('Login', ['class' => ['login-btn'], 'name' => 'login-button']) ?>
                <!--<?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>-->
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

