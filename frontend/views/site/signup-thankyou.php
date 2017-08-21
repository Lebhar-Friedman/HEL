<?php
/* @var $this View */
/* @var $form ActiveForm */
/* @var $model SignupForm */

use frontend\models\SignupForm;
use yii\helpers\Html;
use yii\web\View;

$this->title = 'Health Events Live: Welcome';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    body{
        background:#eaeaea !important;
    }
    .result-header{
        background: #FFF !important;
        margin-bottom: 15px !important;
        border-bottom: 1px solid #2aaae2;
    }
    a{
        color: #337ab7 !important;
        font-size: inherit;
        text-decoration: initial !important;
    }
    a:hover, a:focus {
        color: #23527c;
        text-decoration: underline !important;
    }
</style>
<div class="row">
    <div class="col-lg-1">
    </div>
    <div class="col-lg-10" style="min-height: 428px;justify-content: center;display: flex;align-items: center;">
        <div class="middle-content text-center">
            <h2>Thank you for signing up. Click <a href="<?= yii\helpers\Url::to(['/']) ?>">here</a> to start searching for health events.</h2>

        </div>
    </div>
</div>

