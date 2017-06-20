<?php

use common\models\LoginForm;
use yii\authclient\widgets\AuthChoice;
use yii\bootstrap\ActiveForm;
use yii\helpers\BaseUrl;
use yii\helpers\Html;
use yii\web\View;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
    <div class="row">
        <div class="col-lg-1"></div>
        <div class="col-lg-10">
            <div class="middle-content">
                <div class="login-form">
                    <h1>Log In</h1>
                    <div class="row">
                        <div class="col-lg-1 col-md-1 col-sm-1"> </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 text-center">
                            <div class="left-colm">
                                <span>Sign In with your Facebook or Google+ account</span>
                            </div>
                            
                            <?php $authAuthChoice = AuthChoice::begin([
                                'baseAuthUrl' => ['site/auth']
                            ]); ?>
                            
                           <?php $social_clients=$authAuthChoice->getClients();?>
                            
                            <div class="fb-signin clearfix">
                                <?= $authAuthChoice->clientLink($social_clients['facebook'],'Sign in with facebook') ?>
                            </div>
                            <div class="gplus-signin">
                                <!--<a href="#">Sign In with Google+</a>-->
                                <?= $authAuthChoice->clientLink($social_clients['google'],'Sign in with Google+') ?>
                            </div>
                            <?php AuthChoice::end(); ?>
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-1 or-mrgn">
                            <img class="" src="<?= BaseUrl::base() . "/images/or.png" ?>" alt="" />
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 text-center">
                            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                            <div class="left-colm">
                                <span>Already have an account</span>
                            </div>
                            <div>
                                <?= $form->field($model, 'username', ['inputOptions' => ['class' => 'email-input email-icon', 'placeholder' => 'Username']])->textInput(['autofocus' => true])->label(FALSE) ?>
                            </div>
                            <div>
                                <?= $form->field($model, 'password', ['inputOptions' => ['class' => 'email-input passowrd-icon', 'placeholder' => 'Password']])->passwordInput()->label(false) ?>
                            </div>
                            <div class="forgot">
                                <?= Html::a('Forgot Password?', ['site/request-password-reset']) ?>
                            </div>
                            <div class="login-account">
                            <?= Html::submitButton('Login', ['class' => 'btn', 'name' => 'login-button']) ?>
                            </div>
                            <div class="sign-in">
                                Don’t have an account? 
                            <?= Html::a('Sign up', ['site/signup']) ?>
                            </div>
<?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
