<?php

use common\functions\GlobalFunctions;
use yii\helpers\BaseUrl;
use yii\web\JqueryAsset;
use components\GlobalFunction;
use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\Map;
use dosamigos\google\maps\overlays\InfoWindow;
use dosamigos\google\maps\overlays\Marker;

$this->registerCssFile('@web/css/results.css');
$this->registerCssFile('@web/css/chosen.min.css');
$this->registerJsFile('@web/js/chosen.jquery.min.js', ['depends' => [JqueryAsset::className()]]);
$this->title = $event['title'];
if ($coordinates = GlobalFunctions::getCookiesOfLngLat()) {
    $user_lng = $coordinates['longitude'];
    $user_lat = $coordinates['latitude'];
} else {
    $user_lng = '12';
    $user_lat = '12';
}
?>
<?php $img_url = BaseUrl::base() . '/images/'; ?>
<?php $this->registerJsFile('@web/js/site.js', ['depends' => [JqueryAsset::className()]]); ?>
<?php $this->registerJsFile('@web/js/events.js', ['depends' => [JqueryAsset::className()]]); ?>

<style>
    .chosen-choices{
        min-height: 45px;
        display: block;
        border: 1px solid #dbdbdb;
        background: #FFF;
        padding-top: 7px !important;
        background-image: none !important;
    }
    .chosen-container-multi .chosen-choices li.search-field input[type=text]{
        /*color: #000 !important;*/
    }
    .add-new-alert{
        cursor: pointer;
    }
</style>
<?php $img_url = BaseUrl::base() . '/images/'; ?>

<div class="container">
    <div class="row">
        <!--            <div class="col-lg-1 col-md-2 col-sm-2">
                        <a href="javascript:location.replace(document.referrer);" class="back-btn">&lt; Back</a>
                    </div>
                        <div class="col-lg-1"></div>
                    div class="col-lg-7 col-md-8 col-sm-8 col-xs-7">-->
        <div class="col-lg-9 col-md-8 col-sm-8 col-xs-7">
            <div class="senior-day-content">
                <h1><?= $event['title'] ?></h1>
                <h2><?= GlobalFunction::getEventDate($event['date_start'], $event['date_end']) ?></h2>
                <?= $event['time_start'] ?> - <?= $event['time_end'] ?> 
                <div class="save-share-btn clearfix" style="display:none">
                    <?php
                    if (isset(Yii::$app->user->identity->_id)) {
                        ?>
                        <a href="javascript:;" onclick="saveEvent('<?= $event['_id'] ?>', this)"><img src="<?= $img_url ?>star-icon.png" alt="" /> SAVE</a>
                        <?php
                    } else {
                        ?>
                        <a href="<?= BaseUrl::base() ?>/site/save-event?flg=y&eid=<?= $event['_id'] ?>" ><img src="<?= $img_url ?>star-icon.png" alt="" /> SAVE</a>
                        <?php
                    }
                    ?>

                    <div class="addthis_inline_share_toolbox"></div>
                </div>

                <div class="clearfix">
                    <?php
                    foreach ($event['categories'] as $category):
                        ?>
                        <div class="heart-text"><?= $category ?></div>
                        <?php
                    endforeach;
                    ?>
                </div>
            </div>
            <div class="free-health-content">
                <h1><?= !empty($event['price']) ? '&dollar;' . $event['price'] : 'FREE Healthcare Services' ?></h1>
                <!--<h2>No appointment required!</h2>-->
                <div class="row">
                    <?php
                    foreach ($event['sub_categories'] as $sub_category):
                        ?>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <i><?= $sub_category ?></i>
                        </div>
                        <?php
                    endforeach;
                    ?>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-5">
            <div class="cvs-text">
                <img src="<?= GlobalFunctions::getCompanyLogo($event_location['company']) ?>" alt="" />
                <div class="margin-t">
                    <?= $event_location['street'] ?><br />
                    <?= $event_location['city'] ?>, <?= $event_location['state'] ?>, <?= $event_location['zip'] ?><br />
                    <?= $event_location['phone'] ?><br />
                    <input type="hidden" value="<?= $event_location['zip'] ?>" id="c_zipcode">
                    <input type="hidden" value="<?= $event_location['street'] ?>" id="c_street">
                    <input type="hidden" value="<?= $event_location['city'] ?>" id="c_city">
                    <input type="hidden" value="<?= $event_location['state'] ?>" id="c_state">
                </div>
                <?php
                if (sizeof($event['locations']) > 1) {
                    echo "<span>More locations nearby</span>";
                }
                ?>
                <a href="#map">Show map</a>            </div>
        </div>
    </div>
    <div class="row">
        <!--        	<div class="col-lg-1"></div>
                        <div class="col-lg-10">-->
        <!--        <div class="col-lg-12">
                    <div class="free-health-content">
                        <h1><?= !empty($event['price']) ? '&dollar;' . $event['price'] : 'FREE Healthcare Services' ?></h1>
                        <h2>No appointment required!</h2>
                        <div class="row">
        <?php
        foreach ($event['sub_categories'] as $sub_category):
            ?>
                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                        <i><?= $sub_category ?></i>
                                                    </div>
            <?php
        endforeach;
        ?>
                        </div>
                    </div>
                </div>-->
    </div>
    <!--<div class="event-detail-img show-on-mobile"><img src="<?= $img_url ?>result-img7.png" alt="" /></div>-->
    <div class="row">
        <!--        	<div class="col-lg-1"></div>
                    <div class="col-lg-6 col-md-8 col-sm-8">-->
        <div class="col-lg-8 col-md-9 col-sm-8">
            <div class="event-detail-text">
                <h1>Health event details</h1>
                <?= $event['description'] ?>
            </div>
        </div>
        <!--        <div class="col-lg-4 col-md-4 col-sm-4">
                    <div class="event-detail-img hide-on-mobile"><img src="<?= $img_url ?>result-img7.png" alt="" /></div>
                </div>-->
    </div>
    <div class="row">
        <!--        	<div class="col-lg-1"></div>
                    <div class="col-lg-10">-->
        <div class="col-lg-12">
            <div class="map2-content" id="map">
                <h1><?php
                    if (sizeof($event['locations']) > 1) {
                        echo "Locations";
                    } else {
                        echo "Location";
                    }
                    ?> for this event</h1>
<!--                    <img src="<?= $img_url ?>map-img.png" alt="" />-->
                <?php if (sizeof($event) > 0 && sizeof($event['locations']) > 0) { ?>
                    <?php $user_lat = $event['locations'][0]['geometry']['coordinates'][0] ?>
                    <?php $user_lng = $event['locations'][0]['geometry']['coordinates'][1] ?>
                    <div class="map-content" >
                                <!--<a href="javascript:;" onclick='openModal(<?php echo json_encode($event); ?>)' class="view-all-btn" style="z-index: 99">View all event locations</a>-->
                        <?php
                        $coord = new LatLng(['lat' => intval($user_lat), 'lng' => intval($user_lng)]);
                        $poic_styles='[{"featureType": "poi","elementType": "labels","stylers": [{ "visibility": "off" }]},{"featureType": "transit","elementType": "labels","stylers": [{ "visibility": "off" }]}]';
//                        $gmapStyler = '[{"stylers":[{"saturation":-100},{"gamma":1}]},{"elementType":"labels.text.stroke","stylers":[{"visibility":"off"}]},{"featureType":"poi.business","elementType":"labels.text","stylers":[{"visibility":"off"}]},{"featureType":"poi.business","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"poi.place_of_worship","elementType":"labels.text","stylers":[{"visibility":"off"}]},{"featureType":"poi.place_of_worship","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"geometry","stylers":[{"visibility":"simplified"}]},{"featureType":"water","stylers":[{"visibility":"on"},{"saturation":50},{"gamma":0},{"hue":"#50a5d1"}]},{"featureType":"administrative.neighborhood","elementType":"labels.text.fill","stylers":[{"color":"#333333"}]},{"featureType":"road.local","elementType":"labels.text","stylers":[{"weight":0.5},{"color":"#333333"}]},{"featureType":"transit.station","elementType":"labels.icon","stylers":[{"gamma":1},{"saturation":50}]}]';
                        $gmapStyler = '[{"stylers":[{"saturation":-100},{"gamma":1}]},{"elementType":"labels.text.stroke","stylers":[{"visibility":"off"}]},{"featureType":"poi.business","elementType":"labels.text","stylers":[{"visibility":"off"}]},{"featureType":"poi.business","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"poi.place_of_worship","elementType":"labels.text","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"geometry","stylers":[{"visibility":"simplified"}]},{"featureType":"water","stylers":[{"visibility":"on"},{"saturation":50},{"gamma":0},{"hue":"#50a5d1"}]},{"featureType":"administrative.neighborhood","elementType":"labels.text.fill","stylers":[{"color":"#333333"}]},{"featureType":"road.local","elementType":"labels.text","stylers":[{"weight":0.5},{"color":"#333333"}]},{"featureType":"transit.station","elementType":"labels.icon","stylers":[{"gamma":1},{"saturation":50}]}]';
                        $map = new Map([
                            'center' => $coord,
                            'zoom' => 8,
                            'width' => '100%',
                            'height' => '275',
                            'scrollwheel' => false,
                            'styles' => $poic_styles,
                        ]);
                        $map->setName('gmap');

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
                            $content = $location['street'] . ', ' . $location['city'] . ', ' . $location['state'] . ', ' . $location['zip'];
                            $marker->attachInfoWindow(
                                    new InfoWindow(['content' => $content])
                            );

//                $marker->setName('abc');   //to set Info window default open
//                $map->appendScript("google.maps.event.addListenerOnce(gmap, 'idle', function(){
//            google.maps.event.trigger(abc, 'click');});");

                            $map->addOverlay($marker);
                        }

                        $map->center = $map->getMarkersCenterCoordinates();

                        $map->zoom = $map->getMarkersFittingZoom() + 2;

                        echo $map->display();
                        ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="row">
        <!--        <div class="col-lg-1"></div>-->
        <div class="col-lg-7 col-md-8 col-sm-8">
            <?php
            if (!empty($companyEvents)) {
                ?>
                <div class="other-event">Other events at this location</div>


                <?php
                foreach ($companyEvents as $companyEvent):
                    ?> 
                    <div class="multi-service2">
                        <a href="<?= BaseUrl::base() . '/event/detail?eid=' . (string) $companyEvent['_id'] ?>">
                            <h1><?= (isset($event['sub_categories']) && sizeof($event['sub_categories']) === 1 ) ? $event['sub_categories'][0] . ' Screenings' : 'Multiple Services' ?></h1>
                            <h2><?= GlobalFunction::getEventDate($event['date_start'], $event['date_end']) ?></h2>
                            <span><?php
                                if (isset($companyEvent['price']) && $companyEvent['price'] !== '') {
                                    echo "$" . $companyEvent['price'];
                                } else {
                                    echo "Free";
                                }
                                ?></span>
                            <div class="clearfix">
                                <?php
                                foreach ($companyEvent['sub_categories'] as $companySubCategories):
                                    $i = 1;
                                    if ($i <= 6) {
                                        ?>
                                        <div class="table-cust">
                                            <i><?= $companySubCategories ?></i>
                                        </div>
                                        <?php
                                    } else {
                                        ?>
                                        <div class="table-cust show-on-mobile">
                                            <i><?= $companySubCategories ?></i>
                                        </div>
                                        <?php
                                    }
                                endforeach;
                                ?>
                            </div>
                        </a> 
                    </div>
                    <?php
                endforeach;
            }
            ?>
            <?php if (!$alert_added) { ?>
                <div class="email-content" id="add_alert">
                    <div class="row">
                        <div class="col-lg-11 col-md-12">
                            <h1>Alert me when more health events added at this location!</h1>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-8 col-md-10">
                            <?php if (Yii::$app->user->isGuest) { ?>
                                <div class="email-conatiner">   
                                    <input type="text" class="email-textbox" placeholder="Email" id="email"/>
                                    <a type="submit" onclick="alertZipCodeSession()" value="Go" class="submitbtn" />Alert Me</a>
                                </div>
                            <?php } else { ?>
                                <a type="btn" onclick="alertZipCode()" value="Go" class="add-new-alert" />Alert Me</a>
                            <?php } ?><input type="hidden" id="event_id" name="event_id" value="<?= (string) $event['_id'] ?>">
                        </div>
                    </div>

                </div>
            <?php } ?>
        </div>
        <div class="col-lg-offset-2 col-lg-3 col-md-4 col-sm-4">
            <div class="cvs-text mobile-center">
                <img src="<?= GlobalFunctions::getCompanyLogo($company['company_number']) ?>" alt="" />
                <div class="find-out-text">
                <!--<img src="<?= $img_url ?>result-detail-img2.png" alt="" />-->
                    <a href="<?= \yii\helpers\Url::to(['/provider', 'id' => $company['company_number']]); ?>">Find out more <br class="hide-on-mobile" />about <?= $company['name'] ?> <br class="hide-on-mobile" /></a>
                </div>
            </div>
        </div>
    </div> 
</div>

<script type="text/javascript">
    var addthis_config = {
        ui_offset_top: 8,
        ui_offset_left: -9,
        services_compact: 'facebook,twitter,email',
        services_exclude: 'google_plusone_share,gmail,print,smiru,'
    }
    addthis_config.ui_email_note = '<?= $event['description'] ?>';
    addthis_config.ui_email_from = '<?= Yii::$app->user->isGuest ? '' : Yii::$app->user->identity->email ?>';
    var addthis_share = {
        email_vars: {
            note: "Our pharmacies will be holding grocery",
        },
        url: "<?= Yii::$app->request->absoluteUrl ?>",
        title: "<?= $event['title'] ?>",
        description: "<?= $event['description'] ?>",
        media: "<?= GlobalFunctions::getCompanyLogo($company['company_number']) ?>"
    }
</script>
