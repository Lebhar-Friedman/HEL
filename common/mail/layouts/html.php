<?php

use yii\helpers\Html;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
        <?php $this->beginBody() ?>
        <!-- BODY -->    
        <table class="body-wrap" style="width: 100%;background: #f1f1f1;">
            <tr align="center">
                <td></td>
                <td style="padding-bottom: 30px;padding-top: 30px"><img src="http://13.59.81.62/HEL/frontend/web/images/logo.png" style="margin:20px 0 20px 0" /></td>
                <td></td>        
            </tr>

            <tr >
                <td width="10%"></td>
                <td width="80%" class="container" bgcolor="#FFFFFF" style="border:#e5e5e5 solid 1px;display: block!important;max-width: 600px!important;margin: 0 auto!important;clear: both!important; ">

                    <div class="content" style="padding: 0px;max-width: 600px;text-align: center; margin: 0 auto;display: block;min-height: 400px;">
                        <table style="width: 100%;min-height: 1000px">
                            <tr style="min-height: 1000px">
                                <td style="padding-bottom: 50px;padding-top: 50px;margin-bottom: 50px;">
                                    <?= $content ?>         
                                    <br /><br />
                                </td>
                            </tr>
                        </table>
                    </div>

                </td>
                <td width="10%"></td>
            </tr>
            <tr style="background-color: #f1f1f1">
                <td style="margin-bottom: 50px;"></td>
                <td></td>
                <td></td>
            </tr>
        </table><!-- /BODY -->
        <!-- FOOTER -->
        <table class="footer-wrap" style="width: 100%;clear: both!important;height: 97px;background: #141414;">
            <tr>
                <td></td>
                <td class="container" style="display: block!important;max-width: 600px!important;margin: 0 auto!important;clear: both!important;">

                    <!-- content -->
                    <div class="content" style="padding: 0px;max-width: 600px;text-align: center; margin: 0 auto;display: block;">
                        <table style="width: 100%;">
                            <tr>
                                <td align="center">
                                    <p style="margin-top:15px;">
                                        <a href="https://web.facebook.com/" style="color: #2BA6CB;"><img src="http://www.buymiles.com/images/fb-btn_2.png" /></a>
                                        <a href="https://plus.google.com/" style="color: #2BA6CB;"><img src="http://www.buymiles.com/images/gp-btn_2.png" /></a>
                                        <a href="https://www.pinterest.com/"style="color: #2BA6CB;"><img src="http://www.buymiles.com/images/Pinterest.png" /></a>
                                        <a href="https://twitter.com/" style="color: #2BA6CB;"><img src="http://www.buymiles.com/images/twitter-btn_2.png" /></a>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <p style="color:#fff; font-size:16px; font-family: roboto, Helvetica, Arial, sans-serif;    margin-top: 0px;">© Copyrights 2016</p>
                                </td>
                            </tr>
                        </table>
                    </div><!-- /content -->

                </td>
                <td></td>
            </tr>
        </table><!-- /FOOTER -->

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
