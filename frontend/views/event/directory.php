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
        <?php foreach ($cities as $citiy) { ?>
            <a href="<?= BaseUrl::base() . '/free-healthcare-events/' . $citiy ?>">
                <div class="multi-service">
                    <?= $web_link . BaseUrl::base() . '/free-healthcare-events/' . $citiy ?>
                </div>
            </a>
        <?php } ?>
    </div>
</div>