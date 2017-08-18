<?php

use common\functions\GlobalFunctions;
use components\GlobalFunction;
use yii\helpers\BaseUrl;
use yii\web\JqueryAsset;

$this->registerJsFile('@web/js/chosen.jquery.min.js', ['depends' => [JqueryAsset::className()]]);
$this->registerJsFile('@web/js/events.js', ['depends' => [JqueryAsset::className()]]);

$this->registerCssFile('@web/css/results.css', ['depends' => frontend\assets\AppAsset::className()]);
$this->registerCssFile('@web/css/chosen.min.css', ['depends' => frontend\assets\AppAsset::className()]);


$this->title = 'Health Events Live: Search Events';
?>
<style>
    .chosen-choices{
        min-height: 45px;
        display: block;
        border: 1px solid #dbdbdb;
        background: #FFF;
        padding-top: 3px !important;
        padding-bottom: 3px !important;
        background-image: none !important;
    }
    .chosen-container-multi .chosen-choices li.search-field input[type=text]{
        /*color: #000 !important;*/
    }
    .chosen-container{
        width: 100% !important;
    }
    .chosen-container-multi .chosen-choices {padding:0 14px}
    .chosen-container-multi .chosen-choices li.search-field {padding-top: 4px;}
    .chosen-container-multi .chosen-choices li.search-field input[type=text]{
        /*text-align: center !important;*/
        font-size: initial;
    }

    /* NEW */
    .chosen-container-multi 
    .chosen-choices {
        padding: 0 6px;
    }
    .chosen-container-multi 
    .chosen-choices 
    li.search-choice {
        background: #fff;
        border-radius: 2px;
        box-shadow: none;
        padding: 4px 24px 4px 8px;
    }
    .chosen-container-multi 
    .chosen-choices 
    li.search-choice 
    .search-choice-close {
        top: 9px;
        right: 5px;
    }
</style>
<?php $img_url = BaseUrl::base() . '/images/'; ?>
<?php $is_alert = isset($alert_added) ? $alert_added : FALSE ?>

<!--<body class="reult-body">-->
<div class="container">
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-5">
            <div class="search-result-content">
                <div class="search-nav">
                    <form action="<?= BaseUrl::base() ?>/event" method="post" id="events_search_form">

                        <!--<input type="hidden" name="_csrf-frontend" value="<?= Yii::$app->request->getCsrfToken() ?>" />-->
                        <h1>Search <a href="javascript:;" onclick="closeNav()" class="nav-cros"><img src="<?= $img_url ?>crose-btn.png" alt="" /></a></h1>
                        <div class="zip-code">
                            <span><b>Zip Code</b></span>
                            <div><input type="text" class="zip-textbox" value="<?= $zip_code ?>" name="zipcode" id="zip_code"/></div>
                        </div>
                        <div class="zip-code">
                            <span><b>Keywords</b></span>
                            <div class="optional1">
                                <select class="html-multi-chosen-select" multiple="multiple" style="width:100%;" name="keywords[]" id="keywords">
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
                                    <option <?= isset($ret_sort) && $ret_sort == 'Closest' ? 'selected' : '' ?> >Closest</option>
                                    <option <?= isset($ret_sort) && $ret_sort == 'Soonest' ? 'selected' : '' ?> >Soonest</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <input type="submit" class="btn go-btn" value="Go">
                        </div>
<!--                        <h1>Filters</h1>
                        <?php $i = 0; ?>
                        <?php foreach (GlobalFunctions::getCategories() as $filter) { ?>
                            <?php $i++; ?>
                            <div class="filter-box">
                                <?php if (isset($ret_filters) && !empty($ret_filters)) { ?>
                                    <input name="filters[]" type="checkbox" <?= in_array($filter['text'], $ret_filters) ? 'checked' : '' ?> value="<?= $filter['text'] ?>" id="<?= $filter['text'] ?>"> 
                                        <input type='checkbox' name='filters[]' value='valuable' class="pinmusic" checked/>
                                    <label class="oper" for="<?= $filter['text'] ?>"> <?= $filter['text'] ?> </label>
                                <?php } else { ?>
                                    <input name="filters[]" type="checkbox" value="<?= $filter['text'] ?>" id="<?= $filter['text'] ?>">
                                    <label class="oper" for="<?= $filter['text'] ?>"> <?= $filter['text'] ?> </label>
                                <?php } ?>
                            </div>
                        <?php } ?>
                        <div>
                            <input type="submit" class="btn go-btn" value="Go">
                        </div>-->
                    </form>
                </div>
<!--                <div class="add-box"><img src="<?= $img_url ?>result-img7.png" alt="" /></div>-->

            </div>
        </div>
        <?php if (isset($ret_sort) && !empty($ret_sort)) { ?>
            <?= $this->render('_result', ['events' => $events, 'zip_code' => $zip_code, 'total_events' => $total_events, 'ret_sort' => $ret_sort, 'ret_filters' => $ret_filters, 'longitude' => $longitude, 'latitude' => $latitude, 'alert_added' => $is_alert]); ?>
        <?php } else { ?>
            <?= $this->render('_result', ['events' => $events, 'zip_code' => $zip_code, 'total_events' => $total_events, 'longitude' => $longitude, 'latitude' => $latitude, 'alert_added' => $is_alert]); ?>
        <?php } ?>
    </div>
    <!--    <div class="row">
            <div class="col-lg-1 col-md-1"></div>
            <div class="col-lg-10 col-md-10">
                <div class="add-box2">
                    <img src="<?= $img_url ?>result-img6.png" alt=""  />
                </div>
            </div>
        </div>-->
</div>
