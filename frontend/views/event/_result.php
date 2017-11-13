<?php

use common\functions\GlobalFunctions;
use components\GlobalFunction;
use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\Map;
use dosamigos\google\maps\overlays\InfoWindow;
use dosamigos\google\maps\overlays\Marker;
use yii\helpers\BaseUrl;
use yii\widgets\Pjax;
use function GuzzleHttp\json_encode;

$this->title = 'Free Health Services near ZIP Code ' . $zip_code . ' | Health Events Live';
$this->registerMetaTag(['name' => 'description', 'content' => 'Find free and low-cost health services at trusted stores near your ZIP Code ' . $zip_code]);
?>
<?php
$this->registerCss(
        "#overlay {
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
    .multi-service h1 {
        color: #364D6A;
    }
    .multi-service h2 {
        color: #666;
        font-size: 20px;
    }
    .multi-service span {
        color: #999;
        font-weight: normal;
        font-size: 18px;
    }");
?>
<div id="overlay" >
    <div><img id="loader" src="<?= BaseUrl::base() . '/images/loader.gif' ?>" alt=""></div>
</div>
<?php $img_url = BaseUrl::base() . '/images/'; ?>
<?php Pjax::begin(['id' => 'result-view', 'timeout' => 30000, 'enablePushState' => TRUE]); ?>

<?php
$sortBy = 'distance';
$filters = array();
if (isset($ret_sort)) {
    $ret_sort == 'soonest' ? $sortBy = 'date' : $sortBy = 'distance';
}
if (isset($ret_filters)) {
    $filters = $ret_filters;
}
$user_lng = $longitude;
$user_lat = $latitude;
$temp_events = array();
$nearest = 9999999999;
$nearest_store_number = 0;
$all_locations_near = array();
$events_with_nearest_locations = array();
?>

<div class="col-lg-8 col-md-8 col-sm-7">
    <div class="event-near " id="event_near" onclick="showNav()">
        <a class="search-filter" href="javascript:;" onclick="showNav()"><img src="<?= $img_url ?>filter-btn.png" alt="" /></a>
        <h1>Events near <?= $zip_code ?> <br class="show_on_mobile"><span>(by <?= $sortBy ?>)</span> </h1> 
        <?php //if (sizeof($filters) > 0) {    ?>
            <!--<select class="filters-multi-chosen-selected" multiple="multiple" style="width:100%;" name="filters[]">-->
        <?php //foreach ($filters as $filter) {    ?>
                    <!--<option value="<?/= $filter ?>" selected ><?/= $filter ?></option>-->
        <?php //}    ?>
        <!--</select>-->
        <?php // }    ?>
    </div>
    <?php foreach ($events as $event) { ?>
        <?php
        foreach ($event['locations'] as $location) {
            $distance = round(GlobalFunction::distanceBetweenPoints($user_lat, $user_lng, $location['geometry']['coordinates'][1], $location['geometry']['coordinates'][0]), 1);
            if ($distance == round($event['distance'], 1)) {
                $nearest = $distance;
                $nearest_store_number = $location['location_id'];
            }
        }if ($nearest_store_number == 0) {
            $nearest_store_number = '';
        }
        ?>
        <?php $locations_near = GlobalFunction::locationsInRadius($user_lat, $user_lng, $event['locations'], 20); ?>
        <?php $category_url = isset($event['categories'][0]) ? GlobalFunction::removeSpecialCharacters($event['categories'][0]) . '/' : ''; ?>
        <a href="<?= yii\helpers\Url::to(['healthcare-events/' . $category_url . GlobalFunction::removeSpecialCharacters($event['sub_categories']), 'eid' => (string) $event['_id'], 'store' => $nearest_store_number, 'zipcode' => $zip_code]) ?>">
            <div class="multi-service" >
                <h1><?= (isset($event['categories']) && sizeof($event['categories']) === 1 ) ? str_replace("/", "/ ", $event['categories'][0]) . ' Screenings' : 'Multiple Services' ?></h1>
                <h2><?= GlobalFunction::getEventDate($event['date_start'], $event['date_end']) ?></h2>

                <div class="location-text">
                    <img src="<?= GlobalFunctions::getCompanyLogo($event['company']) ?>" height="50" alt="" />
                    <div class="text"><?= sizeof($locations_near) ?> <?= sizeof($locations_near) > 1 ? "Locations" : "Location" ?></div>
                    <img src="<?= $img_url ?>map-marker.png" alt="" /> <?= isset($event['distance']) ? round($event['distance'], 1) . ' m' : '' ?> 
                </div>
                <span style="padding-right: 0px !important;">Services offered for <?= empty($event['price']) ? 'Free' : '$' . $event['price'] ?>:</span>
                <div class="clearfix row">
                    <?php foreach ($event['sub_categories'] as $sub_category) { ?>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <i><?= $sub_category ?></i>
                        </div>
                    <?php } ?>
                </div>
            </div>

        </a>


        <?php
        $event['locations'] = $locations_near;
        $events_with_nearest_locations[] = $event;
        ?>
        <?php $temp_events[] = ['_id' => (string) $event['_id'], 'locations' => $event['locations'], 'title' => $event['title']]; ?>
    <?php } ?>
    <?php
    for ($i = 0; $i < sizeof($temp_events); $i++) {
        $temp_events[$i]['_id'] = (string) $temp_events[$i]['_id'];
    }
    ?>
    <?php if (sizeof($events_with_nearest_locations) > 0) { ?>
        <div class="map-content" >
            <!--<a href="javascript:;" onclick='openModal(<?php echo json_encode($temp_events, JSON_FORCE_OBJECT); ?>)' class="view-all-btn" style="z-index: 99">View all event locations</a>-->
            <?php
            $coord = new LatLng(['lat' => intval($user_lat), 'lng' => intval($user_lng)]);
            $poic_styles = '[{"featureType": "poi","elementType": "labels","stylers": [{ "visibility": "off" }]},{"featureType": "transit","elementType": "labels","stylers": [{ "visibility": "off" }]}]';
//            $coord = new LatLng(['lat' => intval($events[0]['locations'][0]['geometry']['coordinates'][1]), 'lng' => intval($events[0]['locations'][0]['geometry']['coordinates'][0])]);
            $map = new Map([
                'center' => $coord,
                'zoom' => 18,
//                'maxZoom' => 16,
                'width' => '100%',
                'height' => '275',
                'scrollwheel' => false,
                'styles' => $poic_styles,
            ]);
            $map->setName('gmap');
            foreach ($events_with_nearest_locations as $event) {
                $category_url = isset($event['categories'][0]) ? GlobalFunction::removeSpecialCharacters($event['categories'][0]) . '/' : '';
                foreach ($event['locations'] as $location) {
                    $long_lat = $location['geometry']['coordinates'];
                    $coord = new LatLng(['lng' => $long_lat[0], 'lat' => $long_lat[1]]);
//                    echo "<pre>";
//                    print_r($coord);
                    $marker = new Marker([
                        'position' => $coord,
                        'title' => $event['title'],
                        'animation' => 'google.maps.Animation.DROP',
                        'visible' => 'true',
                        'icon' => $img_url . 'custom-marker.png',
                    ]);

                    $content = "<a class='marker-info' href='" . yii\helpers\Url::to(['healthcare-events/' . $category_url . GlobalFunction::removeSpecialCharacters($event['sub_categories']), 'eid' => (string) $event['_id'], 'store' => $location['location_id'], 'zipcode' => $zip_code]) . "'>" . $event['title'] . "</a>";
                    $marker->attachInfoWindow(
                            new InfoWindow(['content' => $content])
                    );
//                $marker->setName('abc');   //to set Info window default open
//                $map->appendScript("google.maps.event.addListenerOnce(gmap, 'idle', function(){
//            google.maps.event.trigger(abc, 'click');});");

                    $map->addOverlay($marker);
                }
            }
            $map->center = $map->getMarkersCenterCoordinates();
            $map->zoom = $map->getMarkersFittingZoom() + 1;

//            $map_event = new Event(["trigger" => "click", "js" => "openModal(" . json_encode($temp_events, JSON_FORCE_OBJECT) . ")"]);
//            $map->addEvent($map_event);
            echo $map->display();
            ?>
        </div>
    <?php } ?>
    <?php if (sizeof($events) < 1) { ?>
        <div class="text-center email-content padding-top-50" style="padding-top:50px">
            <h1 >There are no nearby matching health events. Sign up below to be alerted when we add some!</h1>
        </div>
    <?php } ?>
    <div class="email-content">
        <div class="row" id="add_alert">
            <?php if (!$alert_added) { ?>
                <div class="col-lg-6 col-md-8">
                    <h1>Alert me when more health events like this get added!</h1>
                    <?php if (Yii::$app->user->isGuest) { ?>
                        <div class="email-conatiner">
                            <input type="text" class="email-textbox" placeholder="Email" name="email" id="email"/>
                            <!--<input type="submit" value="Go" class="submitbtn" />-->
                            <!--<a href="<?= BaseUrl::base() ?>/user/add-alerts"  class="submitbtn">Go</a>-->
                            <a href="javascript:;" onclick="addAlertSession()" class="submitbtn">Alert Me</a>
                        </div>
                    <?php } else { ?>
                        <a href="javascript:;" onclick="add_new_alert()" class="add-new-alert" id="add_alert1i">Alert Me</a>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
    <?= $this->render('_more-events'); ?>
</div>

<?php Pjax::end() ?>