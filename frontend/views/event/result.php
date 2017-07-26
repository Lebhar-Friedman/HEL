<?php

use common\functions\GlobalFunctions;
use yii\helpers\BaseUrl;
use yii\web\JqueryAsset;

$this->registerCssFile('@web/css/results.css');
$this->registerCssFile('@web/css/chosen.min.css');
$this->registerJsFile('@web/js/chosen.jquery.min.js', ['depends' => [JqueryAsset::className()]]);
?>
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

<!--<body class="reult-body">-->
<div class="container">
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-5">
            <div class="search-result-content">
                <div class="search-nav">
                    <form action="<?= BaseUrl::base() ?>/event" method="post">

                        <input type="hidden" name="_csrf-frontend" value="<?= Yii::$app->request->getCsrfToken() ?>" />
                        <h1>Search <a href="#" class="nav-cros"><img src="<?= $img_url ?>crose-btn.png" alt="" /></a></h1>
                        <div class="zip-code">
                            <span><b>Zip Code</b></span>
                            <div><input type="text" class="zip-textbox" value="<?= $zip_code ?>" name="zipcode" /></div>
                        </div>
                        <div class="zip-code">
                            <span><b>Keyword</b> (optional)</span>
                            <div class="optional1">
                                <select class="html-multi-chosen-select" multiple="multiple" style="width:100%;" name="keywords[]">
                                    <?php foreach (GlobalFunctions::getKeywords() as $keyword) { ?>
                                        <?php if (isset($ret_keywords) && !empty($ret_keywords)) { ?>
                                            <option value="<?= $keyword['text'] ?>" <?= in_array($keyword['text'], $ret_keywords) ? 'selected' : '' ?> ><?= $keyword['text'] ?></option>
                                        <?php } else { ?>
                                            <option value="<?= $keyword['text'] ?>"><?= $keyword['text'] ?></option>
                                        <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="zip-code">
                            <span><b>Sort By</b></span>
                            <div>
                                <select  class="zip-textbox" name="sortBy">
                                    <option>Closest</option>
                                    <option>Soonest</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <input type="submit" class="btn go-btn" value="Go">
                        </div>
                        <h1>Filters</h1>
                        <?php foreach (GlobalFunctions::getCategories() as $filter) { ?>
                            <div class="filter-box">
                                <?php if (isset($ret_filters) && !empty($ret_filters)) { ?>
                                    <label><input name="filters[]" type="checkbox" <?= in_array($filter['text'], $ret_filters) ? 'checked' : '' ?> value="<?= $filter['text'] ?>"> <?= $filter['text'] ?></label>
                                <?php } else { ?>
                                    <label><input name="filters[]" type="checkbox" value="<?= $filter['text'] ?>"> <?= $filter['text'] ?></label>
                                <?php } ?>
                            </div>
                        <?php } ?>
                        <div>
                            <input type="submit" class="btn go-btn" value="Go">
                        </div>
                    </form>
                </div>
                <div class="add-box"><img src="<?= $img_url ?>result-img7.png" alt="" /></div>

            </div>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-7">
            <div class="event-near">
                <h1>Events near <?= $zip_code ?> <span>(by distance)</span> 
                    <a class="search-filter" href=""><img src="<?= $img_url ?>filter-btn.png" alt="" /></a></h1>
                <i>Heart Health, Flu Shots</i>
            </div>
            <?php foreach ($events as $event) { ?>
                <div class="multi-service">
                    <h1><?= sizeof($event['categories']) === 1 ? $event['categories'][0] : 'Multiple Services' ?></h1>
                    <h2>Jun 1 - 10</h2>
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
            <div class="map-content">
                <img src="<?= $img_url ?>result-img3.png" alt="" />
                <a href="#" class="view-all-btn">View all event locations</a>
            </div>
            <div class="email-content">
                <div class="row">
                    <div class="col-lg-6 col-md-8">
                        <h1>Alert me when more health events like this get added!</h1>
                        <div class="email-conatiner">
                            <input type="text" class="email-textbox" placeholder="Email" />
                            <input type="submit" value="Go" class="submitbtn" />
                        </div>
                    </div>
                </div>

            </div>
            <div class="event-near">
                <h1>More health events</h1>
            </div>
            <div class="multi-service">
                <h1>Multiple Services</h1>
                <h2>Jun 1 - 10</h2>
                <span>FREE</span>
                <div class="clearfix">
                    <div class="table-cust">
                        <i>Flu Shots</i>
                        <i>Meningitis</i>
                    </div>
                    <div class="table-cust">
                        <i>Hepititis A</i>
                        <i>MMR</i>
                    </div>
                    <div class="table-cust">
                        <i>Hepititis B</i>
                        <i>Pnumonia</i>
                    </div>
                    <div class="table-cust">
                        <i>HPV</i>
                        <i>Shingles</i>
                    </div>

                </div>
                <div class="location-text">
                    <img src="images/result-img4.png" alt="" />
                    <div class="text">10 locations</div>
                    <img src="images/result-img1.png" alt="" /> 1.2 m
                </div>
            </div>
            <div class="multi-service">
                <h1>Vaccination Screenings</h1>
                <h2>Jun 1 - 10</h2>
                <span>$25 - $50</span>
                <div class="clearfix">
                    <div class="table-cust">
                        <i>Flu Shots</i>
                    </div>
                    <div class="table-cust">
                        <i>Hepititis A</i>
                    </div>
                    <div class="table-cust">
                        <i>Hepititis B</i>
                    </div>

                </div>
                <div class="location-text">
                    <img src="images/result-img5.png" alt="" />
                    <div class="text">2 locations</div>
                    <img src="images/result-img1.png" alt="" /> 1.7 m
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-1 col-md-1"></div>
        <div class="col-lg-10 col-md-10">
            <div class="add-box2">
                <img src="images/result-img6.png" alt=""  />
            </div>
        </div>
    </div>
</div>

