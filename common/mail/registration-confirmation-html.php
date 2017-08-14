<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */
$confirmLink=Yii::$app->urlManager->createAbsoluteUrl(['site/confirm', 'id' => $user->id,'key'=> $user->auth_key, 'url'=> $url]);
//$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>
<div class="password-reset">
    <p>Hello <?= Html::encode($user->username) ?>,</p>
    <p>Follow the link below to complete your registration:</p>
    <p><?= Html::a(Html::encode($confirmLink), $confirmLink) ?></p>
</div>
