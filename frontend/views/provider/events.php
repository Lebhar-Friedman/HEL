<?php

use yii\helpers\BaseUrl;
use yii\web\JqueryAsset;

$this->registerCssFile('@web/css/results.css');
$this->registerCssFile('@web/css/chosen.min.css');
$this->registerJsFile('@web/js/chosen.jquery.min.js', ['depends' => [JqueryAsset::className()]]);
//$this->registerJsFile('@web/js/events.js', ['depends' => [JqueryAsset::className()]]);

$this->title = 'Provider Events';
$baseUrl = Yii::$app->request->baseUrl;

if ($company['logo'] === NULL || $company['logo'] === '' || !isset($company['logo'])) {

    $img_url = BaseUrl::base() . '/images/upload-logo.png';
} else {
    $img_url = IMG_URL . $company['logo'];
}
$zip_code = (Yii::$app->request->get('zipcode')) ? Yii::$app->request->get('zipcode') : (Yii::$app->request->post('zipcode') ? Yii::$app->request->post('zipcode') : '');
?>
<style>
    .chosen-container-multi .chosen-choices {
        min-height: 45px;
        display: block;
        border: 1px solid #dbdbdb;
        background: #FFF;
        padding-top: 7px !important;
        background-image: none !important;
    }
    .chosen-container{
        width: 100% !important;
    }
    .chosen-container-multi .chosen-choices li.search-field input[type=text]{
        text-align: center !important;
    }
</style>


<div class="container">
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-5">
            <div class="search-result-content">
                <div class="search-nav">
                    <form action="<?= \yii\helpers\Url::to(['provider/events', 'id' => $company->name]) ?>" method="post" id="events_search_form">
                        <input type="hidden" name="_csrf-frontend" value="<?= Yii::$app->request->getCsrfToken() ?>" />
                        <h1>Search <a href="javascript:;" onclick="closeNav()" class="nav-cros"><img src="<?= $baseUrl ?>/images/crose-btn.png" alt="" /></a></h1>
                        <div class="zip-code">
                            <span><b>Zip Code</b></span>
                            <div><input type="text" class="zip-textbox" value="<?= $zip_code ?>" name="zipcode" /></div>
                        </div>
                        <div class="zip-code">
                            <span><b>Keyword</b> (optional)</span>
                            <div class="optional1">
                                <select class="html-multi-chosen-select" multiple="multiple" style="width:100%;" name="keywords[]">
                                    <?php foreach (\common\functions\GlobalFunctions::getKeywords() as $keyword) { ?>
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
                                    <option <?= isset($ret_sort) && $ret_sort == 'Closest' ? 'selected' : '' ?> >Closest</option>
                                    <option <?= isset($ret_sort) && $ret_sort == 'Soonest' ? 'selected' : '' ?> >Soonest</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <input type="submit" class="btn go-btn" value="Go">
                        </div>
                        <h1>Filters</h1>
                        <?php $i = 0; ?>
                        <?php foreach (\common\functions\GlobalFunctions::getCategories() as $filter) { ?>
                            <?php $i++; ?>
                            <div class="filter-box">
                                <?php if (isset($ret_filters) && !empty($ret_filters)) { ?>
                                    <input name="filters[]" type="checkbox" <?= in_array($filter['text'], $ret_filters) ? 'checked' : '' ?> value="<?= $filter['text'] ?>" id="<?= $filter['text'] ?>"> 
                                        <!--<input type='checkbox' name='filters[]' value='valuable' class="pinmusic" checked/>-->
                                    <label class="oper" for="<?= $filter['text'] ?>"> <?= $filter['text'] ?> </label>
                                <?php } else { ?>
                                    <input name="filters[]" type="checkbox" value="<?= $filter['text'] ?>" id="<?= $filter['text'] ?>">
                                    <label class="oper" for="<?= $filter['text'] ?>"> <?= $filter['text'] ?> </label>
                                <?php } ?>
                            </div>
                        <?php } ?>
                        <div>
                            <input type="submit" class="btn go-btn" value="Go">
                        </div>
                    </form>
                </div>
                <div class="add-box"><img src="<?= $baseUrl ?>/images/result-img7.png" alt="" /></div>

            </div>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-7">

            <div class="event-near event-margin-r">
                <div class="row">
                    <div class="col-lg-8 col-md-8 col-sm-8">
                        <h1><a class="search-filter" href=""><img src="<?= $baseUrl ?>/images/filter-btn.png" alt="" /></a> Events <span>(<?= count($events) ?> results)</span> </h1>
                        <i><?= Yii::$app->request->post('filters') ? implode(Yii::$app->request->post('filters'), ', ') : '' ?></i>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4">
                        <div class="event-img-box">
                            <img src="<?= $img_url ?>" alt="" />
                        </div>
                    </div>
                </div>

            </div>
            <?php if (empty($events)) { ?>
                <div class="row">
                    <p class="text-center"><b>Record Not found</b></p>  
                </div>
                <?php
            } else {
                ?>
                <?php foreach ($events as $event) { ?>
                    <a href="<?= BaseUrl::base() . '/event/detail?eid=' . (string) $event['_id'] ?>">
                        <div class="event-multi-service">
                            <h1><?= count($event->categories) > 0 ? 'Multiple Services' : $event->categories[0] ?></h1>
                            <h2><?= components\GlobalFunction::getEventDate($event['date_start'], $event['date_end']) ?></h2>
                            <span><?= !empty($event->price) ? '&dollar;' . $event->price : 'Free' ?></span>
                            <div class="clearfix">
                                <?php foreach ($event['sub_categories'] as $sc) { ?>
                                    <div class="table-cust">
                                        <i><?= $sc ?></i>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="event-location-text">
                                <img src="<?= $baseUrl ?>/images/result-img1.png" alt="" /> <?= isset($event['distance']) ? round($event['distance'], 1) . ' m' : '' ?>
                            </div>
                        </div>
                    </a>
                    <?php
                }
            }
            ?>          

        </div>
    </div>
</div>