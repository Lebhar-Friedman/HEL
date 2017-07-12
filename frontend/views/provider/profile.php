<?php

use yii\helpers\BaseUrl;

if ($company['logo'] === NULL || $company['logo'] === '' || !isset($company['logo'])) {
    $img_url = BaseUrl::base() . '/images/upload-logo.png';
} else {
    $img_url = BaseUrl::base() . '/uploads/'.$company['logo'];
}
?>

<div class="col-lg-12">
    <div class="profile-content">
        <div class="profile-form">
            <h2>Provider Profile</h2>
            <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-2">
                    <div class="upload-img"><img src="<?= $img_url ?>" alt="" /></div> </div>
                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-4">
                    <div class="profile-name">Company Name:</div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
                    <div class="profile-name-text"><?= $company['name'] ?></div>
                </div>
                <div class="col-lg-5 col-md-5 col-sm-4">
                    <div class="participated">Events Participated: 115</div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-2">
                </div>
                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
                    <div class="profile-name">Contact Name:</div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="profile-name-text"><?= $company['contact_name'] ?></div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-2">
                </div>
                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
                    <div class="profile-name">Phone:</div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="profile-name-text"><?= $company['phone'] ?></div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-2">
                </div>
                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
                    <div class="profile-name">Email:</div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="profile-name-text"><?= $company['email'] ?></div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-2">
                </div>
                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
                    <div class="profile-name">Street Address:</div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="profile-name-text">City Hall-555 west 66th Avenue</div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-2">
                </div>
                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
                    <div class="profile-name">City:</div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">
                    <div class="profile-name-text">San Mateo</div>
                </div>
                <div class="col-lg-5 col-md-4 col-sm-4">

                </div>
            </div>
            <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-2">
                </div>
                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
                    <div class="profile-name">State:</div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="profile-name-text">CA</div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-2">
                </div>
                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
                    <div class="profile-name">Zip:</div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="profile-name-text">94</div>
                </div>
            </div>
        </div>
    </div>
</div>