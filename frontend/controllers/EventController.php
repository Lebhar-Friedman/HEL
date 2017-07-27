<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace frontend\controllers;

use common\models\Event;
use common\models\Company;
use components\GlobalFunction;
use Yii;
use yii\web\Controller;
use yii\web\Cookie;

class EventController extends Controller {

    public function actionIndex() {
//        echo "<pre>";
//        print_r(GlobalFunction::getDates());exit;
//        $long = 74.329376;
//        $lat = 31.582045;
//        echo GlobalFunction::getZipFromLongLat($long, $lat);
        $longitude;
        $latitude;
        if (Yii::$app->request->get('zipcode') === NULL) {

            $ip = Yii::$app->request->userIP;
//            $ip = '103.7.78.171';
            $latitude = Yii::$app->ip2location->getLatitude($ip);
            $longitude = Yii::$app->ip2location->getLongitude($ip);
            $zip_code = Yii::$app->ip2location->getZIPCode($ip);

            $cookies = Yii::$app->request->cookies;

            if (($cookie_long = $cookies->get('longitude')) !== null && ($cookie_lat = $cookies->get('latitude'))) {
                $longitude = $cookie_long->value;
                $latitude = $cookie_lat->value;
                $temp_zip = GlobalFunction::getZipFromLongLat($longitude, $latitude);
                $zip_code = $temp_zip ? $temp_zip : $zip_code;
            }
        } else {
            $zip_code = urldecode(Yii::$app->request->get('zipcode'));
            $longlat = GlobalFunction::getLongLatFromZip($zip_code);
            $latitude = $longlat['lat'];
            $longitude = $longlat['long'];
        }
        if (Yii::$app->request->isPost) {
            $zip_code = Yii::$app->request->post('zipcode');
            $keywords = Yii::$app->request->post('keywords');
            $sort_by = Yii::$app->request->post('sortBy');
            $filters = Yii::$app->request->post('filters');

            $longlat = GlobalFunction::getLongLatFromZip($zip_code);
            $latitude = $longlat['lat'];
            $longitude = $longlat['long'];
            if (($cookie_long = $cookies->get('longitude')) !== null && ($cookie_lat = $cookies->get('latitude'))) {
                $longitude = $cookie_long->value;
                $latitude = $cookie_lat->value;
                $temp_zip = GlobalFunction::getZipFromLongLat($longitude, $latitude);
                $zip_code = $temp_zip ? $temp_zip : $zip_code;
            }

            $events_dist = $this->getEventsWithDistance($zip_code, $keywords, $filters, $longitude, $latitude, $sort_by);
            $total_events = sizeof($events_dist);

            return $this->render('index', ['events' => $events_dist, 'zip_code' => $zip_code, 'total_events' => $total_events, 'ret_keywords' => $keywords, 'ret_filters' => $filters, 'ret_sort' => $sort_by]);
        }
        $events = $this->getEventsWithDistance($zip_code, null, null, $longitude, $latitude);
        $total_events = sizeof($events);

        return $this->render('index', ['events' => $events, 'zip_code' => $zip_code, 'total_events' => $total_events]);
    }

    public function actionDetail() {
        $query = Event::find();
        $eid = urldecode(Yii::$app->request->get('eid'));
        if($eid !== ''){
            $query->andWhere(['=','_id', $eid]);
        }        
        $event = $query->one();
        $company = Company::findCompanyByName($event['company']);
        $companyEvents = Event::findCompanyEvents($company['name']);
        //var_dump($companyEvents);die;
        return $this->render('detail', ['event' => $event, 'company'=> $company, 'companyEvents'=>$companyEvents]);
    
    }

    public function actionSetLongLat() {

        $long = Yii::$app->request->post('longitude');
        $lat = Yii::$app->request->post('latitude');

        $cookies = Yii::$app->response->cookies;

        $cookies->add(new Cookie([
            'name' => 'longitude',
            'value' => $long,
        ]));
        $cookies->add(new Cookie([
            'name' => 'latitude',
            'value' => $lat,
        ]));
    }

    public function getZipLongLat() {
        if (Yii::$app->request->isPost) {
            $zip_code = Yii::$app->request->post('zipcode');
        } else if (Yii::$app->request->isGet) {
            $zip_code = Yii::$app->request->get('zipcode');
        } else {
            $ip = Yii::$app->request->userIP;
            $latitude = Yii::$app->ip2location->getLatitude($ip);
            $longitude = Yii::$app->ip2location->getLongitude($ip);
            $zip_code = Yii::$app->ip2location->getZIPCode($ip);
        }
        $longlat = GlobalFunction::getLongLatFromZip($zip_code);
    }

    public function getEventsWithDistance($zip_code, $keywords, $filters, $longitude, $latitude, $sort = 'Closest') {
        $current_date = new \MongoDB\BSON\UTCDateTime(strtotime(date('Y-m-d')) * 1000);
        $last_date = new \MongoDB\BSON\UTCDateTime(strtotime(date('Y-m-d', strtotime("+230 days"))) * 1000);

        if (isset($keywords) && sizeof($keywords) > 0) {
            if (sizeof($filters) > 0) {
                $keywords_params = ['OR', ['categories' => $keywords], ['sub_categories' => $keywords]];
                $matchParams = ['AND', $keywords_params, ['categories' => ['$all' => $filters]], ['date_end' => ['$gte' => $current_date]], ['date_end' => ['$lte' => $last_date]], ['locations.zip' => $zip_code]];
            } else {
                $keywordParams = ['OR', ['categories' => $keywords], ['sub_categories' => $keywords]];
                $matchParams = ['AND', $keywordParams, ['date_end' => ['$gte' => $current_date]], ['date_end' => ['$lte' => $last_date]], ['locations.zip' => $zip_code]];
            }
        } else if (isset($filters) && sizeof($filters) > 0) {
            if (sizeof($keywords) > 0) {
                $keywords_params = ['OR', ['categories' => $keywords], ['sub_categories' => $keywords]];
                $matchParams = ['AND', $keywords_params, ['categories' => ['$all' => $filters]], ['date_end' => ['$gte' => $current_date]], ['date_end' => ['$lte' => $last_date]], ['locations.zip' => $zip_code]];
            } else {
                $matchParams = ['AND', ['date_end' => ['$gte' => $current_date]], ['date_end' => ['$lte' => $last_date]], ['locations.zip' => $zip_code], ['categories' => ['$all' => $filters]]];
            }
        } else {
            $matchParams = ['AND', ['date_end' => ['$gte' => $current_date]], ['date_end' => ['$lte' => $last_date]], ['locations.zip' => $zip_code]];
        }
        $db = Event::getDb();
        $events = $db->getCollection('event')->aggregate([
                [
                '$geoNear' => [
                    "near" => [
                        "type" => "Point",
//                        "coordinates" => [74.329376, 31.582045]
                        "coordinates" => [floatval($longitude), floatval($latitude)]
                    ],
                    "maxDistance" => 50 * 1609,
                    "spherical" => true,
                    "distanceField" => "distance",
                    "distanceMultiplier" => 0.000621371
                ],
            ],
                ['$match' => $matchParams],
                ['$sort' => $sort === 'Soonest' ? ["event_id" => 1, "distance" => 1] : ["distance" => 1]]
                ], ['allowDiskUse' => true]);

        return $events;
    }
}
