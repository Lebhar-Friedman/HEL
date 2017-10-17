<?php

use common\functions\GlobalFunctions;
use components\GlobalFunction;
use yii\helpers\BaseUrl;
use yii\web\JqueryAsset;

$this->title = 'Health Events Live: Profile';
$baseUrl = Yii::$app->request->baseUrl;
?>

<div class="container alert-cust-container">
    <div class="profile-alert-nav clearfix">
        <a href="<?= \yii\helpers\Url::to(['user/profile']) ?>" class="active">Saved Events</a>
        <a href="<?= \yii\helpers\Url::to(['user/alerts']) ?>">Alerts</a>
    </div>
    <div class="profile-alert-container">
        <!--        <div class="cutomer-profile-content">
                    <div class="row">
                        <div class="col-lg-1 col-md-1 col-sm-1"></div>
                        <div class="col-lg-11 col-md-11 col-sm-11">
                            <span><img src="<?= $baseUrl ?>/images/customer-img1.png" alt="" /><?= Yii::$app->user->identity->first_name . ' ' . Yii::$app->user->identity->first_name ?></span>
                            <span><img src="<?= $baseUrl ?>/images/customer-img2.png" alt="" /><?= Yii::$app->user->identity->email ?></span>
                        </div>
                    </div>
                </div>-->
        <div class="cutomer-profile-container">
            <div class="row">
                <div class="col-lg-1 col-md-1 col-sm-1"></div>
                <div class="col-lg-10 col-md-10 col-sm-10">
                    <?php if (!empty($events)) { ?>
                        <div class="cutomer-profile-h">My Saved Events</div>
                        <?php
                        foreach ($events as $event) {
                            $eUrlParam = ['healthcare-events/' . GlobalFunction::removeSpecialCharacters(event['categories'][0]) . '/' . GlobalFunction::removeSpecialCharacters($event['sub_categories']), 'eid' => (string) $event['_id']];
                            if (!empty($event['store'])) {
                                $eUrlParam['store'] = $event['store'];
                            }
                            if (!empty($event['zip'])) {
                                $eUrlParam['zipcode'] = $event['zip'];
                            }
                            ?>
                            <a href="<?= yii\helpers\Url::to($eUrlParam) ?>">
                                <div class="cutomer-profile-multi-service">
                                    <h1><?= count($event['categories']) == 1 ? $event['categories'][0] . ' Screenings' : 'Multiple Services' ?></h1>
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
                                        <img src="<?= GlobalFunctions::getCompanyLogo($event['company']) ?>" alt="" style="max-width: 88px;"/>
                                        <div class="text">
                                            <!--<img src="<?= $baseUrl ?>/images/result-img1.png" alt="" /> 1.2 m-->
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <?php
                        }
                    } else {
                        ?>
                        <div class="cutomer-profile-h" style="border:0px">No saved events yet.</div>
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

