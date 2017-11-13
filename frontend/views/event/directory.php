<?php

use yii\helpers\BaseUrl;
?>
<?php
$this->title = 'Health Events Live: Directory';
$img_url = BaseUrl::base() . 'images/';
$web_link = "http://$_SERVER[HTTP_HOST]";
?>
<div class="container">
    <div class="row">
        <?php foreach ($cities as $city) { ?>
            <a href="<?= BaseUrl::base() . '/free-healthcare-events/' . $city['slug'] ?>">
                <div class="multi-service">
                    <?= $city['name'] ?>
                </div>
            </a>
        <?php } ?>
    </div>
</div>