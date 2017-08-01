<?php

use common\functions\GlobalFunctions;
use components\GlobalFunction;
use yii\helpers\BaseUrl;
use yii\widgets\Pjax;
?>

<style>
#overlay {
    position: fixed;
    display: none;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0,0,0,0.5);
    z-index: 2;
    /*cursor: pointer;*/
}
#loader{
    position: absolute;
    top: 50%;
    left: 50%;
    font-size: 50px;
    color: white;
    transform: translate(-50%,-50%);
    -ms-transform: translate(-50%,-50%);
}
</style>
<div id="overlay" >
    <div><img id="loader" src="<?= BaseUrl::base() . '/images/loader.gif' ?>"></div>
</div>
<?php $img_url = BaseUrl::base() . '/images/'; ?>
<?php Pjax::begin(['id' => 'result-view', 'timeout' => 30000, 'enablePushState' => false]); ?>

<?php
$sortBy='distance';
if (isset($ret_sort)) {
    $ret_sort == 'Soonest' ? $sortBy= 'date' : $sortBy= 'distance';
}
if(isset($ret_filters)){
    $filters=$ret_filters;
}else{
//    $filters='as';
}
?>

<div class="col-lg-8 col-md-8 col-sm-7">
    <div class="event-near " id="event_near">
        <h1>Events near <?= $zip_code ?> <span>(by <?= $sortBy ?>)</span> 
            <a class="search-filter" href=""><img src="<?= $img_url ?>filter-btn.png" alt="" /></a></h1>
            <i> Heart Health, Flu Shots</i>
    </div>
    <?php foreach ($events as $event) { ?>
        <div class="multi-service">
            <h1><?= sizeof($event['categories']) === 1 ? $event['categories'][0] : 'Multiple Services' ?></h1>
            <h2><?= GlobalFunction::getEventDate($event['date_start'], $event['date_end']) ?></h2>
            <span><?= empty($event['price']) ? 'Free' : '$' . $event['price'] ?></span>
            <div class="clearfix">
                <?php foreach ($event['sub_categories'] as $sub_category) { ?>
                    <div class="table-cust">
                        <i><?= $sub_category ?></i>
                    </div>
                <?php } ?>
            </div>
            <div class="location-text">
                <img src="<?= GlobalFunctions::getCompanyLogo($event['company']) ?>" height="50px" alt="" />
                <div class="text"><?= sizeof($event['locations']) ?> locations</div>
                <img src="<?= $img_url ?>map-marker.png" alt="" /> <?= isset($event['distance']) ? round($event['distance'], 1) . ' m' : '' ?> 
            </div>
        </div>
    <?php } ?>
    <div class="map-content">
        <img src="<?= $img_url ?>result-img3.png" alt="" />
        <a href="#" class="view-all-btn">View all event locations</a>
    </div>
    <div class="email-content">
        <div class="row">
            <div class="col-lg-6 col-md-8">
                <h1>Alert me when more health events like this get added!</h1>
                <div class="email-conatiner">
                    <input type="text" class="email-textbox" placeholder="Email" />
                    <input type="submit" value="Go" class="submitbtn" />
                </div>
            </div>
        </div>

    </div>
    <div class="event-near">
        <h1>More health events</h1>
    </div>
    <div class="multi-service">
        <h1>Multiple Services</h1>
        <h2>Jun 1 - 10</h2>
        <span>FREE</span>
        <div class="clearfix">
            <div class="table-cust">
                <i>Flu Shots</i>
                <i>Meningitis</i>
            </div>
            <div class="table-cust">
                <i>Hepititis A</i>
                <i>MMR</i>
            </div>
            <div class="table-cust">
                <i>Hepititis B</i>
                <i>Pnumonia</i>
            </div>
            <div class="table-cust">
                <i>HPV</i>
                <i>Shingles</i>
            </div>

        </div>
        <div class="location-text">
            <img src="images/result-img4.png" alt="" />
            <div class="text">10 locations</div>
            <img src="images/result-img1.png" alt="" /> 1.2 m
        </div>
    </div>
    <div class="multi-service">
        <h1>Vaccination Screenings</h1>
        <h2>Jun 1 - 10</h2>
        <span>$25 - $50</span>
        <div class="clearfix">
            <div class="table-cust">
                <i>Flu Shots</i>
            </div>
            <div class="table-cust">
                <i>Hepititis A</i>
            </div>
            <div class="table-cust">
                <i>Hepititis B</i>
            </div>

        </div>
        <div class="location-text">
            <img src="images/result-img5.png" alt="" />
            <div class="text">2 locations</div>
            <img src="images/result-img1.png" alt="" /> 1.7 m
        </div>
    </div>
</div>

<?php Pjax::end() ?>