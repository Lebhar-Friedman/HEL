<?php

use yii\helpers\BaseUrl;

if ($company['logo'] === NULL || $company['logo'] === '' || !isset($company['logo'])) {
    
    $img_url = BaseUrl::base() . '/images/upload-logo.png';
} else {
    $img_url = IMG_URL . $company['logo'];
    
}
?>
<?php if(! is_array($company)){ ?>
<p class="text-center"><b>Record Not found</b></p>    
<?php 
}else{
?>
<div class="col-lg-12">
    <div class="profile-content">
        <div class="profile-form">
            <h2>Provider Profile</h2>


            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-3">
                    <div class="upload-img"><img src="<?= $img_url ?>" alt="" /></div> </div>
                <div class="col-lg-3 col-md-3 col-sm-5 col-xs-4">
                    <div class="profile-name">Company Name:</div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-4">
                    <div class="profile-name-text"><?= $company['name'] ?></div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <div class="participated">Events Participated: <?= $total_events ?></div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-3">
                </div>
                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
                    <div class="profile-name">Contact Name:</div>
                </div>
                <div class="col-lg-5 col-md-5 col-sm-5">
                    <div class="profile-name-text"><?= $company['contact_name'] ?></div>
                </div>

            </div>
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-3">
                </div>
                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
                    <div class="profile-name">Phone:</div>
                </div>
                <div class="col-lg-5 col-md-5 col-sm-5">
                    <div class="profile-name-text"><?= $company['phone'] ?></div>
                </div>

            </div>
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-3">
                </div>
                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
                    <div class="profile-name">Email:</div>
                </div>
                <div class="col-lg-5 col-md-5 col-sm-5">
                    <div class="profile-name-text"><?= $company['email'] ?></div>
                </div>

            </div>
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-3">
                </div>
                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
                    <div class="profile-name">Street Address:</div>
                </div>
                <div class="col-lg-5 col-md-5 col-sm-5">
                    <div class="profile-name-text"><?= $company['street'] ?></div>
                </div>

            </div>

            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-3">
                </div>
                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
                    <div class="profile-name">City:</div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">
                    <div class="profile-name-text"><?= $company['city'] ?></div>
                </div>
                <div class="col-lg-5 col-md-5 col-sm-5">

                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-3">
                </div>
                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
                    <div class="profile-name">State:</div>
                </div>
                <div class="col-lg-5 col-md-5 col-sm-5">
                    <div class="profile-name-text"><?= $company['state'] ?></div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-3">
                </div>
                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
                    <div class="profile-name">Zip:</div>
                </div>
                <div class="col-lg-5 col-md-5 col-sm-5">
                    <div class="profile-name-text"><?= $company['zip'] ?></div>
                </div>

            </div>
        </div>
    </div>
</div>
<?php } ?>