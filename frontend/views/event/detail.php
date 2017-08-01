<?php

use common\functions\GlobalFunctions;
use yii\helpers\BaseUrl;
use yii\web\JqueryAsset;
use components\GlobalFunction;

$this->registerCssFile('@web/css/results.css');
$this->registerCssFile('@web/css/chosen.min.css');
$this->registerJsFile('@web/js/chosen.jquery.min.js', ['depends' => [JqueryAsset::className()]]);
$this->title = $event['title'];
?>
<?php $this->registerJsFile('@web/js/site.js', ['depends' => [JqueryAsset::className()]]); ?>
<style>
    .chosen-choices{
        min-height: 45px;
        display: block;
        border: 1px solid #dbdbdb;
        background: #FFF;
        padding-top: 7px !important;
        background-image: none !important;
    }
    .chosen-container-multi .chosen-choices li.search-field input[type=text]{
        /*color: #000 !important;*/
    }
</style>
<?php $img_url = BaseUrl::base() . '/images/'; ?>

<div class="container">
    	<div class="row">
        	<div class="col-lg-1"></div>
            <div class="col-lg-7 col-md-8 col-sm-8 col-xs-7">
            	<div class="senior-day-content">
                	<h1><?=$event['title']?></h1>
                        <h2><?=GlobalFunction::getDate('M d', $event['date_start'])?> - <?=GlobalFunction::getDate('d', $event['date_end'])?></h2>
                    <?=$event['time_start']?> - <?=$event['time_end']?> 
                    <div class="save-share-btn clearfix">
                    	<a href="javascript:;" onclick="saveEvent('<?= $event['_id'] ?>',this)"><img src="<?= $img_url ?>star-icon.png" alt="" /> SAVE</a>
                        <a href="#"><img src="<?= $img_url ?>share-icon.png" alt="" />SHARE</a>
                    </div>
                    <div class="clearfix">
                    	<?php
                        foreach ($event['categories'] as $category):
                        ?>
                        <div class="heart-text"><?=$category?></div>
                        <?php
                        endforeach;
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-5">
                <div class="cvs-text">
                    <img src="<?=GlobalFunctions::getCompanyLogo($company['name'])?>" alt="" />
                	<div class="margin-t">
                        <?=$company['street']?><br />
                        <?=$company['state']?>, <?=$company['zip']?><br />
                        <?=$company['phone']?><br />
                    </div>
                    <span>More locations nearby</span>
                </div>
            </div>
        </div>
        <div class="row">
        	<div class="col-lg-1"></div>
            <div class="col-lg-10">
            	<div class="free-health-content">
                	<h1>FREE Healthcare Services</h1>
                    <h2>No appointment required!</h2>
                    <div class="row">
                    	<?php
                        foreach ($event['sub_categories'] as $sub_category):
                        ?>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                         	<i><?=$sub_category?></i>
			</div>
                        <?php
                        endforeach;
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="event-detail-img show-on-mobile"><img src="<?= $img_url ?>result-img7.png" alt="" /></div>
        <div class="row">
        	<div class="col-lg-1"></div>
            <div class="col-lg-6 col-md-8 col-sm-8">
            	<div class="event-detail-text">
                	<h1>Health event details</h1>
                	<?=$event['description']?>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4">
            	<div class="event-detail-img hide-on-mobile"><img src="<?= $img_url ?>result-img7.png" alt="" /></div>
            </div>
      	</div>
        <div class="row">
        	<div class="col-lg-1"></div>
            <div class="col-lg-10">
            	<div class="map2-content">
                	<h1>Other locations nearby for this event</h1>
                    <img src="<?= $img_url ?>map-img.png" alt="" />
                </div>
            </div>
        </div>
        <div class="row">
        	<div class="col-lg-1"></div>
          	<div class="col-lg-7 col-md-8 col-sm-8">
            	<div class="other-event">Other events here</div>
                <div class="multi-service2">
                <?php
                foreach ($companyEvents as $companyEvent):
                ?>
                    <h1>Multiple Services<?=$companyEvent['title']?></h1>
                    <h2><?=GlobalFunction::getDate('M d', $companyEvent['date_start'])?> - <?=GlobalFunction::getDate('d', $companyEvent['date_end'])?></h2>
                    <span><?php if(isset($companyEvent['price'])){echo "$".$companyEvent['price'];}?></span>
                    <div class="clearfix">
                        <?php
                        foreach ($companyEvent['sub_categories'] as $companySubCategories):
                        $i = 1;
                        if($i<=6){
                        ?>
                    	<div class="table-cust">
                        	<i><?=$companySubCategories?></i>
                        </div>
                        <?php
                        }else{
                        ?>
                    	<div class="table-cust show-on-mobile">
                        	<i><?=$companySubCategories?></i>
                        </div>
                        <?php
                        }
                        endforeach;
                        ?>
                    </div>
                    <?php
                    endforeach;
                    ?>
                </div>
                <div class="email-content">
                	
                	<div class="row">
                    	<div class="col-lg-11 col-md-12">
                        	<h1>Alert me when more health events added at this location!</h1>
                            <div class="show-on-mobile">Alert me when healthcare events are added at CVS Pharmacy, 503 Wonder Lane</div>
                        </div>
                    </div>
                    <div class="row">
                    	<div class="col-lg-8 col-md-10">
                        	
                            <div class="email-conatiner">
                            	<input type="text" class="email-textbox" placeholder="Email" />
                                <input type="submit" value="Go" class="submitbtn" />
                            </div>
                        </div>
                    </div>
                	
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-4">
            	<div class="cvs-text mobile-center">
                	<img src="<?=GlobalFunctions::getCompanyLogo($company['name'])?>" alt="" />
                	<div class="find-out-text">
                    	<img src="<?= $img_url ?>result-detail-img2.png" alt="" />
                       	<h2>Find out more <br class="hide-on-mobile" />about CVS <br class="hide-on-mobile" />Pharmacies</h2>
                    </div>
                </div>
            </div>
        </div> 
    </div>
    