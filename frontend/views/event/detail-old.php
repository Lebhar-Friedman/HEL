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
    $user_lng = $longitude;
    $user_lat = $latitude;
}
?>
<?php $img_url = BaseUrl::base() . '/images/'; ?>
<?php $this->registerJsFile('@web/js/site.js', ['depends' => [JqueryAsset::className()]]); ?>

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
</style>
<?php $img_url = BaseUrl::base() . '/images/'; ?>
<?php if($error !== ''){
?>
<p class="text-center"><b><?=$error?></b></p>   
<?php
}else{
?>
<div class="container">
    	<div class="row">
<!--            <div class="col-lg-1 col-md-2 col-sm-2">
                <a href="javascript:location.replace(document.referrer);" class="back-btn">&lt; Back</a>
            </div>
        	<div class="col-lg-1"></div>
            div class="col-lg-7 col-md-8 col-sm-8 col-xs-7">-->
            <div class="col-lg-9 col-md-10 col-sm-8 col-xs-7">
            	<div class="senior-day-content">
                	<h1><?=$event['title']?></h1>
                        <h2><?= GlobalFunction::getEventDate($event['date_start'], $event['date_end']) ?></h2>
                    <?=$event['time_start']?> - <?=$event['time_end']?> 
                    <div class="save-share-btn clearfix">
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
            </div>
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-5">
                <div class="cvs-text">
                    <img src="<?=GlobalFunctions::getCompanyLogo($company['name'])?>" alt="" />
                	<div class="margin-t">
                        <?=$company['street']?><br />
                        <?=$company['state']?>, <?=$company['zip']?><br />
                        <?=$company['phone']?><br />
                    </div>
                    <?php 
                    
                    if(sizeof($event['locations'])> 1){ echo "<span>More locations nearby</span>";}?>
                    <a href="#map">Show map</a>
                </div>
            </div>
        </div>
        <div class="row">
<!--        	<div class="col-lg-1"></div>
                <div class="col-lg-10">-->
            <div class="col-lg-12">
            	<div class="free-health-content">
                	<h1>FREE Healthcare Services</h1>
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
        </div>
        <div class="event-detail-img show-on-mobile"><img src="<?= $img_url ?>result-img7.png" alt="" /></div>
        <div class="row">
<!--        	<div class="col-lg-1"></div>
            <div class="col-lg-6 col-md-8 col-sm-8">-->
            <div class="col-lg-8 col-md-9 col-sm-8">
            	<div class="event-detail-text">
                	<h1>Health event details</h1>
                	<?=$event['description']?>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4">
            	<div class="event-detail-img hide-on-mobile"><img src="<?= $img_url ?>result-img7.png" alt="" /></div>
            </div>
      	</div>
        <div class="row">
<!--        	<div class="col-lg-1"></div>
            <div class="col-lg-10">-->
            <div class="col-lg-12">
            	<div class="map2-content">
                	<h1><?php if(sizeof($event['locations'])> 1){ echo "Locations";}else{echo "Location";}?> for this event</h1>
<!--                    <img src="<?= $img_url ?>map-img.png" alt="" />-->
                    <?php if (sizeof($event) > 0) { ?>
    <div class="map-content" id="map">
            <!--<a href="javascript:;" onclick='openModal(<?php echo json_encode($event); ?>)' class="view-all-btn" style="z-index: 99">View all event locations</a>-->
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
            $map->zoom = $map->getMarkersFittingZoom() - 1;

            echo $map->display();
            ?>
        </div>
    <?php } ?>
                </div>
            </div>
        </div>
        <div class="row">
<!--        	<div class="col-lg-1"></div>
          	<div class="col-lg-7 col-md-8 col-sm-8">-->

    <div class="row">
        <div class="col-lg-1"></div>
        <div class="col-lg-7 col-md-8 col-sm-8">
            <?php
            if (!empty($companyEvents)) {
                ?>
                <div class="other-event">Other events here</div>

                <div class="multi-service2">
                    <?php
                    foreach ($companyEvents as $companyEvent):
                        ?>
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
                        <?php
                    endforeach;
                    ?>
                </div>
                <?php
                }
                ?>
                <div class="email-content">
                	
                	<div class="row">
                    	<div class="col-lg-11 col-md-12">
                        	<h1>Alert me when more health events added at this location!</h1>
                            <div class="show-on-mobile">Alert me when healthcare events are added at CVS Pharmacy, 503 Wonder Lane</div>
                        </div>
                    </div>
                    <div class="row">
                    	<div class="col-lg-8 col-md-10">
                        	
                            <div class="email-conatiner">
                            	<input type="text" class="email-textbox" placeholder="Email" />
                                <input type="submit" value="Go" class="submitbtn" />
                            </div>
                        </div>
                    </div>
                	
                </div>
            </div>
            <div class="col-lg-offset-2 col-lg-3 col-md-4 col-sm-4">
            	<div class="cvs-text mobile-center">
                	<img src="<?=GlobalFunctions::getCompanyLogo($company['name'])?>" alt="" />
                	<div class="find-out-text">
                    	<!--<img src="<?= $img_url ?>result-detail-img2.png" alt="" />-->
                            <a href="<?= \yii\helpers\Url::to(['/provider', 'id' => $company['name']]); ?>">Find out more <br class="hide-on-mobile" />about <?=$company['name']?> <br class="hide-on-mobile" /></a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8 col-md-10">

                        <div class="email-conatiner">
                            <input type="text" class="email-textbox" placeholder="Email" />
                            <input type="submit" value="Go" class="submitbtn" />
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-4">
            <div class="cvs-text mobile-center">
                <img src="<?= GlobalFunctions::getCompanyLogo($company['name']) ?>" alt="" />
                <div class="find-out-text">
                <!--<img src="<?= $img_url ?>result-detail-img2.png" alt="" />-->
                    <a href="<?= \yii\helpers\Url::to(['/provider', 'id' => $company['name']]); ?>"><h2>Find out more <br class="hide-on-mobile" />about CVS <br class="hide-on-mobile" />Pharmacies</h2></a>
                </div>
            </div>
        </div>
    </div> 
</div>

<script type="text/javascript">
var addthis_share = {
   url: "<?= Yii::$app->request->absoluteUrl?>",
   title: "<?=$event['title']?>",
   description: "<?=$event['description']?>",
   media: "<?=Yii::$app->urlManager->hostInfo.GlobalFunctions::getCompanyLogo($company['name'])?>"
}
</script>
<?php
}
?>
