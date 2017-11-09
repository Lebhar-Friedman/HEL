<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace frontend\controllers;

use common\functions\GlobalFunctions;
use common\models\Alerts;
use common\models\Categories;
use common\models\Company;
use common\models\Event;
use common\models\Location;
use common\models\SubCategories;
use components\GlobalFunction;
use Yii;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\Response;

class EventController extends Controller {

    public function actionIndex() {

        $longitude;
        $latitude;
        $session = Yii::$app->session;
        if ($session->has('zipcode') && !Yii::$app->user->isGuest) {

            $keywords = $session->get('keywords');
            $filters = $session->get('filters');
            $zip = $session->get('zipcode');
            $sort = $session->get('sort');
            $type = $session->get('type');

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
            if (Alerts::addAlerts(['zip_code' => $zip, 'keywords' => $keywords, 'filters' => $filters, 'sort' => $sort, 'type' => $type])) {
                Yii::$app->getSession()->setFlash('success', 'Alert has been added');
            } else {
                Yii::$app->getSession()->setFlash('error', 'Unable to save this alert');
            }
            $events_dist = $this->getEventsWithDistance($zip, $keywords, $filters, $longitude, $latitude, 20, 0, $sort);
            $total_events = sizeof($events_dist);

            return $this->render('index', ['events' => $events_dist, 'zip_code' => $zip, 'total_events' => $total_events, 'ret_keywords' => $keywords, 'ret_filters' => $filters, 'ret_sort' => $sort, 'longitude' => $longitude, 'latitude' => $latitude, 'alert_added' => TRUE]);
        }

        if (Yii::$app->request->get('zipcode') === NULL) {
            $events_dist = array();
            $city = Yii::$app->request->get('city');
            $keywords = Yii::$app->request->get('keywords');
            $categories = Yii::$app->request->get('categories');
            $services = Yii::$app->request->get('services');
            $filters = Yii::$app->request->get('filters');
            $sort_by = ucfirst(urldecode(Yii::$app->request->get('sortby')));
//            $zip_code = GlobalFunctions::getLatestSearchedZip();
            $zip_code = NULL;
            $params_keys = array();
            $categories_array = explode(' ', $categories);
            $services_array = explode(' ', $services);
            if (sizeof($categories_array) > 0 && $categories_array[0] !== '') {
                $params_keys = array_merge($params_keys, $categories_array);
            }if (sizeof($services_array) > 0 && $services_array[0] !== '') {
                $params_keys = array_merge($params_keys, $services_array);
            }
            $keywords = $params_keys;

            $longlat = GlobalFunction::getLongLat(null, $city);
            
            $title_content = "Free Health Services in $city, FL | Health Events Live";
            $events_dist = $this->getEventsWithDistance($zip_code, $keywords, $filters, $longlat['long'], $longlat['lat'], 20, 0, $sort_by);
            return $this->render('index', ['events' => $events_dist, 'zip_code' => $zip_code, 'total_events' => 0, 'ret_keywords' => $keywords, 'ret_filters' => $filters, 'ret_sort' => strtolower($sort_by), 'longitude' => $longlat['long'], 'latitude' => $longlat['lat']]);
        } else {
            $zip_code = urldecode(Yii::$app->request->get('zipcode'));
            $keywords = Yii::$app->request->get('keywords');
            $filters = Yii::$app->request->get('filters');
            $categories = Yii::$app->request->get('categories');
            $services = Yii::$app->request->get('services');
            $params_keys = array();
            $categories_array = explode(' ', $categories);
            $services_array = explode(' ', $services);
            $name_categories_array = array();
            $name_sub_array = array();
            if (sizeof($categories_array) > 0 && $categories_array[0] !== '') {
                foreach ($categories_array as $cat) {
                    $category = Categories::find()->where(['category_slug' => $cat])->one();
                    if (isset($category->name)) {
                        array_push($name_categories_array, $category->name);
                    }
                }
                $params_keys = array_merge($params_keys, $name_categories_array);
            }if (sizeof($services_array) > 0 && $services_array[0] !== '') {
                foreach ($services_array as $service) {
                    $sub_category = SubCategories::find()->where(['sub_category_slug' => $service])->one();
                    if (isset($sub_category->name)) {
                        array_push($name_sub_array, $sub_category->name);
                    }
                }
                $params_keys = array_merge($params_keys, $name_sub_array);
            }
            $keywords = $params_keys;
            $sort_by = ucfirst(urldecode(Yii::$app->request->get('sortby')));

            $longlat = GlobalFunction::getLongLatFromZip($zip_code);
            $latitude = $longlat['lat'];
            $longitude = $longlat['long'];
            GlobalFunctions::saveLatestSearchedZip($zip_code);

            $events_dist = $this->getEventsWithDistance($zip_code, $keywords, $filters, $longitude, $latitude, 20, 0, $sort_by);
            $total_events = sizeof($events_dist);


            return $this->render('index', ['events' => $events_dist, 'zip_code' => $zip_code, 'total_events' => $total_events, 'ret_keywords' => $keywords, 'ret_filters' => $filters, 'ret_sort' => strtolower($sort_by), 'longitude' => $longitude, 'latitude' => $latitude]);
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
            $events_dist = $this->getEventsWithDistance($z_lng_lat['zip_code'], $keywords, $filters, $z_lng_lat['longitude'], $z_lng_lat['latitude'], 50, 0, $sort_by);
            $total_events = sizeof($events_dist);

            return $this->render('index', ['events' => $events_dist, 'zip_code' => $z_lng_lat['zip_code'], 'total_events' => $total_events, 'ret_keywords' => $keywords, 'ret_filters' => $filters, 'ret_sort' => $sort_by, 'longitude' => $z_lng_lat['longitude'], 'latitude' => $z_lng_lat['latitude']]);
        }
        $z_lng_lat = $this->getZipLongLat();
        $longitude = $z_lng_lat['longitude'];
        $latitude = $z_lng_lat['latitude'];
        $events = $this->getEventsWithDistance($zip_code, null, null, $longitude, $latitude, 20, 0);
        $total_events = sizeof($events);

        return $this->render('index', ['events' => $events, 'zip_code' => $zip_code, 'total_events' => $total_events, 'longitude' => $longitude, 'latitude' => $latitude]);
    }

    public function actionMoreEvents() {

        $zip_code = urldecode(Yii::$app->request->get('zipcode'));
        $keywords = Yii::$app->request->get('keywords');
        $filters = Yii::$app->request->get('filters');

        $lng_lat = GlobalFunction::getLongLatFromZip($zip_code);

        $events = $this->getEventsWithDistance($zip_code, $keywords, $filters, $lng_lat['long'], $lng_lat['lat'], 200, 21);
        $events_with_score = array();
        $nearest_locations = array();
        foreach ($events as $event) {
            $current_date = new \MongoDB\BSON\UTCDateTime(strtotime(date('Y-m-d')) * 1000);
            $current_date = GlobalFunction::getDate('Y-m-d', $current_date);
            $end_date = GlobalFunction::getDate('Y-m-d', $event['date_end']);
            $diff = GlobalFunction::dateDiff($end_date, $current_date, false);

            $score['score'] = round($event['distance'] * $diff, 2);
            $nearest_locations = GlobalFunction::locationsInRadius($lng_lat['lat'], $lng_lat['long'], $event['locations'], 200);
            $event['locations'] = $nearest_locations;

            $events_with_score[] = array_merge($event, $score);
        }
        if (sizeof($events_with_score) > 0) {
            usort($events_with_score, function ($item1, $item2) {
                if (abs(($item1['score'] - $item2['score']) / $item2['score']) < 0.00001)
                    return 0;
                return $item1['score'] < $item2['score'] ? -1 : 1;
            });
        }
        return $this->renderAjax('_more-events', ['more_events' => $events_with_score, 'zipcode' => $zip_code, 'lng_lat' => $lng_lat]);
    }

    public function actionDetail() {
        $query = Event::find();
        $eid = urldecode(Yii::$app->request->get('eid'));
        $store_number = urldecode(Yii::$app->request->get('store'));
        $alert_added = false;
        $session = Yii::$app->session;

        if (urldecode(Yii::$app->request->get('alert_added')) == 1 && $session->has('zipcode')) {

            $alert_added = urldecode(Yii::$app->request->get('alert_added'));
            $keywords = $session->get('keywords');
            $filters = $session->get('filters');
            $zip = $session->get('zipcode');
            $sort = $session->get('sort');
            $type = $session->get('type');

            if ($session->has('event_id')) {

                $event_id = $session->get('event_id');
                $street = $session->get('street');
                $city = $session->get('city');
                $state = $session->get('state');
                $store_number = $session->get('store_number');

                $session->remove('event_id');
                $session->remove('street');
                $session->remove('city');
                $session->remove('state');
                $session->remove('store_number');

                if (Alerts::addAlerts(['zip_code' => $zip, 'keywords' => $keywords, 'filters' => $filters, 'type' => $type, 'sort' => $sort, 'street' => $street, 'city' => $city, 'state' => $state, 'store_number' => $store_number])) {
                    Yii::$app->getSession()->setFlash('success', 'Alert has been added');
                } else {
                    Yii::$app->getSession()->setFlash('error', 'Unable to save this alert');
                }
            } else {

                if (Alerts::addAlerts(['zip_code' => $zip, 'keywords' => $keywords, 'filters' => $filters, 'sort' => $sort, 'type' => $type])) {
                    Yii::$app->getSession()->setFlash('success', 'Alert has been added');
                } else {
                    Yii::$app->getSession()->setFlash('error', 'Unable to save this alert');
                }
            }

            $session->remove('zipcode');
            $session->remove('keywords');
            $session->remove('filters');
            $session->remove('sort');
            $session->remove('type');
        }
        $error = '';
        if ($eid !== '') {
            $query->andWhere(['_id' => $eid]);
        } else {
            $error = 'Record not found!';
        }
        $event = $query->one();

        if ($store_number === '') {
            $event_location = $event['locations'][0];
            $company_number = $event['locations'][0]['company'];
        } else {
            $event_location = Location::findLocationByStoreNumber($store_number);
            $company_number = $event_location->company;
        }

        $company = Company::findCompanyByNumber($event['company']);

        $companyEvents = Event::findEventsByStore($event_location['location_id'], $eid);

        $number_of_categories_in_title = 0;
        $categories_in_title = '';
        foreach ($event['categories'] as $category) {
            $number_of_categories_in_title += 1;
            if ($number_of_categories_in_title > 2)
                break;
            $categories_in_title = $categories_in_title . $category . ', ';
        }
        if (isset($_GET['zipcode'])) {
            $zipcode = $_GET['zipcode'];
        } else {
            $zipcode = isset($event['locations'][0]['zip']) ? $event['locations'][0]['zip'] : '';
        }
        if (isset($_GET['store']) && !empty($_GET['store']) && (!isset($_GET['zipcode']) || empty($_GET['zipcode']) )) {
            $zipcode = $event_location['zip'];
        }
        $company_name_in_title = isset($company['name']) ? $company['name'] : '';
        $title_content = $categories_in_title .$company_name_in_title. ', ' . $zipcode;
//        echo $title_content;exit;
        return $this->render('detail', ['event' => $event, 'company' => $company, 'companyEvents' => $companyEvents, 'error' => $error, 'alert_added' => $alert_added, 'event_location' => $event_location, 'title_content' => $title_content]);
    }

    public function actionDirectory() {
        $current_date = new \MongoDB\BSON\UTCDateTime(strtotime(date('Y-m-d')) * 1000);
        $query = Event::find();
        $events = $query->where(['AND', ['date_start' => ['$gte' => $current_date]], ['is_post' => true]])->all();
        return $this->render('directory', ['events' => $events]);
    }

    public function actionDisplayMap() {
        $events = Yii::$app->request->post('events');
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
        }
//        } else if ($coordinates = GlobalFunctions::getCookiesOfLngLat()) {
//            $zip_code = GlobalFunction::getZipFromLongLat($coordinates['longitude'], $coordinates['latitude']);
//            $zip_code == '' ? $zip_code = '' : '';
//            $session->set('lng', $coordinates['longitude']);
//            $session->set('lat', $coordinates['latitude']);
//            return ['zip_code' => $zip_code, 'longitude' => $coordinates['longitude'], 'latitude' => $coordinates['latitude']];
//        } else {
//            $ip = Yii::$app->request->userIP;
//            $latitude = Yii::$app->ip2location->getLatitude($ip);
//            $longitude = Yii::$app->ip2location->getLongitude($ip);
//            $zip_code = Yii::$app->ip2location->getZIPCode($ip);
//
//            $session->set('lng', $longitude);
//            $session->set('lat', $latitude);
//            return ['zip_code' => $zip_code, 'longitude' => $longitude, 'latitude' => $latitude];
//        }
        $longlat = GlobalFunction::getLongLatFromZip($zip_code);
        $session->set('lng', $longlat['long']);
        $session->set('lat', $longlat['lat']);
        return ['zip_code' => $zip_code, 'longitude' => $longlat['long'], 'latitude' => $longlat['lat']];
    }

    public function getEventsWithDistance($zip_code, $keywords, $filters, $longitude, $latitude, $max_distance = 20, $min_distance = 0, $sort = 'Closest', $company = null) {
        $current_date = new \MongoDB\BSON\UTCDateTime(strtotime(date('Y-m-d')) * 1000);
        $last_date = new \MongoDB\BSON\UTCDateTime(strtotime(date('Y-m-d', strtotime("+30 days"))) * 1000);

        if (isset($keywords) && sizeof($keywords) > 0) {
            if (sizeof($filters) > 0) {
                $keywords_params = ['OR', ['categories' => $keywords], ['sub_categories' => $keywords]];
                $matchParams = ['AND', $keywords_params, ['categories' => ['$in' => $filters]], ['date_end' => ['$gte' => $current_date]], ['is_post' => true]];
            } else {
                $keywordParams = ['OR', ['categories' => $keywords], ['sub_categories' => $keywords]];
                $matchParams = ['AND', $keywordParams, ['date_end' => ['$gte' => $current_date]], ['is_post' => true]];
            }
        } else if (isset($filters) && sizeof($filters) > 0) {
            if (sizeof($keywords) > 0) {
                $keywords_params = ['OR', ['categories' => $keywords], ['sub_categories' => $keywords]];
                $matchParams = ['AND', $keywords_params, ['categories' => ['$in' => $filters]], ['date_end' => ['$gte' => $current_date]], ['is_post' => true]];
            } else {
                $matchParams = ['AND', ['date_end' => ['$gte' => $current_date]], ['categories' => ['$in' => $filters]], ['is_post' => true]];
            }
        } else {
            $matchParams = ['AND', ['date_end' => ['$gte' => $current_date]], ['is_post' => true]];
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
                ['$sort' => $sort === 'Soonest' ? ["date_start" => 1, "distance" => 1] : ["distance" => 1]]
                ], ['allowDiskUse' => true]);

        return $events;
    }

    public function actionGetCity() {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $zip = Yii::$app->request->post('zipcode');
        $zip = strval(intval($zip));
        $city = GlobalFunction::getCityFromZip($zip);
        return ['city' => str_replace(' ', '-', $city)];
//        return $city['short_name'];
    }

}
