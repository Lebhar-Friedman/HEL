<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
$baseUrl = Yii::$app->request->baseUrl;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
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
        <script type="text/javascript">
            var baseUrl = '<?php echo \yii\helpers\Url::base(true) . "/"; //"http://" . $_SERVER["HTTP_HOST"] . Yii::$app->request->baseUrl . "/";                                           ?>';
            var userType = '<?php echo (isset(Yii::$app->user->identity->role) ? Yii::$app->user->identity->role : ''); ?>';
            var userId = '<?php echo (isset(Yii::$app->user->identity->_id) ? Yii::$app->user->identity->_id : ''); ?>';
            var image_url = '<?= \yii\helpers\BaseUrl::base() ?>/images/';
        </script>

        <!--<header>-->
        <div class="result-header">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-5 col-xs-6">

                        <div class="result-logo hide-on-mobile">
                            <a href="<?= $baseUrl ?>"><img src="<?= Yii::$app->getHomeUrl(); ?>images/logo.png" alt="" /></a>
                        </div>
                        <div class="display-on-mobile mobile-logo">
                            <a href="<?= $baseUrl ?>"><img src="<?= Yii::$app->getHomeUrl(); ?>images/logo3.png" alt="" /></a>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-7  col-xs-6">
                        <div class="result-sign-up clearfix">
                            <?php
                            if (Yii::$app->user->isGuest) {
                                ?>
                                <a href="<?= $baseUrl ?>/site/signup" class="border">Sign Up</a>
                                <a href="<?= $baseUrl ?>/site/login">Log In</a>
                            <?php } else {
                                ?>
                                <a class="active show_menu"><?= Yii::$app->user->identity->first_name ?></a>
                                <div class="account_dd" style="display:none;">
                                    <a href="<?= \yii\helpers\Url::to(['user/profile']) ?>"><span class="account_dd_ico2"></span>My Account</a>
                                    <a href="<?= $baseUrl ?>/site/logout" class="active show_menu">Logout</a>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--</header>-->

        <div class="container1">
            <div class="container">
                <div class="row" style="margin-left: 0px;">
                    <?= Alert::widget() ?>
                </div>
            </div>
            <?= $content ?>
        </div>

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
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4">
                        <div class="footer-logo">
                            <a href="<?= \yii\helpers\Url::to(['/']); ?>"><img src="<?= yii\helpers\BaseUrl::base() ?>/images/logo2.png" alt="" /></a>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-8">
                        <div class="footer-right-side">
                            <!--<a href="<?= yii\helpers\BaseUrl::base() . '/event/directory' ?>">Directory</a> &bull;-->     
                            <a href="#">Sitemap</a>  
                            <a href="<?= yii\helpers\BaseUrl::base() . '/site/terms' ?>">Terms</a>    
                            <a href="<?= yii\helpers\BaseUrl::base() . '/site/privacy' ?>">Privacy</a>
                            <span></span>
                            © Health Events Live.  All rights reserved.
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
