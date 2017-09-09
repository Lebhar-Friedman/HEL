<?php
/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="site-error">

    <?php
    if (!YII_DEBUG && strstr($message, 'internal server error')) {
        $this->title = "Health Events Live";
        ?>    
        <div class="alert text-center"><h2>Oops! Something went wrong.</h2></div>
    <?php } else { ?>
        <h1><?= Html::encode($this->title) ?></h1>

        <div class="alert alert-danger">
            <?= nl2br(Html::encode($message)) ?>
        </div>
    <?php } ?>

</div>
