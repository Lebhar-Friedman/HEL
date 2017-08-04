<?php
/* @var $this yii\web\View */

use components\GlobalFunction;

$this->title = 'Import csv';
$this->registerMetaTag(['name' => 'description', 'content' => 'kjsdhfjkdsfh jkdsfjsdkfhdsjk fdhgds gh']);
//print_r($events);
?>
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
                        <input id="filename" type="text" class="res-upload-textbx" placeholder="file.csv" readonly=""/>
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
<?php
if (count($events) > 0) {
    $this->registerJsFile('@web/js/event.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
    ?>
    <div class="col-lg-12">

        <div class="csv2-comp-content">
            <div class="row ">
                <div class="col-lg-4 col-md-5 col-sm-12 col-xs-12">
                    <div class="flt-lft  b-post-btn2 mrg-rht"><a href="javascript:;" onclick="postSelectedEvent(this)">Bulk Post</a></div>
                    <div class="flt-lft  b-del-btn2"><a href="javascript:;" onclick="deleteSelectedEvent(this)">Bulk Delete</a></div>
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
                        <div class="table-chk-h">
                            <input type="checkbox" id="check_all" name="check_all" class="check-box" onclick="selectAll()"/>
                            <label for="check_all"><span></span></label>
                        </div>
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
                            <div class="table-chk-h1">
                                <input type="checkbox" id="<?= $event['_id'] ?>" name="checkEvent" onclick="parentUnselect(this)" />
                                <label for="<?= $event['_id'] ?>"><span></span></label>
                            </div>
                            <div class="table-title-h1"><?= $event->title ?></div>
                            <div class="table-date-h1"><?= GlobalFunction::getDate('d/m/Y', $event->date_start) . ' - ' . GlobalFunction::getDate('d/m/Y', $event->date_end) ?></div>
                            <div class="table-time-h1"><?= $event->time_start . ' - ' . $event->time_end ?></div>
                            <div class="table-category-h1"><?= implode(',', $event->categories) ?></div>
                            <div class="table-sub-cat-h1"><?= implode(',', $event->sub_categories) ?></div>
                            <div class="table-location-h1">
                                <div class="tc-location"><?= count($event->locations) ?>
                                    <!--<img src="images/caution.png" alt="" />-->
                                </div>
                            </div>
                            <div class="table-cost-h1"><?= (!empty($event->price)) ? '&dollar;' . $event->price : 'Free' ?></div>
                            <div class="table-blank-h1">
                                <div class="flt-lft b-post-btn3 mrg-lftt"><a href="javascript:;" onclick="postEvent('<?= $event->_id ?>', this)" class="<?= ($event->is_post) ? 'disableLink' : 'n' ?>">Post</a></div>
                                <a href="<?= \yii\helpers\BaseUrl::base() . '/event/detail?id=' . $event['_id'] ?>" class="edit1-btn"></a> 
                                <a href="javascript:;" onclick="deleteEvent('<?= $event['_id'] ?>', this)" class="del1-btn"></a>
                            </div>
                        </div>
                    <?php } ?>
                </div> 
            </div>
        </div>    
    </div>
    <?php
} elseif (count($companies) > 0) {
    $this->registerJsFile('@web/js/company.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
    ?>
    <div class="col-lg-12">

        <div class="csv2-comp-content">
            <div class="row ">
                <div class="col-lg-4 col-md-5 col-sm-12 col-xs-12">
                    <!--                        <div class="flt-lft  b-post-btn2 mrg-rht"><a href="#">Bulk Post</a></div>
                                            <div class="flt-lft  b-del-btn2"><a href="#">Bulk Delete</a></div>-->
                </div>
                <div class="col-lg-4 col-md-5 col-sm-6 col-xs-6">
                    <div class="events">Companies Added</div>
                </div>
                <div class="col-lg-4 col-md-2 col-sm-6 col-xs-6">
                    <div class="total-1">Total: <?= count($companies) ?></div>
                </div>
            </div>
            <div class="table-scroll">
                <div class = "list-table">
                    <div class = "table-th">
                        <div class = "table-company">Company</div>
                        <div class = "table-events">Events</div>
                        <div class = "table-locations">Locations</div>
                        <div class = "table-space">&nbsp;
                        </div>
                    </div>
                    <?php foreach ($companies as $company) {
                        ?>
                        <?php
                        if ($company['logo'] === null || $company['logo'] === '' || !isset($company['logo'])) {
                            $logo_url = \yii\helpers\Url::to('@web/images/upload-logo.png');
                        } else {
                            $logo_url = \yii\helpers\Url::to('@web/uploads/' . $company['logo']);
                        }
                        ?>
                        <div class="main-table">
                            <div class="tc1"><img src="<?= $logo_url ?>" alt=""  width="60px" /></div>
                            <div class="tc2"><?= $company['name'] ?></div>
                            <div class="tc2-2"><?= $company['t_events'] ?></div>
                            <div class="tc3"><?= $company['t_locations'] ?><img src="<?= \yii\helpers\Url::to('@web/images/caution.png') ?>" alt="" /></div>
                            <div class="tc4">
                                <a href="javascript:;" onclick="deleteCompany('<?= $company['_id'] ?>', this)" class="del-btn "></a>
                                <a href="<?= \yii\helpers\BaseUrl::base() . '/company/detail?cid=' . $company['_id'] ?>" class="edit-btn "></a>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
