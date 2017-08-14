<?php

use common\functions\GlobalFunctions;
use components\GlobalFunction;
use yii\helpers\BaseUrl;
?>
<?php
$this->title = 'Health Events Live: Directory';
$img_url = BaseUrl::base() . 'images/'
?>
<div class="container">
    <div class="row">
        <?php foreach ($events as $event) { ?>
            <a href="<?= BaseUrl::base() . '/event/detail?id=' . (string) $event['_id'] ?>">
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
                        <img src="<?= GlobalFunctions::getCompanyLogo($event['company']) ?>" height="50px" class="img-responsive" alt="" />
                        <div class="text"><?= sizeof($event['locations']) ?> locations</div>
                    </div>
                </div>
            </a>
        <?php } ?>
    </div>
</div>