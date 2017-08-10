<?php

namespace common\functions;

use common\models\Company;
use Yii;
use yii\helpers\BaseUrl;
use const IMG_URL;

class GlobalFunctions {

    public static function getCompanyLogo($name) {
        $company = Company::findOne(['name' => $name]); //->where(['name'=> $name]);
        if ($company && $company->logo !== NULL) {
            return IMG_URL . $company->logo;
        }
        return BaseUrl::base() . '/images/upload-logo.png';
    }

    public static function getCategories() {
        $categories = array(
            array('value' => 1, 'text' => 'Diabetes Care'),
            array('value' => 2, 'text' => 'Heart Health'),
            array('value' => 3, 'text' => 'Immunization/Vaccination'),
            array('value' => 4, 'text' => 'Lung Care'),
            array('value' => 5, 'text' => 'Wellness'),
            array('value' => 6, 'text' => 'Women\'s Health'),
            array('value' => 7, 'text' => 'Senior Care'),
            array('value' => 8, 'text' => 'Mental Health'),
        );
        return $categories;
    }

    public static function getSubcategories() {
        $sub_categories = array();
        $temp = array(
            'Blood Glucose', 'Cholesterol', 'Flu',
            'A1C', 'HDL', 'Hepatitis', 'Diabetes II', 'Blood Pressure', 'HPV', 'Body Fat', 'LDL',
            'Meningitis', 'Triglycerides', 'MMR', 'Risk Ratio', 'Pnemonia', 'Body Fat', 'Shingles',
            'Full Limpid panel', 'TD', 'TDAP', 'Chickenpox/ Varicella', 'Senior Immunizations',
            'Asthma', 'Nutrition', 'Osteoperosis', 'COPD	Beauty	Women\'s Health',
            'Smoking Cessastion', 'Vision', 'Lung Health', 'Hearing', 'Smoking Cessassion', 'Sun Care',
            'Weight', 'Dental', 'Allergies', 'Foot care', 'Headaches', 'Kids', 'Chiropractic', 'Skin Care',
            'Body fat/ Weight', 'Osteoperosis', 'Alzheimers', 'Healthy Aging', 'Anxiety', 'Alzheimers', 'Bipolar',
            'Memory Screening', 'Depression', 'Senior Immunizations', 'Psychosis', 'Hearing', 'PTSD', 'Work Health',
            'Memory Screening', 'Alcohol/Substance Abuse', 'Smoking Cessation', 'Parent',
        );

        foreach ($temp as $key => $sub_cat) {
            $sub_categories[] = array('value' => $key, 'text' => $sub_cat);
        }
        return $sub_categories;
    }

    public static function getKeywords() {
        $cats = GlobalFunctions::getCategories();
        $sub_cats = GlobalFunctions::getSubcategories();
        $keywords = array();
        foreach ($cats as $category) {
            $keywords[] = array('value' => $category['value'], 'text' => $category['text']);
        }
        foreach ($sub_cats as $s_cat) {
            $keywords[] = array('value' => $s_cat['value'], 'text' => $s_cat['text']);
        }
        return $keywords;
    }

    public static function getCategoryList() {
        $array = self::getCategories();
        $list = [];
        foreach ($array as $value) {
            $list[$value['text']] = $value['text'];
        }
        return $list;
    }

    public static function getSubCategoryList() {
        $array = self::getSubcategories();
        $list = [];
        foreach ($array as $value) {
            $list[$value['text']] = $value['text'];
        }
        return $list;
    }

    public static function getCookiesOfLngLat() {

        $cookies = Yii::$app->request->cookies;
        if (($cookie = $cookies->get('language')) !== NULL) {
            $language = $cookie->value;
        }
        if ($cookies->has('longitude') && ($cookies->has('latitude'))) {
            $lng = $cookies->getValue('longitude',FALSE);
            $lat = $cookies->getValue('latitude',FALSE);
            if($lng == FALSE || $lat == FALSE){
                return FALSE;
            }else{
                return ['longitude' => $lng, 'latitude' => $lat];
            }
        }else{
            return FALSE;
        }
    }
    
    public static function sendEmail($html_file,$send_to,$subject,$arguments) {
        return Yii::$app->mailer->compose(
                                ['html' => $html_file], $arguments
                        )
                        ->setTo($send_to)
                        ->setFrom(Yii::$app->params['supportEmail'])
                        ->setSubject($subject)
                        ->send();
    }

}
