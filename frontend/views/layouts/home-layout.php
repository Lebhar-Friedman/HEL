<?php
/* @var $this View */
/* @var $content string */

//AppAsset::register($this);


use app\assets\HomeAsset;
use common\widgets\Alert;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

HomeAsset::register($this);
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
    </head>
    <body>
        <?php $this->beginBody() ?>
        <?php if (ENV === "live") { ?>
            <!-- Google Tag Manager (noscript) -->
            <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-P2WNXC"
                              height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
            <!-- End Google Tag Manager (noscript) -->
        <?php } ?>
        <script type="text/javascript">
            var baseUrl = '<?php echo Url::base(true) . "/"; //"http://" . $_SERVER["HTTP_HOST"] . Yii::$app->request->baseUrl . "/";                                                          ?>';
            var userType = '<?php echo (isset(Yii::$app->user->identity->role) ? Yii::$app->user->identity->role : ''); ?>';
            var userId = '<?php echo (isset(Yii::$app->user->identity->_id) ? Yii::$app->user->identity->_id : ''); ?>';
        </script>

        <header id="homelayout-header">
            <div class="container">
                <div class="signUp-btns clearfix">
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
                            <a href="<?= $baseUrl ?>/site/logout" class="active show_menu">Log out</a>
                        </div>
                    <?php } ?>
                </div>
                <div class="logo-container">
                    <a href="<?= $baseUrl ?>"><img src="<?= Yii::$app->getHomeUrl(); ?>images/home-logo.png" alt="" class="img-responsive"/></a>
                    <div class="logo-text">
                        Find free and low cost health <br />services at trusted stores near you
                    </div>
                    <div class="search-content">
                        <input type="text" class="search-txtbx" placeholder="Enter your zip code"/>
                        <a href="#" class="search-btn"></a>
                    </div>
                </div>
            </div>
        </header>

        <!--<div class="container">-->
        <?= Alert::widget() ?>
        <?= $content ?>
        <!--</div>-->

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
                            <!--<a href="#">Sitemap</a>-->     
                            <a href="<?= yii\helpers\BaseUrl::base() . '/site/contact' ?>">Contact</a> &nbsp;
                            <a href="<?= yii\helpers\BaseUrl::base() . '/site/terms-privacy' ?>">Terms & Privacy</a>
                            <!--&bull;-->
                            &nbsp;
                            <!--<a href="<?= yii\helpers\BaseUrl::base() . '/event/directory' ?>">Directory</a>--> 
                            <a href="<?= yii\helpers\BaseUrl::base() . '/sitemap.xml' ?>" target="_blank">Sitemap</a> 
                            <span></span>
                            © Health Events Live.  All rights reserved.
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
