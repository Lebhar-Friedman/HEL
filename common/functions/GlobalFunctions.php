<?php

namespace common\functions;

use common\models\Company;

class GlobalFunctions {

    public static function getCompanyLogo($name) {
        $company = Company::findOne(['name' => $name]); //->where(['name'=> $name]);
        if ($company && $company->logo !== NULL) {
            return IMG_URL . $company->logo;
        }
        return \yii\helpers\BaseUrl::base().'/images/upload-logo.png';
    }
    
    public static function getCategories() {
        $categories=array(
            array('value'=>1,'text'=>'Diabetes'),
            array('value'=>2,'text'=>'Heart'),
            array('value'=>3,'text'=>'Cancer'),
            array('value'=>4,'text'=>'Category 4'),
            array('value'=>5,'text'=>'Category 5'),
            );
        return $categories;
    }
    
    public static function getKeywords() {
        $keywords=array(
            array('value'=>1,'text'=>'Blood glucose'),
            array('value'=>2,'text'=>'Heart'),
            array('value'=>3,'text'=>'Cancer'),
            array('value'=>4,'text'=>'Category 4'),
            array('value'=>5,'text'=>'Category 5'),
            );
        return $keywords;
    }

}
