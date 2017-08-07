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
<?php if (empty($providerEvents)) { ?>
    <div class="container">
        <div class="row">
            <p class="text-center"><b>Record Not found</b></p>  
        </div>
    </div>
    <?php
} else {
    ?>
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-5">
                <div class="search-result-content">
                    <div class="search-nav">
                        <h1>Search <a href="#" class="nav-cros"><img src="<?= $baseUrl ?>/images/crose-btn.png" alt="" /></a></h1>
                        <div class="zip-code">
                            <span><b>Zip Code</b></span>
                            <div><input type="text" class="zip-textbox" value="94903" /></div>
                        </div>
                        <div class="zip-code">
                            <span><b>Keyword</b> (optional)</span>
                            <div class="optional">
                                <div class="full-shot">Flu shots <a href="#">X</a></div>
                            </div>
                        </div>
                        <div class="zip-code">
                            <span><b>Sort By</b></span>
                            <div>
                                <select  class="zip-textbox">
                                    <option>Closest</option>
                                </select>
                            </div>
                        </div>
                        <div><a href="#" class="go-btn">GO</a></div>
                        <h1>Filters</h1>
                        <div class="filter-box">
                            <input type="checkbox"  value="Diabetes Care" id="Diabetes Care"/>
                            <label class="oper" for="Diabetes Care"> Diabetes Care </label>
                        </div>
                        <div class="filter-box">
                            <input type="checkbox"  value="Diabetes Care" id="Diabetes Care1"/>
                            <label class="oper" for="Diabetes Care1"> Diabetes Care </label>
                        </div>
                        <div class="filter-box">
                            <input type="checkbox"  value="Diabetes Care" id="Diabetes Care2"/>
                            <label class="oper" for="Diabetes Care2"> Diabetes Care </label>
                        </div>
                        <div class="filter-box">
                            <input type="checkbox"  value="Diabetes Care" id="Diabetes Care3"/>
                            <label class="oper" for="Diabetes Care3"> Diabetes Care </label>
                        </div>
                        <div class="filter-box">
                            <input type="checkbox"  value="Diabetes Care" id="Diabetes Care4"/>
                            <label class="oper" for="Diabetes Care4"> Diabetes Care </label>
                        </div>
                        <div class="filter-box">
                            <input type="checkbox"  value="Diabetes Care" id="Diabetes Care5"/>
                            <label class="oper" for="Diabetes Care5"> Diabetes Care </label>
                        </div>
                        <div class="filter-box">
                            <input type="checkbox"  value="Diabetes Care" id="Diabetes Care6"/>
                            <label class="oper" for="Diabetes Care6"> Diabetes Care </label>
                        </div>
                        <div><a href="#" class="go-btn">GO</a></div>
                    </div>
                    <div class="add-box"><img src="<?= $baseUrl ?>/images/result-img7.png" alt="" /></div>

                </div>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-7">
                <div class="event-near event-margin-r">
                    <div class="row">
                        <div class="col-lg-8 col-md-8 col-sm-8">
                            <h1><a class="search-filter" href=""><img src="<?= $baseUrl ?>/images/filter-btn.png" alt="" /></a> Events <span>(103 results)</span> </h1>
                            <i>Diabetes Care, Flu Shots</i>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            <div class="event-img-box">
                                <img src="<?= $img_url ?>" alt="" />
                            </div>
                        </div>
                    </div>

                </div>
                <?php foreach ($providerEvents as $event) { ?>
                    <div class="event-multi-service">
                        <h1><?= $event->title ?></h1>
                        <h2>Jun 1 - 10</h2>
                        <span><?= !empty($event->price) ? $event->price : 'Free' ?></span>
                        <div class="clearfix">
                            <?php foreach ($event->sub_categories as $sc) { ?>
                                <div class="table-cust">
                                    <i><?= $sc ?></i>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="event-location-text">
                            <img src="<?= $baseUrl ?>/images/result-img1.png" alt="" /> 1.2 m
                        </div>
                    </div>
                <?php } ?>          

            </div>
        </div>
    </div>
<?php } ?>