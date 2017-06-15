<?php


use common\models\LoginForm;
use yii\authclient\widgets\AuthChoice;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\web\View; 
/* @var $this View */
/* @var $form ActiveForm */
/* @var $model LoginForm */

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to login:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

            <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <?= $form->field($model, 'rememberMe')->checkbox() ?>

            <div style="color:#999;margin:1em 0">
                If you forgot your password you can <?= Html::a('reset it', ['site/request-password-reset']) ?>.
            </div>

            <div class="form-group">
                <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <?php $authAuthChoice = AuthChoice::begin(['baseAuthUrl' => ['site/auth'], 'autoRender' => false]); ?>
    <!--<ul>-->
        <?php foreach ($authAuthChoice->getClients() as $client): ?>
            <?= Html::a('Log in with ' . $client->title, ['site/auth', 'authclient' => $client->name,], ['class' => "btn btn-block btn-default $client->name "]) ?>
        <?php endforeach; ?>
    <!--</ul>-->
    <?php AuthChoice::end(); ?>
    
    <?php foreach ($authAuthChoice->getClients() as $client): ?>
    <?= $authAuthChoice->clientLink($client)?>
<?php endforeach; ?>
</div>
