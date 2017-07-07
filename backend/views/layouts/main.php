<?php
/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
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
            var baseUrl = '<?php echo "http://" . $_SERVER["HTTP_HOST"] . Yii::$app->request->baseUrl . "/"; ?>';
            var userType = '<?php echo (isset(Yii::$app->user->identity->user_role) ? Yii::$app->user->identity->user_role : ''); ?>';
            var adminId = '<?php echo (isset(Yii::$app->user->identity->_id) ? Yii::$app->user->identity->_id : ''); ?>';
        </script>

        <header>
            <div class="container">
                <div class="row">
                    <div class="col-lg-2 col-md-5 col-sm-5">
                        <div class="logo"><a href="#"><img src="<?= $baseUrl ?>/images/logo.png" alt="" /></a></div>
                    </div>
                    <div class="col-lg-10 col-md-7 col-sm-7">
                        <div class="mobile-nav"><span class="glyphicon glyphicon-align-justify"></span></div>
                        <div class="clearfix">
                            <div class="header-nav clearfix">
                                <?php
                                if (Yii::$app->user->isGuest) {
                                    ?>
                                    <a href="<?= $baseUrl ?>/site/login">Log In</a>
                                <?php } else {
                                    ?>
                                    <a href="#">Upload</a>
                                    <a href="#">Companies</a>
                                    <a href="#">Locations</a>
                                    <a href="#">Events</a>
                                    <a href="#">Categories</a>
                                    <a href="#">Admins</a>
                                    <a href="<?= $baseUrl ?>/site/logout" class="active">Logout (<?= Yii::$app->user->identity->first_name ?>)</a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="container">
            <div class="row"><?= Alert::widget() ?></div>
            <?= $content ?>
        </div>

        <footer>
            <!--            <div class="social-icon">
                            <a href="#"><img src="images/social-icon.png" alt="" /></a>
                            <a href="#"><img src="images/social-icon2.png" alt="" /></a>
                            <a href="#"><img src="images/social-icon3.png" alt="" /></a>
                            <a href="#"><img src="images/social-icon4.png" alt="" /></a>
                        </div>-->
            Copyright &copy; <?= date('Y') ?> Health Events Live Plus
        </footer>

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
