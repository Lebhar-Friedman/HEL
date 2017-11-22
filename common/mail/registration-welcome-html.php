<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */
//$confirmLink=Yii::$app->urlManager->createAbsoluteUrl(['site/confirm', 'id' => $user->id,'key'=> $user->auth_key, 'url'=> $url]);
//$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>
<style>
    .password-reset{
        padding-left: 20px !important; 
        padding-right: 20px !important;
        text-align: left !important;
    }
    .password-reset p{
        
        text-align: left !important;
    }
    .password-reset h1{
/*        padding-left: 20px !important; 
        padding-right: 20px !important;*/
        text-align: left !important;
    }
</style>
<div class="password-reset" style="padding-left: 20px;padding-right: 20px;">
    <h1>Welcome to Health Events Live!</h1>
    <p>Thank you for signing up — a new world of free and low-cost health care is waiting for you.</p>
    <p>Login anytime and go to "My Account” to create email alerts for upcoming events near you. That why you’ll never miss out on valuable health services coming your way. You can also save event details for later, add events to your calendar, and more.</p>
    <p>It’s time to take your health into your own hands with more access to care, greater convenience, and less cost!</p>
</div>
