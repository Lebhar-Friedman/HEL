<?php

use yii\helpers\BaseUrl;

$this->registerCssFile('@web/css/results.css');

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
            <img src="images/profile-img5.png" alt="" />
            <div class="text">Events Participated<span>115</span></div>
        </div>
        <div class="row">
            <div class="col-lg-1 col-md-2 col-sm-2">
                <a href="#" class="back-btn">&lt; Back</a>
            </div>
            <div class="col-lg-7 col-md-7 col-sm-7">
                <div class="profile-main-container">
                    <div class="profile-h">Provider Profile</div>
                    <div class="contact-box clearfix">
                        <span class="contact-name">Contact Name</span>
                        <span class="contact-name2">Erin Suomala</span>
                        <img src="images/profile-img1.png" alt="" />
                    </div>
                    <div class="contact-box clearfix">
                        <span class="contact-name">Phone</span>
                        <span class="contact-name2">(650) 386-9429</span>
                        <img src="images/profile-img2.png" alt="" />
                    </div>
                    <div class="contact-box clearfix">
                        <span class="contact-name">Email</span>
                        <span class="contact-name2">info@cvs.com</span>
                        <img src="images/profile-img3.png" alt="" class="email-icon" />
                    </div>
                    <div class="contact-box clearfix">
                        <span class="contact-name">Address</span>
                        <span class="contact-name2">5100 Clayton Road<i>Concord, CA 5100</i></span>
                        <img src="images/profile-img4.png" alt="" class="loc-icon" />
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3">
                <div class="profile-right-side">
                    <div class="contnet">
                        <div><img src="images/result-detail-img1.png" alt="" /></div>
                        <div class="margin-t"><img src="images/result-detail-img2.png" alt="" /></div>
                        <div class="profile-event-text">
                            Events
                            <div class="clearfix">Participated<span>120</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>