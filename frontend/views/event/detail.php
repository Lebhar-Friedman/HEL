<?php

use common\functions\GlobalFunctions;
use components\GlobalFunction;
use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\Map;
use dosamigos\google\maps\overlays\InfoWindow;
use dosamigos\google\maps\overlays\Marker;
use yii\helpers\BaseUrl;
use yii\helpers\Url;
use yii\web\JqueryAsset;
use function GuzzleHttp\json_encode;

$this->registerMetaTag(['property' => 'og:url', 'content' => Url::to(['event/detail', 'eid' => (string) $event['_id']])]);
$this->registerMetaTag(['property' => 'og:type', 'content' => 'article']);
$this->registerMetaTag(['property' => 'og:title', 'content' => $event['title']]);
$this->registerMetaTag(['property' => 'og:description', 'content' => $event['description']]);
$this->registerMetaTag(['property' => 'og:image', 'content' => GlobalFunctions::getCompanyLogo($company['company_number'])]);
$this->registerMetaTag(['property' => 'og:site_name', 'content' => 'Health Events Live']);

$this->registerMetaTag(['property' => 'twitter:card', 'content' => 'article']);
$this->registerMetaTag(['property' => 'twitter:title', 'content' => $event['title']]);
$this->registerMetaTag(['property' => 'twitter:description', 'content' => $event['description']]);
$this->registerMetaTag(['property' => 'twitter:image', 'content' => GlobalFunctions::getCompanyLogo($company['company_number'])]);


$this->registerCssFile('@web/css/results.css');
$this->registerCssFile('@web/css/chosen.min.css');
$this->registerJsFile('@web/js/chosen.jquery.min.js', ['depends' => [JqueryAsset::className()]]);


if ($coordinates = GlobalFunctions::getCookiesOfLngLat()) {
    $user_lng = $coordinates['longitude'];
    $user_lat = $coordinates['latitude'];
} else {
    $user_lng = '12';
    $user_lat = '12';
}
if (isset($_GET['zipcode'])) {
    $zipcode = $_GET['zipcode'];
    $lat_lng = GlobalFunction::getLongLatFromZip($zipcode);
} else {
    $zipcode = $event['locations'][0]['zip'];
    $lat_lng = GlobalFunction::getLongLatFromZip($zipcode);
}
if (isset($_GET['store']) && !empty($_GET['store']) && (!isset($_GET['zipcode']) || empty($_GET['zipcode']) )) {
    $zipcode = $event_location['zip'];
    $lat_lng = GlobalFunction::getLongLatFromZip($zipcode);
}
$number_of_categories_in_title = 0;
$categories_in_title = '';
foreach ($event['categories'] as $category) {
    $number_of_categories_in_title += 1;
    if ($number_of_categories_in_title > 2)
        break;
    $categories_in_title = $categories_in_title . $category . ', ';
}
$number_of_sub_categories_in_meta = 0;
$sub_categories_in_meta = '';
foreach ($event['sub_categories'] as $sub_category) {
    $number_of_sub_categories_in_meta += 1;
    if ($number_of_sub_categories_in_meta > 5)
        break;
    $sub_categories_in_meta = $sub_categories_in_meta . htmlentities($sub_category) . ", ";
}
$sub_categories_in_meta = rtrim(trim($sub_categories_in_meta), ',');
$this->title = $categories_in_title . $company['name'] . ', ' . $zipcode;
//$this->title = $sub_categories_in_meta;
$this->registerMetaTag(["name" => "description", "content" => "Free and low-cost health services for " . $sub_categories_in_meta . " at " . $company['name'] . ", " . $event_location['street'] . ", " . $event_location['city']]);
?>

<?php $img_url = BaseUrl::base() . '/images/'; ?>
<?php $this->registerJsFile('@web/js/site.js', ['depends' => [JqueryAsset::className()]]); ?>
<?php $this->registerJsFile('@web/js/events.js', ['depends' => [JqueryAsset::className()]]); ?>

<script type="application/ld+json">
    {
    "@context": "http://schema.org",
    "@type": "Event",
    "name": "<?= $event['title'] ?>",
    "startDate": "<?= GlobalFunction::getDate('Y-m-d', $event['date_start']) ?>T<?= date("H:i", strtotime($event['time_start'])) ?>:00",
    "location": {
    "@type": "Place",
    "name": "<?= $event_location['street'] ?>, <?= $event_location['city'] ?>", 
    "address": {
    "@type": "PostalAddress",
    "streetAddress": "<?= $event_location['street'] ?>",
    "addressLocality": "<?= $event_location['city'] ?>",
    "postalCode": "<?= $event_location['zip'] ?>",
    "addressRegion": "<?= $event_location['state'] ?>",
    "addressCountry": "US"
    }
    },
    "image": [
    "<?= GlobalFunctions::getCompanyLogo($event_location['company']) ?>"
    ],
    "description": "<?= $event['description'] ?>",
    "endDate": "<?= GlobalFunction::getDate('Y-m-d', $event['date_end']) ?>T<?= date("H:i", strtotime($event['time_end'])) ?>:00",
    "offers": {
    "@type": "Offer",
    "url": "<?= "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>",
    "price": "<?= empty($event['price']) ? '0' : $event['price']; ?>",
    "priceCurrency": "USD",
    "availability": "http://schema.org/InStock"
    }
    }
</script>
<!-- 1. Include style -->
<!--<link href="http://addtocalendar.com/atc/1.5/atc-style-blue.css" rel="stylesheet" type="text/css">-->
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
    #at_hover.atm-s a{
        color: #F59A38 !important;
    }
    #at_hover.atm-s a:hover{
        background: #D38735 !important;
        color : #fff;
    }
    .free-health-content h2 {
        font-weight: bold;
        color: #333333;
    }
    .multi-service2 h1 {
        color: #364D6A;
    }
    .multi-service2 h2 {
        color: #666;
        font-size: 20px;
    }
    .multi-service2 span {
        color: #999;
        font-weight: normal;
        font-size: 18px;
    }

</style>
<script type="text/javascript">(function () {
        if (window.addtocalendar)
            if (typeof window.addtocalendar.start == "function")
                return;
        if (window.ifaddtocalendar == undefined) {
            window.ifaddtocalendar = 1;
            var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
            s.type = 'text/javascript';
            s.charset = 'UTF-8';
            s.async = true;
            s.src = ('https:' == window.location.protocol ? 'https' : 'http') + '://addtocalendar.com/atc/1.5/atc.min.js';
            var h = d[g]('body')[0];
            h.appendChild(s);
        }
    })();
</script>
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
                <p><?= $event['time_start'] ?> - <?= $event['time_end'] ?> </p>
                <div class="save-share-btn clearfix" style="display:none">
                    <?php
                    if (isset(Yii::$app->user->identity->_id)) {
                        ?>
                        <a href="javascript:;" onclick="saveEvent('<?= $event['_id'] ?>', this,<?= $zipcode ?>, <?= $event_location['location_id'] ?>)"><img src="<?= $img_url ?>star-icon.png" alt="" /> SAVE</a>
                        <?php
                    } else {
                        ?>
                        <a href="<?= BaseUrl::base() ?>/site/save-event?flg=y&eid=<?= $event['_id'] ?>" ><img src="<?= $img_url ?>star-icon.png" alt="" /> SAVE</a>
                        <?php
                    }
                    ?>

                    <div class="addthis_inline_share_toolbox"></div>
                    <span class="addtocalendar ">
                        <a class="atcb-link">
                            <span class="glyphicon glyphicon-calendar"></span>
                            CALENDAR
                        </a>
                        <var class="atc_event">
                            <var class="atc_date_start"><?= GlobalFunction::getDate('Y-m-d', $event['date_start']) ?> <?= $event['time_start'] ?></var>
                            <var class="atc_date_end"><?= GlobalFunction::getDate('Y-m-d', $event['date_end']) ?> <?= $event['time_end'] ?></var>
                            <var class="atc_timezone">America/New_York</var>
                            <var class="atc_title"><?= $event['title'] ?></var>
                            <var class="atc_description"><?= $event['description'] ?></var>
                            <var class="atc_location"><?= $event_location['street'] ?>, <?= $event_location['city'] ?>, <?= $event_location['state'] ?>, <?= $event_location['zip'] ?></var>
                            <var class="atc_organizer"><?= $event_location['company'] ?></var>
                            <!--<var class="atc_organizer_email">luke@starwars.com</var>-->
                        </var>
                    </span>
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
                <h2><?= !empty($event['price']) ? 'Services offered for &dollar;' . $event['price'] . ':' : 'FREE Healthcare Services' ?></h2>
                <!--<h2>No appointment required!</h2>-->
                <div class="row">
                    <?php
                    foreach ($event['sub_categories'] as $sub_category):
                        ?>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">
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
                    <input type="hidden" value="<?= $event_location['store_number'] ?>" id="store_number">
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
                    <?php $user_lat = $event['locations'][0]['geometry']['coordinates'][0]; ?>
                    <?php $user_lng = $event['locations'][0]['geometry']['coordinates'][1]; ?>
                    <div class="map-content" >
                                <!--<a href="javascript:;" onclick='openModal(<?php echo json_encode($event); ?>)' class="view-all-btn" style="z-index: 99">View all event locations</a>-->
                        <?php
                        $coord = new LatLng(['lat' => intval($user_lat), 'lng' => intval($user_lng)]);
                        $poic_styles = '[{"featureType": "poi","elementType": "labels","stylers": [{ "visibility": "off" }]},{"featureType": "transit","elementType": "labels","stylers": [{ "visibility": "off" }]}]';
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
                            if (round(GlobalFunction::distanceBetweenPoints($lat_lng['lat'], $lat_lng['long'], $long_lat[1], $long_lat[0])) > 20) {
                                continue;
                            }
                            $coord = new LatLng(['lng' => $long_lat[0], 'lat' => $long_lat[1]]);
                            $marker = new Marker([
                                'position' => $coord,
                                'title' => $event['title'],
                                'animation' => 'google.maps.Animation.DROP',
                                'visible' => 'true',
                                'icon' => $img_url . 'custom-marker.png',
                            ]);
                            $content = "<a href='" . BaseUrl::base() . "/event/detail?eid=" . (string) $event['_id'] . "&store=" . $location['store_number'] . "&zipcode=" . $zipcode . "'>" . $location['street'] . ', ' . $location['city'] . ', ' . $location['state'] . ', ' . $location['zip'] . "</a>";
                            $marker->attachInfoWindow(
                                    new InfoWindow(['content' => $content])
                            );

                            $map->addOverlay($marker);
                        }
                        if (!empty($map->getMarkers())) {
                            $map->center = $map->getMarkersCenterCoordinates();
                            $map->zoom = $map->getMarkersFittingZoom();
                            echo $map->display();
                        }
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
                    <a href="<?= yii\helpers\Url::to(['healthcare-events/' . urlencode($companyEvent['categories'][0]) . '/' . urlencode(implode('-', $companyEvent['sub_categories'])), 'eid' => (string) $companyEvent['_id'], 'store' => $event_location['location_id'], 'zipcode' => $event_location['zip']]) ?>">
                        <div class="multi-service2">
                            <h1><?= (isset($companyEvent['sub_categories']) && sizeof($companyEvent['sub_categories']) === 1 ) ? $companyEvent['sub_categories'][0] . ' Screenings' : 'Multiple Services' ?></h1>
                            <h2><?= GlobalFunction::getEventDate($companyEvent['date_start'], $companyEvent['date_end']) ?></h2>
                            <span><?php
                                if (isset($companyEvent['price']) && $companyEvent['price'] !== '' && $companyEvent['price'] > 0) {
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
                        </div>
                    </a> 
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
        <!--        <div class="col-lg-offset-2 col-lg-3 col-md-4 col-sm-4">
                    <div class="cvs-text mobile-center">
                        <img src="<?= GlobalFunctions::getCompanyLogo($company['company_number']) ?>" alt="" />
                        <div class="find-out-text">
                        <img src="<?= $img_url ?>result-detail-img2.png" alt="" />
                            <a href="<?= Url::to(['/provider', 'id' => $company['company_number']]); ?>">Find out more <br class="hide-on-mobile" />about <?= $company['name'] ?> <br class="hide-on-mobile" /></a>
                        </div>
                    </div>
                </div>-->
    </div> 
</div>

<script type="text/javascript">
    var addthis_config = {
        ui_offset_top: 8,
        ui_offset_left: -9,
        services_compact: 'facebook,twitter,email',
        services_exclude: 'google_plusone_share,gmail,print,smiru,'
    }
    addthis_config.ui_email_note = '<?= htmlspecialchars($event['description']) ?>';
    addthis_config.ui_email_from = '<?= Yii::$app->user->isGuest ? '' : Yii::$app->user->identity->email ?>';
    var addthis_share = {
        email_vars: {
//            note: "Our pharmacies will be holding grocery",
        },
        url: "<?= Yii::$app->request->absoluteUrl ?>",
        title: "<?= $event['title'] ?>",
        description: "<?= $event['description'] ?>",
//        media: "<?= GlobalFunctions::getCompanyLogo($company['company_number']) ?>"
    }

</script>
