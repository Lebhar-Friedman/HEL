<?php

use yii\widgets\LinkPager; ?>

<div class="col-lg-12">
    <div class="search-comp-content">
        <div class="company-container">
            <div class="col-lg-2"></div>
            <center><div class="row col-lg-8"><input type="text" class="search-box search-img" value="" 		 					placeholder="Search Term" /></div></center>

            <div class="row ">
                <div class="col-lg-6 col-md-4 col-sm-4">
                    <div class="flt-lft  add-new-btn2"><a href="detail">Add New</a></div>
                </div>
                <div class="col-lg-6 col-md-8 col-sm-8">
                    <div class="total">Total: <?= $total; ?></div>
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
                        $logo_url = '../../../common/images/upload-logo.png';
                    } else {
                        $logo_url = '../../../common/uploads/' . $company['logo'];
                    }
                    ?>
                    <div class="main-table">
                        <div class="tc1"><img src="<?= $logo_url ?>" alt=""  width="60px" /></div>
                        <div class="tc2"><?= $company['name'] ?></div>
                        <div class="tc2-2">26</div>
                        <div class="tc3">26<img src="images/caution.png" alt="" /></div>
                        <div class="tc4"><a href="#" class="del-btn "></a><a href="#" class="edit-btn "></a></div>
                    </div>
                <?php } ?>
                <div class="main-table">
                    <div class="tc1"><img src="images/wallmart.png" alt="" /></div>
                    <div class="tc2">Wallmart Pharmacy</div>
                    <div class="tc2-2">11</div>
                    <div class="tc5">11</div>
                    <div class="tc4"><a href="#" class="del-btn "></a><a href="#" class="edit-btn "></a></div>
                </div>
            </div>

        </div>
    </div>
</div>
<?php
if (isset($pagination)) {
    echo LinkPager::widget([
        'pagination' => $pagination,
    ]);
}
?>