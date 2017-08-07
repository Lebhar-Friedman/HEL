<?php

use yii\helpers\BaseUrl;

$this->registerCssFile('@web/css/results.css');
$baseUrl = Yii::$app->request->baseUrl;

if ($company['logo'] === NULL || $company['logo'] === '' || !isset($company['logo'])) {

    $img_url = BaseUrl::base() . '/images/upload-logo.png';
} else {
    $img_url = IMG_URL . $company['logo'];
}
?>
<?php if (empty($company)) { ?>
    <p class="text-center"><b>Record Not found</b></p>    
    <?php
} else {
    ?>
    <div class="container">
        <div class="mobile-profile-event-text">
            <img src="<?= $baseUrl ?>/images/profile-img5.png" alt="" />
            <div class="text" onclick="window.location.href='<?= \yii\helpers\Url::to(['provider/events', 'id' => $company->name]); ?>'">Events Participated
                <span><?= $total_events ?></span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-1 col-md-2 col-sm-2">
                <a href="javascript:window.history.go(-1)" class="back-btn">&lt; Back</a>
            </div>
            <div class="col-lg-7 col-md-7 col-sm-7">
                <div class="profile-main-container">
                    <div class="profile-h">Provider Profile</div>
                    <div class="contact-box clearfix">
                        <span class="contact-name">Contact Name</span>
                        <span class="contact-name2"><?= $company->contact_name ?></span>
                        <img src="<?= $baseUrl ?>/images/profile-img1.png" alt="" />
                    </div>
                    <div class="contact-box clearfix">
                        <span class="contact-name">Phone</span>
                        <span class="contact-name2"><?= $company->phone ?></span>
                        <img src="<?= $baseUrl ?>/images/profile-img2.png" alt="" />
                    </div>
                    <div class="contact-box clearfix">
                        <span class="contact-name">Email</span>
                        <span class="contact-name2"><?= $company->email ?></span>
                        <img src="<?= $baseUrl ?>/images/profile-img3.png" alt="" class="email-icon" />
                    </div>
                    <div class="contact-box clearfix">
                        <span class="contact-name">Address</span>
                        <span class="contact-name2"><?= $company->street ?><i><?= $company->city ?>, <?= $company->state ?> <?= $company->zip ?></i></span>
                        <img src="<?= $baseUrl ?>/images/profile-img4.png" alt="" class="loc-icon" />
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3">
                <div class="profile-right-side">
                    <div class="contnet">
                        <div><img src="<?= $img_url ?>" alt="" /></div>
                        <div class="margin-t"><img src="<?= $baseUrl ?>/images/result-detail-img2.png" alt="" /></div>
                        <div class="profile-event-text" onclick="window.location.href='<?= \yii\helpers\Url::to(['provider/events', 'id' => $company->name]); ?>'">
                            Events
                            <div class="clearfix">Participated<span><?= $total_events ?></span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>