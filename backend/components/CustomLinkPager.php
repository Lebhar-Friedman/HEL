<?php

namespace backend\components;

use yii\helpers\BaseUrl;
use yii\widgets\LinkPager;

class CustomLinkPager extends LinkPager {
    
    public function __construct() {
        $img_url=BaseUrl::base().'/images/';
        $this->nextPageLabel = '<img src="' . $img_url . 'next-btn.png" />';
        $this->prevPageLabel = '<img src="' . $img_url . 'prev-btn.png" />';
        $this->firstPageLabel = '<img src="' . $img_url . 'prev-btn.png" /><img src="' . $img_url . 'prev-btn.png" />';
        $this->lastPageLabel = '<img src="' . $img_url . 'next-btn.png" /><img src="' . $img_url . 'next-btn.png" />';
    }

}
