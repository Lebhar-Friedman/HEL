<?php

use backend\components\CustomLinkPager;
use yii\helpers\BaseUrl;
use yii\helpers\Url;
use yii\web\JqueryAsset;
use yii\widgets\LinkPager;
?>
<?php $this->registerJsFile('@web/js/company.js', ['depends' => [JqueryAsset::className()]]); ?>
<?php $this->title='Companies'; ?>
<div class="col-lg-12">
    <div class="search-comp-content">
        <div class="company-container">
            <div class="col-lg-2"></div>
            <center>
                <div class="row col-lg-8">
                    <form method="get" action="<?= BaseUrl::base() . '/company' ?>">
                        <input type="text" name="name" class="search-box search-img" value="" placeholder="Search Term" />
                    </form>
                </div>
            </center>

            <div class="row ">
                <div class="col-lg-6 col-md-4 col-sm-4">
                    <div class="flt-lft  add-new-btn2"><a href="<?= BaseUrl::base() . '/company/detail'; ?>">Add New</a></div>
                </div>
                <div class="col-lg-6 col-md-8 col-sm-8">
                    <div class="total">Total: <span id="total"><?= $total; ?></span></div>
                </div>
            </div>

            <div class="list-table">
                <div class="table-th">
                    <div class="table-company">Company</div>
                    <div class="table-events">Events</div>
                    <div class="table-locations">Locations</div>
                    <div class="table-space">&nbsp;</div>                                                
                </div>	
                <?php foreach ($companies as $company) { ?>
                    <?php
                    if ($company['logo'] === null || $company['logo'] === '' || !isset($company['logo'])) {
                        $logo_url = Url::to('@web/images/upload-logo.png');
                    } else {
                        $logo_url = Url::to('@web/uploads/' . $company['logo']);
                    }
                    ?>
                    <div class="main-table">
                        <div class="tc1"><img src="<?= $logo_url ?>" alt=""  width="60px" /></div>
                        <div class="tc2"><?= $company['name'] ?></div>
                        <div class="tc2-2"><?= $company['t_events'] ?></div>
                        <div class="tc3"><?= $company['t_locations'] ?>
                            <!--<img src="<?= Url::to('@web/images/caution.png') ?>" alt="" />-->
                        </div>
                        <div class="tc4">
                            <a href="javascript:;" onclick="deleteCompany('<?= $company['_id'] ?>',this)" class="del-btn "></a>
                                <a href="<?= BaseUrl::base() . '/company/detail?cid=' . $company['_id'] ?>" class="edit-btn "></a>
                        </div>
                    </div>
                <?php } ?>
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