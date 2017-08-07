<?php

use common\functions\GlobalFunctions;
use components\GlobalFunction;
use yii\helpers\BaseUrl;
?>
<?php $img_url = BaseUrl::base() . '/images/'; ?>
<div id="more_events">
    <?php if (isset($more_events) && sizeof($more_events) > 0) { ?>
        <?php foreach ($more_events as $event) { ?>
            <a href="<?= BaseUrl::base() . '/event/detail?eid=' . (string) $event['_id'] ?>">
                <div class="event-near">
                    <h1>More health events</h1>
                </div>
                <div class="multi-service">
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
            <?php } ?>
        <?php } ?>
    </a>
</div>