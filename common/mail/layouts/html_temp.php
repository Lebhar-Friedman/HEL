<?php
/* @var $this View */
/* @var $content string */

use app\assets\AppAsset;
use yii\helpers\Html;
use yii\web\View;

//AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body style="-webkit-font-smoothing: antialiased;-webkit-text-size-adjust: none;width:100%!important;height:100%;margin:0;padding: 0;">
        <?php $this->beginBody() ?>
        <table class="head-wrap" bgcolor="#dcdcdc" style="width: 100%;">
            <tr>
                <td></td>
                <td class="header container" style="    display: block!important;max-width: 600px!important;margin: 0 auto!important;clear: both!important;">
                    <div class="content" style="padding: 0px;max-width: 600px;text-align: center; margin: 0 auto;display: block;">
                        <table style="width: 100%;">
                            <tr>
                                <td></td>
                                <td align="right" style="color:#000 !important;"><h6 class="collapse" style="margin: 0;padding: 7px 0 5px 0;font-size: 28px; text-transform: uppercase; color:#dcdcdc !important; font-weight:normal;font-family: roboto regular, Helvetica, Arial, sans-serif;line-height: 1.1;"><img src="http://www.buymiles.com/images/call-ico.png" style="margin-right:10px;" alt=""/><a href='#' style="color:#000 !important; text-decoration:none; font-size: 17px">1-800-928-9645</a></h6></td>
                            </tr>
                        </table>
                    </div>
                </td>
                <td></td>
            </tr>
        </table><!-- /HEADER -->


        <!-- BODY -->
        <table class="body-wrap" style="width: 100%;background: #f1f1f1;">
            <tr align="center">
                <td></td>
                <td><img src="http://www.buymiles.com/images/email_logo.png" style="margin:20px 0 20px 0" /></td>
                <td></td>        
            </tr>

            <tr>
                <td></td>
                <td class="container" bgcolor="#FFFFFF" style="border:#e5e5e5 solid 1px;display: block!important;max-width: 600px!important;margin: 0 auto!important;clear: both!important;">

                    <div class="content" style="padding: 0px;max-width: 600px;text-align: center; margin: 0 auto;display: block;">
                        <table style="width: 100%;">
                            <tr>
                                <td>
                                    <?php echo 'afsdasdfads'; ?>   
                                    <br /><br />
                                </td>
                            </tr>
                        </table>
                    </div>

                </td>
                <td></td>
            </tr>
        </table><!-- /BODY -->
        <table style="background-color:#f1f1f1" width="100%">
            <tr>
                <td align="center">
                    <p style="margin-top:15px;">
                        <a href="http://www.buymiles.com/privacy" style="color:#000; text-decoration:none; font-size:16px; padding-right:10px; border-right:solid 1px #000;font-family: roboto, Helvetica, Arial, sans-serif;"> Privacy Policy</a>
                        <a href="http://www.buymiles.com/terms" style="color:#000; text-decoration:none; font-size:16px; padding-right:10px;font-family: roboto, Helvetica, Arial, sans-serif;">Terms and Conditions</a>
                    </p>
                </td>
            </tr>
        </table>
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
                                        <a href="https://web.facebook.com/Buy-Miles-1767389113531963/" style="color: #2BA6CB;"><img src="http://www.buymiles.com/images/fb-btn_2.png" /></a>
                                        <a href="https://plus.google.com/113857019125850832996" style="color: #2BA6CB;"><img src="http://www.buymiles.com/images/gp-btn_2.png" /></a>
                                        <a href="https://www.pinterest.com/buymiles/"style="color: #2BA6CB;"><img src="http://www.buymiles.com/images/Pinterest.png" /></a>
                                        <a href="https://twitter.com/buy_miles" style="color: #2BA6CB;"><img src="http://www.buymiles.com/images/twitter-btn_2.png" /></a>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <p style="color:#fff; font-size:16px; font-family: roboto, Helvetica, Arial, sans-serif;    margin-top: 0px;">Â© Copyrights 2016</p>
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
