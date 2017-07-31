<?php

use backend\components\CustomLinkPager;
use yii\helpers\BaseUrl;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\web\JqueryAsset;
use components\GlobalFunction;
?>
<?php
//var_dump($events);
$this->registerJsFile('@web/js/event.js', ['depends' => [JqueryAsset::className()]]);
$this->title = 'Events';
$baseUrl = Yii::$app->request->baseUrl;
?>
<?php if (empty($model)) { ?>
    <div class="col-lg-12">
        <div class="csv-comp-content-1">
            <div class="upload clearfix">
                <div >
                    <h3>Invalid Event Id</h3>
                </div>
            </div>
        </div>
    </div>
    <?php
} else {
    ?>
    <div class="col-lg-12">
        <div class="csv-comp-content-1">
            <div class="upload clearfix">
                <div >
                    <h3>Event</h3>
                </div>
            </div>

            <div class ="row">
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 upload-btn-2">
                    <a href="javascript:;" onclick="window.history.go(-1); return false;"><img src="<?= $baseUrl ?>/images/Shape3.png"></a>
                </div>
                <div class ="col-lg-4 col-md-3 col-sm-1"></div>
                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-6 upload-btn-1">
                    <a href="#">Edit</a>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-6 upload-btn-3">
                    <a href="#" ><?= $model->is_post ? 'Unpublish' : 'Publish' ?></a>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12 upload-btn-4">
                    <a href="javascript:;" onclick="deleteEvent('<?= $model->eid ?>', this, 'event/');">Delete</a>
                </div>
            </div> 
            <br>
            <br>

            <div class ="row mrgd">            
                <div class="col-lg-2 ">
                    <STRONG>Dates:</STRONG>
                </div>
                <div class="col-lg-10 ">
                    <?= GlobalFunction::getDate('m/d/Y', $model->date_start) . ' - ' . GlobalFunction::getDate('m/d/Y', $model->date_end) ?>
                </div>
            </div>
            <div class ="row mrgd">
                <div class="col-lg-2 ">
                    <strong>Time:</strong>
                </div>
                <div class="col-lg-10 ">
                    <?= $model->time_start . ' - ' . $model->time_end ?>
                </div>
            </div>
            <div class ="row mrgd">
                <div class="col-lg-2  ">
                    <strong>Event Title:</strong>
                </div>
                <div class="col-lg-10 ">
                    <?= $model->title ?>
                </div>
            </div>
            <div class ="row mrgd">
                <div class="col-lg-2 ">
                    <strong>Categories:</strong>
                </div>
                <div class="col-lg-10 ">
                    <?= !empty($model->categories) ? implode(', ', $model->categories) : '' ?>
                </div>
            </div>
            <div class ="row mrgd">
                <div class="col-lg-2 ">
                    <strong>Sub-Categories:</strong>
                </div>
                <div class="col-lg-10 ">
                    <?= !empty($model->sub_categories) ? implode(', ', $model->sub_categories) : '' ?>
                </div>
            </div>
            <div class ="row mrgd">
                <div class="col-lg-2 ">
                    <strong>Cost:</strong>
                </div>
                <div class="col-lg-10 ">
                    <?= !empty($model->price) ? $model->price : 'Free' ?>
                </div>
            </div>

            <div class ="row mrgd">
                <div class="col-lg-2 ">
                    <strong>Description:</strong>
                </div>
                <div class="col-lg-10 ">
                    <?= !empty($model->description) ? $model->description : 'Free' ?>
                </div>
            </div>
        </div>
    </div>


    <div class="col-lg-12">

        <div class="csv2-comp-content-11">
            <div class="row ">
                <div class="col-lg-4 col-md-5 col-sm-12 col-xs-12">
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
                </div>
                <div class="col-lg-4 col-md-3 col-sm-6 col-xs-6">
                    <div class="total-1">Total Locations: <?= count($locations) ?></div>
                </div>
            </div>

            <div class="table-scroll">    
                <div class="table-csv-list">
                    <div class="csv-table-row csv-h-bg clearfix">
                        <div class="table-chk-h-22">Store #</div>
                        <div class="table-title-h-33">Store Name</div>
                        <div class="table-date-h-44">Contact</div>
                        <div class="table-time-h-55">Phone</div>
                        <div class="table-category-h-66">Address</div>
                        <div class="table-blank-h-77"></div>
                    </div>
                    <?php foreach ($locations as $location) { ?>
                        <div class="csv-table-row1 event-table-row1 clearfix">
                            <div class="table-chk-h1-10"><?= $location->location_id ?></div>
                            <div class="table-title-h1-20"><?= $location->company ?></div>
                            <div class="table-date-h1-30"><?= $location->contact_name ?></div>
                            <div class="table-time-h1-40"><?= $location->phone ?></div>
                            <div class="table-category-h1-50"><?= $location->street . ', ' . $location->city . ', ' . $location->state . ', ' . $location->zip ?></div>
                            <div class="table-blank-h1-60">
                                <a href="<?= BaseUrl::base() . '/location/detail?id=' . $location['_id'] ?>" class="edit1-btn-6 "></a>
                                <img  src="<?= $baseUrl ?>/images/alert.png">
                                <a href="javascript:;" onclick="deleteLocation('<?= $location['_id'] ?>', this)" class="del1-btn-6 "></a> </div>                    
                        </div>
                    <?php } ?>
                    <!--                    <div class="csv-table-row1 event-table-row1 clearfix">
                                            <div class="table-chk-h1-10">1</div>
                                            <div class="table-title-h1-20">Wallgreens</div>
                                            <div class="table-date-h1-30">Smith Doe</div>
                                            <div class="table-time-h1-40">(408) 386-9429</div>
                                            <div class="table-category-h1-50">City Hall - 555 West 66th Avenue, San Mateo, CA 94403</div>
                                            <div class="table-blank-h1-65"><a href="#" class="edit1-btn-65 "></a><a href="#" class="del1-btn-6 "></a> </div>
                                        </div>-->

                </div>
            </div> 
        </div>
    </div>
<?php } ?>