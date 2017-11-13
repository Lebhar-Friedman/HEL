<?php
/* @var $this View */
/* @var $content string */

use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\helpers\BaseUrl;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

AppAsset::register($this);
$baseUrl = Yii::$app->request->baseUrl;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <?php if (ENV === "live") { ?>
            <!-- Google Tag Manager -->
            <script>
                (function (w, d, s, l, i) {
                    w[l] = w[l] || [];
                    w[l].push({'gtm.start':
                                new Date().getTime(), event: 'gtm.js'});
                    var f = d.getElementsByTagName(s)[0],
                            j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
                    j.async = true;
                    j.src =
                            'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
                    f.parentNode.insertBefore(j, f);
                })(window, document, 'script', 'dataLayer', 'GTM-P2WNXC');
            </script>
            <!-- End Google Tag Manager -->
        <?php } ?>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <link rel="shortcut icon" href="<?php echo Yii::$app->getHomeUrl(); ?>images/favicon.png" type="image/x-icon" />
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,700" rel='stylesheet' type='text/css'>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
        <?php $this->beginBody() ?>
        <!-- Google Tag Manager (noscript) -->
        <noscript> <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-P2WNXC" height="0" width="0" style="display:none;visibility:hidden"></iframe> </noscript>
        <!-- End Google Tag Manager (noscript) -->
        <script type="text/javascript">
            var baseUrl = '<?php echo Url::base(true) . "/"; //"http://" . $_SERVER["HTTP_HOST"] . Yii::$app->request->baseUrl . "/";                                                                               ?>';
            var userType = '<?php echo (isset(Yii::$app->user->identity->role) ? Yii::$app->user->identity->role : ''); ?>';
            var userId = '<?php echo (isset(Yii::$app->user->identity->_id) ? Yii::$app->user->identity->_id : ''); ?>';
            var image_url = '<?= BaseUrl::base() ?>/images/';
        </script>        


        <!--<header>-->
        <div class="result-header">
            <div class="container">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-5 col-xs-6">

                            <div class="result-logo hide-on-mobile">
                                <a href="<?= $baseUrl ?>/"><img src="<?= Yii::$app->getHomeUrl(); ?>images/logo.png" alt="" class="img-responsive"/></a>
                            </div>
                            <div class="display-on-mobile mobile-logo">
                                <a href="<?= $baseUrl ?>/"><img src="<?= Yii::$app->getHomeUrl(); ?>images/logo3.png" alt="" class="img-responsive"/></a>
                            </div>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-7  col-xs-6">
                            <div class="result-sign-up clearfix">
                                <?php
                                if (Yii::$app->user->isGuest) {
                                    ?>
                                    <a href="<?= $baseUrl ?>/site/signup" class="border" style="font-weight: bold;">Sign Up</a>
                                    <a href="<?= $baseUrl ?>/site/login" style="font-weight: bold;">Log In</a>
                                <?php } else {
                                    ?>
                                    <a href="<?= $baseUrl ?>/site/logout" class="active show_menu">Log out</a>
                                    <a href="<?= Url::to(['user/profile']) ?>">My Account</a>
                                    <!--<a class="active show_menu" style="font-weight: bold;"><?= Yii::$app->user->identity->first_name ?></a>-->
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--</header>-->

        <div class="container" style="min-height: 65vh;">
            <div class="container">
                <div class="row" style="margin-left: 0px;">
                    <?= Alert::widget() ?>
                </div>
            </div>
            <?= $content ?>
        </div>
    <!--</div>-->
    <!--<footer>-->
    <!--            <div class="social-icon">
                    <a href="#"><img src="<?= Yii::$app->getHomeUrl(); ?>images/social-icon.png" alt="" /></a>
                    <a href="#"><img src="<?= Yii::$app->getHomeUrl(); ?>images/social-icon2.png" alt="" /></a>
                    <a href="#"><img src="<?= Yii::$app->getHomeUrl(); ?>images/social-icon3.png" alt="" /></a>
                    <a href="#"><img src="<?= Yii::$app->getHomeUrl(); ?>images/social-icon4.png" alt="" /></a>
                </div>
                Copyright © 2017 Health Events Live Plus-->

    <div class="result-footer">
        <div class="container">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4">
                        <div class="footer-logo">
                            <a href="<?= Url::to(['/']); ?>"><img src="<?= BaseUrl::base() ?>/images/logo2.png" alt="" /></a>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-8">
                        <div class="footer-right-side">
                            <!--<a href="#">Sitemap</a>-->  
                            <a href="<?= BaseUrl::base() . '/site/terms-privacy' ?>">Terms & Privacy</a>
<!--                            &bull;-->
                            <a href="<?= BaseUrl::base() . '/event/directory' ?>">Directory</a>
                            <span></span>
                            © Health Events Live.  All rights reserved.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--</footer>-->


    <?php $this->endBody() ?>
    <!-- Go to www.addthis.com/dashboard to customize your tools --> 
    <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5982f4419c3e0add"></script> 
</body>
</html>
<?php $this->endPage() ?>
