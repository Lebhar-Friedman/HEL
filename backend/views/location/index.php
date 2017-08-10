<?php

use backend\components\CustomLinkPager;
use yii\helpers\BaseUrl;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\web\JqueryAsset;
use components\GlobalFunction;
?>
<?php
//var_dump($locations);
$this->registerJsFile('@web/js/location.js', ['depends' => [JqueryAsset::className()]]);
$this->title = 'Locations';
?>

<div class="col-lg-12">

    <div class="csv2-comp-content-1">

        <div class ="row">
            <form action="" method="get" name="search" enctype="multipart/form-data">
                <div class =" col-lg-4 inbound-list-2">
                    <div>
                        <input type="text" class="search-box-1 search-img" placeholder="Search Term" name="keyword" value="<?php
                        if (isset($_GET['keyword'])) {
                            echo $_GET['keyword'];
                        }
                        ?>"/>
                    </div>
                </div>
                <div class="col-lg-4 inbound-list-1">
                    <select name="company" id="company">
                        <option value="-1" selected="selected">Company</option>
                        <?php foreach ($companies as $company) { ?>
                            <option value='<?= $company['name'] ?>' <?php
                            if (isset($_GET['company']) && $_GET['company'] === $company['name']) {
                                echo "selected";
                            }
                            ?>><?= $company['name'] ?></option>
                                <?php } ?>
                    </select>
                </div>
            </form>

        </div>
        <div class="row ">
            <div >
                <div class="total-2">Total: 115</div>
            </div>
        </div>


        <div class="table-scroll">      
            <div class="table-csv-list">
                <div class="csv-table-row csv-h-bg clearfix">
                    <div class="table-chk-h-1">Store #</div>
                    <div class="table-title-h-2">Company Name</div>
                    <div class="table-date-h-3">Street Address</div>
                    <div class="table-time-h-4">City</div>
                    <div class="table-category-h-5">State</div>
                    <div class="table-sub-cat-h-6">Zip</div>
                    <div class="table-sub-cat-h-8"></div>
                </div>
                <?php
                foreach ($locations as $location) {
                    ?> 
                    <div class="location-table-row1 clearfix">
                        <div class="table-chk-h-a"><?= $location['store_number'] ?></div>
                        <div class="table-title-h1-b"><?= $location['company'] ?></div>
                        <div class="table-date-h1-c"><?= $location['street'] ?></div>
                        <div class="table-time-h1-d"><?= $location['city'] ?></div>
                        <div class="table-state-h1-e"><?= $location['state'] ?></div>
                        <div class="table-zip-h1-f"><?= $location['zip'] ?></div>
                        <div class="table-blank-h1-e">
                            <a href="<?= \yii\helpers\BaseUrl::base() . '/location/detail?id=' . $location['_id'] ?>" class="edit1-btn"></a> 
                            <a href="javascript:;" onclick="deleteLocation('<?= $location['_id'] ?>', this)" class="del1-btn-1"></a>
                        </div>
                    </div> 
                    <?php
                }
                ?>


            </div>
            <center>
            </center>

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