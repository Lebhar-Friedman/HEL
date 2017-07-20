<?php
/* @var $this yii\web\View */

use components\GlobalFunction;

$this->title = 'Import csv';
$this->registerMetaTag(['name' => 'description', 'content' => 'kjsdhfjkdsfh jkdsfjsdkfhdsjk fdhgds gh']);
//print_r($events);
?>
<div class="row"><!--<div class="container-fluid outer-container">-->
    <div class="col-lg-12">
        <div class="csv-comp-content">
            <div class="upload clearfix">
                <div >
                    <h3>Upload CSV</h3>
                    <h4>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed dignissim varius mollis. 				
                        Sed ut perspiciatis unde omnis iste natus sit voluptatem accusantium laudantium,totam rem 			
                        aperiam.</h4>
                </div>
                <div class="chose-file-btn">
                    <form class="" id="fileform" enctype="multipart/form-data">
                        <input type="hidden" name="_csrf" value="">
                        <input id="import" class="hidden" type="file" name="UploadForm[file]" accept=".csv" onchange="setcsvfilename();">
                        <div class="col-lg-8 res-upload-content">
                            <input id="filename" type="text" class="res-upload-textbx" placeholder="Events.CSV" readonly=""/>
                            <a href="javascript:void();" onclick="$('#import').click();">Choose File</a>
                        </div>
                        <div class="col-lg-4 inbound-list ">
                            <select id="import_type" name="import_type">
                                <option value="company">Company</option>
                                <option value="event">Event</option>                                            
                            </select>
                        </div>

                    </form>
                </div>

            </div>
            <div class="upload-btn">
                <a href="javascript:void()"  onclick="importcsv();">Upload</a>
            </div>  
        </div>
    </div>
</div>
<div class="row"><!--<div class="container-fluid outer-container">-->

    <div class="col-lg-12">

        <div class="csv2-comp-content">
            <div class="row ">
                <div class="col-lg-4 col-md-5 col-sm-12 col-xs-12">
                    <div class="flt-lft  b-post-btn2 mrg-rht"><a href="#">Bulk Post</a></div>
                    <div class="flt-lft  b-del-btn2"><a href="#">Bulk Delete</a></div>
                </div>
                <div class="col-lg-4 col-md-5 col-sm-6 col-xs-6">
                    <div class="events">Events Added</div>
                </div>
                <div class="col-lg-4 col-md-2 col-sm-6 col-xs-6">
                    <div class="total-1">Total: <?= count($events) ?></div>
                </div>
            </div>

            <div class="table-scroll">   
                <div class="table-csv-list">
                    <div class="csv-table-row csv-h-bg clearfix">
                        <div class="table-chk-h"><a href="#" class="check-box"></a></div>
                        <div class="table-title-h">Title</div>
                        <div class="table-date-h">Date</div>
                        <div class="table-time-h">Time</div>
                        <div class="table-category-h">Categories</div>
                        <div class="table-sub-cat-h">Sub-Categories</div>
                        <div class="table-location-h">Locations</div>
                        <div class="table-cost-h">Cost</div>
                        <div class="table-blank-h"></div>
                    </div>
                    <?php foreach ($events as $event) { ?>                        
                        <div class="csv-table-row1 clearfix">
                            <div class="table-chk-h1"><a href="#" class="check-box1"></a></div>
                            <div class="table-title-h1"><?= $event->title ?></div>
                            <div class="table-date-h1"><?= GlobalFunction::getDate('d/m/Y', $event->date_start) . ' - ' . GlobalFunction::getDate('d/m/Y', $event->date_end) ?></div>
                            <div class="table-time-h1"><?= $event->time_start . ' - ' . $event->time_end ?></div>
                            <div class="table-category-h1"><?= implode(',', $event->categories) ?></div>
                            <div class="table-sub-cat-h1"><?= implode(',', $event->sub_categories) ?></div>
                            <div class="table-location-h1">
                                <div class="tc-location"><?= count($event->locations) ?>
                                    <img src="images/caution.png" alt="" />
                                </div>
                            </div>
                            <div class="table-cost-h1"><?= (!empty($event->price)) ? '&dollar;' . $event->price : 'Free' ?></div>
                            <div class="table-blank-h1">
                                <div class="flt-lft b-post-btn3 mrg-lftt"><a href="#">Post</a></div>
                                <a href="#" class="edit1-btn "></a> 
                                <a href="#" class="del1-btn "></a>
                            </div>
                        </div>
                    <?php } ?>
                </div> 
            </div>
        </div>    
    </div>
</div>
