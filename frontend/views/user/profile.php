<?php

use common\functions\GlobalFunctions;
use components\GlobalFunction;
use yii\helpers\BaseUrl;
use yii\web\JqueryAsset;

$this->title = 'profile';
$baseUrl = Yii::$app->request->baseUrl;
?>

<div class="container alert-cust-container">
    <div class="profile-alert-nav clearfix">
        <a href="#" class="active">Profile</a>
        <a href="#">Alerts</a>
    </div>
    <div class="profile-alert-container">
        <div class="cutomer-profile-content">
            <div class="row">
                <div class="col-lg-1 col-md-1 col-sm-1"></div>
                <div class="col-lg-11 col-md-11 col-sm-11">
                    <span><img src="<?= $baseUrl ?>/images/customer-img1.png" alt="" /><?= Yii::$app->user->identity->first_name . ' ' . Yii::$app->user->identity->first_name ?></span>
                    <span><img src="<?= $baseUrl ?>/images/customer-img2.png" alt="" /><?= Yii::$app->user->identity->email ?></span>
                </div>
            </div>
        </div>
        <div class="cutomer-profile-container">
            <div class="row">
                <div class="col-lg-1 col-md-1 col-sm-1"></div>
                <div class="col-lg-10 col-md-10 col-sm-10">
                    <div class="cutomer-profile-h">My Saved Events</div>
                    <?php foreach ($events as $event) { ?>
                        <a href="<?= BaseUrl::base() . '/event/detail?eid=' . (string) $event['_id'] ?>">
                            <div class="cutomer-profile-multi-service">
                                <h1><?= count($event->categories) > 0 ? 'Multiple Services' : $event->categories[0] ?></h1>
                                <h2><?= components\GlobalFunction::getEventDate($event['date_start'], $event['date_end']) ?></h2>
                                <span><?= !empty($event->price) ? '&dollar;' . $event->price : 'Free' ?></span>
                                <div class="clearfix">
                                    <?php foreach ($event['sub_categories'] as $sc) { ?>
                                        <div class="table-cust">
                                            <i><?= $sc ?></i>
                                        </div>
                                    <?php } ?>
                                </div>

                                <div class="location-text">
                                    <img src="<?= $companyLogo[$event->event_id] ?>" alt="" style="max-width: 88px;"/>
                                    <div class="text">
                                        <!--<img src="<?= $baseUrl ?>/images/result-img1.png" alt="" /> 1.2 m-->
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php } ?>
                    <!--                    <div class="pagging">
                                            <a href="#"><img src="<?= $baseUrl ?>/images/back-img.png" alt="" /><img src="<?= $baseUrl ?>/images/back-img.png" /></a>
                                            <a href="#"><img src="<?= $baseUrl ?>/images/back-img.png" alt="" /></a>
                                            <a href="#">1</a>
                                            <a href="#">2</a>
                                            <a href="#">3</a>
                                            <a href="#">4</a>
                                            <a href="#"><img src="<?= $baseUrl ?>/images/back-img1.png" alt="" /></a>
                                            <a href="#"><img src="<?= $baseUrl ?>/images/back-img1.png" /><img src="<?= $baseUrl ?>/images/back-img1.png" alt="" /></a>
                                        </div>-->
                </div>
            </div>
        </div>
    </div>
</div>

