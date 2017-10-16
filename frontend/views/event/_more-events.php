<?php

use common\functions\GlobalFunctions;
use components\GlobalFunction;
use yii\helpers\BaseUrl;
?>
<style>
    h1{
        color: #333;
    }
</style>
<?php $img_url = BaseUrl::base() . '/images/'; ?>
<div id="more_events">
    <?php if (isset($more_events) && sizeof($more_events) > 0) { ?>
        <div class="event-near">
            <h1>More health events</h1>
        </div>
        <?php foreach ($more_events as $event) { ?>
            <?php $nearest_location = GlobalFunction::nearestLocation($lng_lat['lat'], $lng_lat['long'], $event['locations']); ?>
            <a href="<?= yii\helpers\Url::to(['healthcare-events/' . urlencode($event['categories'][0]) . '/' . urlencode(implode('-', $event['sub_categories'])), 'eid' => (string) $event['_id'], 'store' => $nearest_store_number]) ?>">
                <div class="multi-service">
                    <h1><?= (isset($event['sub_categories']) && sizeof($event['sub_categories']) === 1 ) ? $event['sub_categories'][0] . ' Screenings' : 'Multiple Services' ?></h1>
                    <h2><?= GlobalFunction::getEventDate($event['date_start'], $event['date_end']) ?></h2>
                    <span><?= empty($event['price']) ? 'Free' : '$' . $event['price'] ?></span>
                    <!--<span><?= $event['score'] ?></span>-->
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
        <?php } ?>
    <?php } ?>
</div>