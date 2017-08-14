<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace frontend\controllers;

use common\functions\GlobalFunctions;
use common\models\Alerts;
use common\models\Company;
use common\models\Location;
use common\models\Event;
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
        $session = Yii::$app->session;
        if ($session->has('zipcode') && !Yii::$app->user->isGuest) {
            
            $keywords = $session->get('keywords');
            $filters = $session->get('filters');
            $zip = $session->get('zip');
            $sort = $session->get('sort');
            
            $session->remove('zipcode');
            $session->remove('keywords');
            $session->remove('filters');
            $session->remove('sort');
            
            if (empty($zip)) {
                $lng_lat = GlobalFunctions::getCookiesOfLngLat();
                if ($lng_lat) {
                    $longitude = $lng_lat['longitude'];
                    $latitude = $lng_lat['latitude'];
                } else {
                    $ip = Yii::$app->request->userIP;
                    $latitude = Yii::$app->ip2location->getLatitude($ip);
                    $longitude = Yii::$app->ip2location->getLongitude($ip);
                }
            } else {
                $lng_lat = GlobalFunction::getLongLatFromZip($zip);
                $longitude = $lng_lat['long'];
                $latitude = $lng_lat['lat'];
            }
            if (Alerts::addAlerts(['zip_code' => $zip, 'keywords' => $keywords, 'filters' => $filters, 'sort' => $sort])) {
                Yii::$app->getSession()->setFlash('success', 'Alert has been added');
            }else{
                Yii::$app->getSession()->setFlash('error', 'Unable to save this alert');
            }
            $events_dist = $this->getEventsWithDistance($zip, $keywords, $filters, $longitude, $latitude, 50, 0, $sort);
            $total_events = sizeof($events_dist);

            return $this->render('index', ['events' => $events_dist, 'zip_code' => $zip, 'total_events' => $total_events, 'ret_keywords' => $keywords, 'ret_filters' => $filters, 'ret_sort' => $sort, 'longitude' => $longitude, 'latitude' => $latitude, 'alert_added' => TRUE]);
        }

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
            $z_lng_lat = $this->getZipLongLat();
//            echo '<pre>';
//            print_r($z_lng_lat);exit;
//            $events_dist = $this->getEventsWithDistance($zip_code, $keywords, $filters, $longitude, $latitude,50,0, $sort_by);
            $events_dist = $this->getEventsWithDistance($z_lng_lat['zip_code'], $keywords, $filters, $z_lng_lat['longitude'], $z_lng_lat['latitude'], 50, 0, $sort_by);
            $total_events = sizeof($events_dist);

//            return $this->render('index', ['events' => $events_dist, 'zip_code' => $zip_code, 'total_events' => $total_events, 'ret_keywords' => $keywords, 'ret_filters' => $filters, 'ret_sort' => $sort_by, 'longitude' => $z_lng_lat['longitude'], 'latitude' => $z_lng_lat['latitude']]);
            return $this->render('index', ['events' => $events_dist, 'zip_code' => $z_lng_lat['zip_code'], 'total_events' => $total_events, 'ret_keywords' => $keywords, 'ret_filters' => $filters, 'ret_sort' => $sort_by, 'longitude' => $z_lng_lat['longitude'], 'latitude' => $z_lng_lat['latitude']]);
        }
        $z_lng_lat = $this->getZipLongLat();
        $longitude = $z_lng_lat['longitude'];
        $latitude = $z_lng_lat['latitude'];
        $events = $this->getEventsWithDistance($zip_code, null, null, $longitude, $latitude, 50, 0);
        $total_events = sizeof($events);

        return $this->render('index', ['events' => $events, 'zip_code' => $zip_code, 'total_events' => $total_events, 'longitude' => $longitude, 'latitude' => $latitude]);
    }

    public function actionMoreEvents() {
        $z_lng_lat = $this->getZipLongLat();
        $events = $this->getEventsWithDistance($z_lng_lat['zip_code'], null, null, $z_lng_lat['longitude'], $z_lng_lat['latitude'], 200, 50);
//        $updated_events[];
//        foreach ($events as $event){
//            $temp = $event;
//        }
        return $this->renderAjax('_more-events', ['more_events' => $events]);
    }

    public function actionDetail() {
        $query = Event::find();
        $eid = urldecode(Yii::$app->request->get('eid'));
        $store_number = urldecode(Yii::$app->request->get('store_number'));
        $error='';
        if ($eid !== '') {
            $query->andWhere(['_id' => $eid]);
        }else{
            $error = 'Record not found!';
        }
        $event = $query->one();
        
        if($store_number===''){
            $company_number = $event['locations'][0]['company'];
        }else{
            $company_number = Location::findCompanyByStoreNumber($store_number)['company'];
        }
            
        $company = Company::findCompanyByNumber($company_number);
        
        $companyEvents = Event::findCompanyEventsByNumber($company['company_number'],$eid);
        $z_lng_lat = $this->getZipLongLat();
        
        return $this->render('detail', ['event' => $event, 'company' => $company, 'companyEvents' => $companyEvents, 'longitude' => $z_lng_lat['longitude'], 'latitude' => $z_lng_lat['latitude'],'error'=>$error]);
    }

    public function actionDirectory() {
        $current_date = new \MongoDB\BSON\UTCDateTime(strtotime(date('Y-m-d')) * 1000);
        $query = Event::find();
        $events = $query->where(['AND', ['date_start' => ['$gte' => $current_date]], ['is_post' => true]])->all();
        return $this->render('directory', ['events' => $events]);
    }

    public function actionDisplayMap() {
        $events = Yii::$app->request->post('events');
//        $events = json_decode($events);
//        echo '<pre>';
//        print_r($events);        exit();
        return $this->renderAjax('_map-modal', ['events' => $events]);
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

    public static function getZipLongLat() {
        $session = Yii::$app->session;

        if (Yii::$app->request->isPost && !empty(Yii::$app->request->post('zipcode'))) {
            $zip_code = Yii::$app->request->post('zipcode');
        } else if (Yii::$app->request->isGet && !empty(Yii::$app->request->get('zipcode'))) {
            $zip_code = Yii::$app->request->get('zipcode');
        } else if ($coordinates = GlobalFunctions::getCookiesOfLngLat()) {
            $zip_code = GlobalFunction::getZipFromLongLat($coordinates['longitude'], $coordinates['latitude']);
            $zip_code == '' ? $zip_code = '' : '';
            $session->set('lng', $coordinates['longitude']);
            $session->set('lat', $coordinates['latitude']);
            return ['zip_code' => $zip_code, 'longitude' => $coordinates['longitude'], 'latitude' => $coordinates['latitude']];
        } else {
            $ip = Yii::$app->request->userIP;
            $latitude = Yii::$app->ip2location->getLatitude($ip);
            $longitude = Yii::$app->ip2location->getLongitude($ip);
            $zip_code = Yii::$app->ip2location->getZIPCode($ip);

            $session->set('lng', $longitude);
            $session->set('lat', $latitude);
            return ['zip_code' => $zip_code, 'longitude' => $longitude, 'latitude' => $latitude];
        }
        $longlat = GlobalFunction::getLongLatFromZip($zip_code);
        $session->set('lng', $longlat['long']);
        $session->set('lat', $longlat['lat']);
        return ['zip_code' => $zip_code, 'longitude' => $longlat['long'], 'latitude' => $longlat['lat']];
    }

    public function getEventsWithDistance($zip_code, $keywords, $filters, $longitude, $latitude, $max_distance = 50, $min_distance = 0, $sort = 'Closest', $company = null) {
        $current_date = new \MongoDB\BSON\UTCDateTime(strtotime(date('Y-m-d')) * 1000);
        $last_date = new \MongoDB\BSON\UTCDateTime(strtotime(date('Y-m-d', strtotime("+30 days"))) * 1000);

        if (isset($keywords) && sizeof($keywords) > 0) {
            if (sizeof($filters) > 0) {
                $keywords_params = ['OR', ['categories' => $keywords], ['sub_categories' => $keywords]];
//                $matchParams = ['AND', $keywords_params, ['categories' => ['$all' => $filters]], ['date_end' => ['$gte' => $current_date]], ['date_end' => ['$lte' => $last_date]], ['is_post' => true]];
                $matchParams = ['AND', $keywords_params, ['categories' => ['$in' => $filters]], ['date_start' => ['$gte' => $current_date]], ['date_start' => ['$lte' => $last_date]], ['is_post' => true]];
            } else {
                $keywordParams = ['OR', ['categories' => $keywords], ['sub_categories' => $keywords]];
                $matchParams = ['AND', $keywordParams, ['date_start' => ['$gte' => $current_date]], ['date_start' => ['$lte' => $last_date]], ['is_post' => true]];
            }
        } else if (isset($filters) && sizeof($filters) > 0) {
            if (sizeof($keywords) > 0) {
                $keywords_params = ['OR', ['categories' => $keywords], ['sub_categories' => $keywords]];
//                $matchParams = ['AND', $keywords_params, ['categories' => ['$all' => $filters]], ['date_end' => ['$gte' => $current_date]], ['date_end' => ['$lte' => $last_date]], ['is_post' => true]];
                $matchParams = ['AND', $keywords_params, ['categories' => ['$in' => $filters]], ['date_start' => ['$gte' => $current_date]], ['date_start' => ['$lte' => $last_date]], ['is_post' => true]];
            } else {
//                $matchParams = ['AND', ['date_end' => ['$gte' => $current_date]], ['date_end' => ['$lte' => $last_date]], ['categories' => ['$all' => $filters]], ['is_post' => true]];
                $matchParams = ['AND', ['date_start' => ['$gte' => $current_date]], ['date_start' => ['$lte' => $last_date]], ['categories' => ['$in' => $filters]], ['is_post' => true]];
            }
        } else {
            $matchParams = ['AND', ['date_start' => ['$gte' => $current_date]], ['date_start' => ['$lte' => $last_date]], ['is_post' => true]];
        }
        if (!empty($company)) {
            array_push($matchParams, ['company' => $company]);
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
                    "maxDistance" => intval($max_distance) * 1609,
                    "minDistance" => intval($min_distance) * 1609,
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
