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
?>
<div class="row">

    <div class="col-lg-12">


        <div class="csv2-comp-content">

            <div class="row">
                <div class="s-events">Search Events</div>
            </div>



            <div class="table-scroll">                              

                <form action="<?= BaseUrl::base() ?>/event" id="event-search" method="get">
                    <div class="cntl-table-trgreen">
                        <div class="cntl-table-td-date">
                            <input type="text" name="eventTerm" class="cntl-table-trgreen-search" placeholder="Search Term" value="<?php
                            if (isset($_GET['eventTerm'])) {
                                echo $_GET['eventTerm'];
                            }
                            ?>"></div>
                        <div class="cntl-table-td-from">From:</div>
                        <div class="cntl-table-td-date">
                            <input type="date" name="eventFrom" class="cntl-table-trgreen-date" value="<?php
                            if (isset($_GET['eventFrom'])) {
                                echo $_GET['eventFrom'];
                            }
                            ?>"></div>
                        <div class="cntl-table-td-to">To:</div>
                        <div class="cntl-table-td-date">
                            <input type="date" name="eventTo" class="cntl-table-trgreen-date" value="<?php
                            if (isset($_GET['eventTo'])) {
                                echo $_GET['eventTo'];
                            }
                            ?>"></div>
                        <div class="cntl-table-td-con-type ">
                            <select name="eventCompany">
                                <option value="-1" selected="selected">Company</option>
                                <?php foreach ($companies as $company) { ?>
                                    <option value='<?= $company['name'] ?>' <?php
                                    if (isset($_GET['eventTerm']) && $_GET['eventCompany'] === $company['name']) {
                                        echo "selected";
                                    }
                                    ?>><?= $company['name'] ?></option>
                                        <?php } ?>
                            </select>
                        </div>
                        <div class="cntl-table-td-con-type ">
                            <select name="eventCategory">
                                <option value="-1" selected="selected">Category</option>
                                <option <?php
                                    if (isset($_GET['eventCategory']) && $_GET['eventCategory'] === 'Diabetes') {
                                        echo "selected";
                                    }
                                    ?>>Diabetes</option>
                                <option>Category 1</option>
                            </select>
                        </div>
                        <div class="cntl-table-td-con-type ">
                            <select name="eventSubCategory">
                                <option value="-1" selected="selected">Sub-Category</option>
                                <option <?php
                                    if (isset($_GET['eventSubCategory']) && $_GET['eventSubCategory'] === 'Blood glucose') {
                                        echo "selected";
                                    }
                                    ?>>Blood glucose</option>
                                <option>Category 1</option>
                            </select>
                        </div>
                        <div class="search-butn">
                            <button type="submit">Search</button>
                        </div>
                    </div>		
                </form>    
                <div class="row ">
                    <div class="col-lg-4 col-md-5 col-sm-12 col-xs-12">
                        <div class="flt-lft  b-post-btn2 mrg-rht"><a href="javascript:;" onclick="postSelectedEvent(this)">Bulk Post</a></div>
                        <div class="flt-lft  b-del-btn2"><a href="javascript:;" onclick="deleteSelectedEvent(this)">Bulk Delete</a></div>
                    </div>
                    <div class="col-lg-4 col-md-5 col-sm-6 col-xs-6">
                        <div class="events">Events Added</div>
                    </div>
                    <div class="col-lg-4 col-md-2 col-sm-6 col-xs-6">
                        <div class="total-1">Total: <?= $total; ?></div>
                    </div>
                </div>


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
                    <?php
                    foreach ($events as $event) {
                        ?>        
                        <div class="csv-table-row1 clearfix">
                            <div class="table-chk-h1">
                                <input type="checkbox" id="<?= $event['_id'] ?>" name="checkEvent" onclick="parentUnselect(this)" />
                                <label for="<?= $event['_id'] ?>"><span></span></label>

                            </div>
                            <div class="table-title-h1"><?= $event['title'] ?></div>
                            <div class="table-date-h1"><?= GlobalFunction::getDate('m/d/Y', $event['date_start']) ?> - <?= GlobalFunction::getDate('m/d/Y', $event['date_end']) ?></div>
                            <div class="table-time-h1"><?= $event['time_start'] ?> - <?= $event['time_end'] ?></div>
                            <div class="table-category-h1"><?= implode(',', $event->categories) ?></div>
                            <div class="table-sub-cat-h1"><?= implode(',', $event->sub_categories) ?></div>
                            <div class="table-location-h1">
                                <div class="tc-location"><a href="<?= BaseUrl::base() . '/location?eid=' . $event['_id'] ?>"><?= sizeof($event['locations']); ?></a>
                                    <img src="<?= BaseUrl::base() ?>/images/caution.png" alt="" />
                                </div>
                            </div>
                            <div class="table-cost-h1">Free</div>
                            <div class="table-blank-h1"><div class="flt-lft b-post-btn3 mrg-lftt">
                                    <a href="javascript:;" onclick="postEvent('<?= $event['_id'] ?>', this)" >Post</a></div>
                                <a href="<?= BaseUrl::base() . '/event/edit?eid=' . $event['_id'] ?>" class="edit1-btn "></a>
                                <a href="javascript:;" onclick="deleteEvent('<?= $event['_id'] ?>', this)" class="del1-btn "></a>
                            </div>
                        </div> 
                    <?php } ?>
                </div> 
            </div>
<?php
if (isset($pagination)) {
                echo LinkPager::widget([
                    'pagination' => $pagination,
                    'options' => ['class' => 'pagging clearfix'],
                    'prevPageLabel' => '<img src="' . BaseUrl::base() . '/images/prev-btn.png" alt=""/>',
                    'nextPageLabel' => '<img src="' . BaseUrl::base() . '/images/next-btn.png" alt=""/>',
                    'firstPageLabel' => '<img src="' . BaseUrl::base() . '/images/prev-btn.png" alt=""/><img src="' . BaseUrl::base() . '/images/prev-btn.png" alt=""/>',
                    'lastPageLabel' => '<img src="' . BaseUrl::base() . '/images/next-btn.png" alt=""/><img src="' . BaseUrl::base() . '/images/next-btn.png" alt=""/>',
                ]);
}
?>
</div>
</div>
</div>
