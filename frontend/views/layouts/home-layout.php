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
    </head>
    <body>
        <?php $this->beginBody() ?>
        <script type="text/javascript">
            var baseUrl = '<?php echo Url::base(true) . "/"; //"http://" . $_SERVER["HTTP_HOST"] . Yii::$app->request->baseUrl . "/";                                          ?>';
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
                        <a href="<?= $baseUrl ?>/site/logout" class="active">Logout (<?= Yii::$app->user->identity->first_name ?>)</a>
                    <?php } ?>
                </div>
                <div class="logo-container">
                    <a href="<?= $baseUrl ?>"><img src="<?= Yii::$app->getHomeUrl(); ?>images/home-logo.png" alt="" /></a></a>
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

        <footer>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-md-7 col-sm-6"></div>
                    <div class="col-lg-4 col-md-5 col-sm-5">
                        <div class="footer-nav">
                            <span><a href="#">Dairectory</a></span>
                            <span><a href="#">Sitemap</a></span>
                            <span><a href="#">Terms</a></span>
                            <span><a href="#">Privacy</a></span>
                        </div>
                        © Health Events Live.  All rights reserved.
                    </div>
                </div>
            </div>
        </footer>

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
