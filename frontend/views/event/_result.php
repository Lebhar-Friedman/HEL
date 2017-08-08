<?php

use common\functions\GlobalFunctions;
use components\GlobalFunction;
use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\Map;
use dosamigos\google\maps\overlays\InfoWindow;
use dosamigos\google\maps\overlays\Marker;
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
    a .marker_info{
        /*cursor: pointer !important;*/

    }
    .gm-style-mtc {
        display: none !important;
    }

</style>
<div id="overlay" >
    <div><img id="loader" src="<?= BaseUrl::base() . '/images/loader.gif' ?>"></div>
</div>
<?php $img_url = BaseUrl::base() . '/images/'; ?>
<?php Pjax::begin(['id' => 'result-view', 'timeout' => 30000, 'enablePushState' => false]); ?>

<?php
$sortBy = 'distance';
$filters = array();
if (isset($ret_sort)) {
    $ret_sort == 'Soonest' ? $sortBy = 'date' : $sortBy = 'distance';
}
if (isset($ret_filters)) {
    $filters = $ret_filters;
}
//if ($coordinates = GlobalFunctions::getCookiesOfLngLat()) {
//    $user_lng = $coordinates['longitude'];
//    $user_lat = $coordinates['latitude'];
//} else {
//    $user_lng = $longitude;
//    $user_lat = $latitude;
//}
$user_lng = $longitude;
$user_lat = $latitude;
$temp_events= array();
?>

<div class="col-lg-8 col-md-8 col-sm-7">
    <div class="event-near " id="event_near">
        <h1>Events near <?= $zip_code ?> <span>(by <?= $sortBy ?>)</span> 
            <a class="search-filter" href=""><img src="<?= $img_url ?>filter-btn.png" alt="" /></a></h1>
        <i> </i>
        <?php if (sizeof($filters) > 0) { ?>
            <select class="filters-multi-chosen-selected" multiple="multiple" style="width:100%;" name="filters[]">
                <?php foreach ($filters as $filter) { ?>
                    <option value="<?= $filter ?>" selected ><?= $filter ?></option>
                <?php } ?>
            </select>
        <?php } ?>
    </div>
    <?php foreach ($events as $event) { ?>
    <a href="<?= BaseUrl::base() . '/event/detail?eid='. (string)$event['_id']?>">
            <div class="multi-service" >
                <h1><?= (isset($event['sub_categories']) && sizeof($event['sub_categories']) === 1 ) ? $event['sub_categories'][0] . ' Screenings' : 'Multiple Services' ?></h1>
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
        </a>
    <?php $temp_events[]=['_id' => (string)$event['_id'],'locations' => $event['locations'], 'title'=>$event['title'] ]; ?>
    <?php } ?>
    <?php for($i=0; $i< sizeof($temp_events) ; $i++){
        $temp_events[$i]['_id'] = (string)$temp_events[$i]['_id'];
    } ?>
    <?php if (sizeof($events) > 0) { ?>
        <div class="map-content">
            <a href="javascript:;" onclick='openModal(<?php echo json_encode($temp_events,JSON_FORCE_OBJECT); ?>)' class="view-all-btn" style="z-index: 99">View all event locations</a>
            <?php
//            $coord = new LatLng(['lat' => 32.154377, 'lng' => 74.184227]);
            $coord = new LatLng(['lat' => intval($user_lat), 'lng' => intval($user_lng)]);
            $map = new Map([
                'center' => $coord,
                'zoom' => 8,
                'width' => '100%',
                'height' => '275',
                'scrollwheel' => false,
            ]);
            $map->setName('gmap');
            foreach ($events as $event) {
                foreach ($event['locations'] as $location) {
                    $long_lat = $location['geometry']['coordinates'];
                    $coord = new LatLng(['lng' => $long_lat[0], 'lat' => $long_lat[1]]);
                    $marker = new Marker([
                        'position' => $coord,
                        'title' => $event['title'],
                        'animation' => 'google.maps.Animation.DROP',
                        'visible' => 'true',
                        'icon' => $img_url . 'custom-marker.png',
                    ]);


//                $marker->setName('abc');   //to set Info window default open
//                $map->appendScript("google.maps.event.addListenerOnce(gmap, 'idle', function(){
//            google.maps.event.trigger(abc, 'click');});");

                    $map->addOverlay($marker);
                }
            }
            $map->center = $map->getMarkersCenterCoordinates();
            $map->zoom = $map->getMarkersFittingZoom() - 1;

            echo $map->display();
            ?>
        </div>
    <?php } ?>
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
    <?= $this->render('_more-events'); ?>
</div>

<?php Pjax::end() ?>