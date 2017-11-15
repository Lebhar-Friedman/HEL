<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\controllers;

use backend\models\EventForm;
use common\functions\GlobalFunctions;
use common\models\Alerts;
use common\models\Categories;
use common\models\Event;
use common\models\Location;
use common\models\SubCategories;
use common\models\UnsavedEvent;
use common\models\User;
use components\GlobalFunction;
use Yii;
use yii\web\Controller;

/**
 * Description of CronController
 *
 * @author zeeshan
 */
class CronController extends Controller {

    public function actionImportEvents() {
        $results = EventForm::saveCSV('event.csv');
    }

    public function actionUpdateLocationid() {
        $request = Yii::$app->request;
        $locationID = $request->get('_id');
        $location = Location::findOne($locationID);
        if ($location) {
//            echo 'jaga v mil gae';
        } else {
            echo 'Location not found';
            return;
        }
        $query = \common\models\Event::find()->where(['locations._id' => new \MongoDB\BSON\ObjectID($locationID)]);
        $no_of_events = $query->count();
        $events = $query->all();
        echo 'No of events: ' . $no_of_events . '<br>';
        $updated = false;
        for ($i = 0; $i < $no_of_events; $i++) {
            $no_of_locations = sizeof($events[$i]['locations']);
            for ($j = 0; $j < $no_of_locations; $j++) {
                if ((string) $events[$i]['locations'][$j]['_id'] == $locationID) {
                    $locations = $events[$i]['locations'];
                    $locationObj = $locations[$j];
                    $locationObj['location_id'] = $location->location_id;
                    $locationObj['updated_at'] = new \MongoDB\BSON\UTCDateTime(round(microtime(true) * 1000));
                    $locations[$j] = $locationObj;
                    $events[$i]['locations'] = $locations;
                    $events[$i]->save();
                    echo 'New location_id is: ' . $location->location_id . ' of event: ' . (string) $events[$i]['_id'] . '<br>';
                    $updated = true;
                }
            }
        }
        if ($updated) {
            $locationid = \common\models\Counter::getAutoIncrementId('locationid');
            echo 'Latest location id: ' . $locationid;
        }
    }

    public function actionTemp() {
        $request = Yii::$app->request;
        $error_msg = $request->get('error');
        $unsaved = new UnsavedEvent();
//        $unsaved->attributes = $eventModel->attributes;
        $unsaved->error_msg = $error_msg;
        $unsaved->save();
    }

    public function actionTestLocations() {
        set_time_limit(3000);
        $i = 0;
        $request = Yii::$app->request;
        $event_id = $request->get('id');
//        $event_id = '59dca2f4a680916fbc3cfe73';
//        $event_id = '59b038f4b9f3c21ee504d5e3';
        $event = Event::find()->where(['_id' => $event_id])->one();
        $locations = Location::findAll(['_id' => Event::findEventLocationsIDs($event->_id)]);
        $location_ids = array();
        foreach ($locations as $single_location) {
            array_push($location_ids, (string) $single_location->_id);
        }
        foreach ($event['locations'] as $location) {
            if (Location::find($location['_id'])->count() > 0) {
                echo $i++ . ', ';
                if (!in_array((string) $location['_id'], $location_ids)) {
//                    echo "<br> " . (string) $location['_id'] . " Not found<br>";
//                    exit;
                    echo "<pre>";
                    echo"<br>not found in array<br>";
                    print_r($location);
                }
            } else {
//                echo (string) $location['_id'] . " Not found <br>";
                echo "<pre>";
                echo "<br>Not found in locations collection<br>";
                print_r($location);
                exit;
            }
        }
    }

    public function actionSetSlug() {
        $categories = Categories::find()->all();
        $sub_categories = SubCategories::find()->all();
        foreach ($categories as $category) {
            $slug = $category->name;
            $slug = strtolower(str_replace(' ', '-', $slug));
            $slug = preg_replace('/[^A-Za-z0-9\-]/', '', $slug);
            echo $slug . '<br>';
            $category->save();
        }
        foreach ($sub_categories as $sub) {
            $sub->save();
        }
    }

    public function actionGetCity() {
        set_time_limit(3000);
        $number_of_locations = Location::find()->count();
        $offset = 0;
        for ($i = 0; $i < $number_of_locations / 100; $i++) {
            $locations = Location::find()->offset($offset)->limit(100)->all();
        }
        foreach ($locations as $location) {
            $zipcode = $location->zip;
            $city = GlobalFunction::getCityFromZip($zipcode);
            echo $city['short_name'] . ', ';
        }
    }

    public function actionSendMailOfAlerts() {


//        var start = new Date();
//        start . setHours(0, 0, 0, 0);
//        $current_date = new \MongoDB\BSON\UTCDateTime(strtotime(date('Y-m-d')) * 1000);
//        $last_date = new \MongoDB\BSON\UTCDateTime(strtotime(date('Y-m-d')) * 1000);
//        $last_date = new \MongoDB\BSON\UTCDateTime(strtotime(date('Y-m-d', strtotime("+230 days"))) * 1000);
//        $last_date = new \MongoDB\BSON\UTCDateTime(strtotime(date('Y-m-d', strtotime("tomorrow") - 1)) * 1000);
//        print_r($current_date);
//        print_r($last_date);


        $alerts_objs = Alerts::find()->all();

        foreach ($alerts_objs as $single_user_alerts) {
            $user_id = $single_user_alerts['user_id'];
            $events_to_send = array();
            foreach ($single_user_alerts['alerts'] as $single_alert) {
                if ($single_alert['type'] === "exact_location") {
                    echo 'Alert on company location';
                    $events = $this->getEventsByLocation($single_alert['street'], $single_alert['city'], $single_alert['state'], $single_alert['zip_code']);
                    if (sizeof($events) > 0) {
                        $events_to_send[] = $events;
                    }
                } else {
                    $events = $this->getEventsWithDistance($single_alert['keywords'], $single_alert['filters'], $single_alert['longitude'], $single_alert['latitude'], $single_alert['sort']);
                    if (sizeof($events) > 0) {
                        $events_to_send[] = $events;
                    }
//                    echo "<pre>";
//                    print_r($events);
                }
            }
            $user = User::findOne($user_id);
            echo sizeof($events_to_send);
            if (isset($user) && sizeof($events_to_send) > 0) {
                echo "<pre>";
                print_r($events_to_send);
                $arguments = ['events' => $events_to_send, 'user_name' => $user->first_name];
                GlobalFunctions::sendEmail('upcoming-events', $user->email, 'Up-coming events ', $arguments);
            }
        }
    }

    public function getEventsByLocation($street, $city, $state, $zip) {
        $current_date = new \MongoDB\BSON\UTCDateTime(strtotime(date('Y-m-d')) * 1000);
        $last_date = new \MongoDB\BSON\UTCDateTime(strtotime(date('Y-m-d', strtotime("+1 days"))) * 1000);
        $query = Event::find();
        $events = $query->andWhere(['AND', ['locations.street' => $street], ['locations.city' => $city], ['locations.state' => $state], ['locations.zip' => $zip], ['created_at' => ['$gte' => $current_date]], ['created_at' => ['$lte' => $last_date]], ['is_post' => true]])->all();
        return $events;
    }

    public function getEventsWithDistance($keywords, $filters, $longitude, $latitude, $max_distance = 50, $min_distance = 0, $sort = 'Closest') {

        $current_date = new \MongoDB\BSON\UTCDateTime(strtotime(date('Y-m-d')) * 1000);
//        $last_date = new \MongoDB\BSON\UTCDateTime(strtotime(date('Y-m-d')) * 1000);
        $last_date = new \MongoDB\BSON\UTCDateTime(strtotime(date('Y-m-d', strtotime("+1 days"))) * 1000);
//        $last_date = new \MongoDB\BSON\UTCDateTime(strtotime(date('Y-m-d', strtotime("tomorrow") - 1)) * 1000);

        if (isset($keywords) && sizeof($keywords) > 0) {
            if (sizeof($filters) > 0) {
                $keywords_params = ['OR', ['categories' => $keywords], ['sub_categories' => $keywords]];
//                $matchParams = ['AND', $keywords_params, ['categories' => ['$all' => $filters]], ['date_end' => ['$gte' => $current_date]], ['date_end' => ['$lte' => $last_date]], ['is_post' => true]];
                $matchParams = ['AND', $keywords_params, ['categories' => ['$in' => $filters]], ['created-at' => ['$gte' => $current_date]], ['created_at' => ['$lte' => $last_date]], ['is_post' => true]];
            } else {
                $keywordParams = ['OR', ['categories' => $keywords], ['sub_categories' => $keywords]];
//                $matchParams = ['AND', $keywordParams, ['date_end' => ['$gte' => $current_date]], ['date_end' => ['$lte' => $last_date]], ['is_post' => true]];
                $matchParams = ['AND', $keywordParams, ['created_at' => ['$gte' => $current_date]], ['created_at' => ['$lte' => $last_date]], ['is_post' => true]];
            }
        } else if (isset($filters) && sizeof($filters) > 0) {
            if (sizeof($keywords) > 0) {
                $keywords_params = ['OR', ['categories' => $keywords], ['sub_categories' => $keywords]];
//                $matchParams = ['AND', $keywords_params, ['categories' => ['$all' => $filters]], ['date_end' => ['$gte' => $current_date]], ['date_end' => ['$lte' => $last_date]], ['is_post' => true]];
                $matchParams = ['AND', $keywords_params, ['categories' => ['$in' => $filters]], ['created_at' => ['$gte' => $current_date]], ['created_at' => ['$lte' => $last_date]], ['is_post' => true]];
            } else {
//                $matchParams = ['AND', ['date_end' => ['$gte' => $current_date]], ['date_end' => ['$lte' => $last_date]], ['categories' => ['$all' => $filters]], ['is_post' => true]];
                $matchParams = ['AND', ['created_at' => ['$gte' => $current_date]], ['created_at' => ['$lte' => $last_date]], ['categories' => ['$in' => $filters]], ['is_post' => true]];
            }
        } else {
//            $matchParams = ['AND', ['date_end' => ['$gte' => $current_date]], ['date_end' => ['$lte' => $last_date]], ['is_post' => true]];
            $matchParams = ['AND', ['created_at' => ['$gte' => $current_date]], ['created_at' => ['$lte' => $last_date]], ['is_post' => true]];
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

    public function actionResetLocationCase() {
        echo $memBefore = memory_get_usage() . '<br>';
        $locations = Location::find()->orderBy('location_id')->all();
        $no = count($locations);
        echo 'Total ' . $no . '<br>';
        $memAfter = memory_get_usage() . '<br>';
        echo "Array Memory : " . ($memAfter - $memBefore) . '<br><br>';
        for ($i = 0; $i < $no; $i++) {
//            $locations[$i]->street = strtoupper($locations[$i]->street);
//            $locations[$i]->city = strtoupper($locations[$i]->city);
//            $locations[$i]->state = strtolower($locations[$i]->state);

            $locations[$i]->street = ucwords(strtolower($locations[$i]->street));
            $locations[$i]->city = ucwords(strtolower($locations[$i]->city));
            $locations[$i]->state = strtoupper($locations[$i]->state);
            $locations[$i]->save();
            $memNow = memory_get_usage();
//            echo "$loc->location_id : $loc->street, $loc->city, $loc->state, $loc->zip (object Memory : " . ($memNow - $memAfter) . ")<br>";
            echo $locations[$i]->location_id . " : (object Memory : " . ($memNow - $memAfter) . ")<br>";
            $memAfter = $memNow;
            unset($locations[$i]);
        }
        echo "<br>total Memory : " . (memory_get_usage() - $memBefore);
        echo '<br>All done :)';
//        \xdebug_debug_zval('locations');
    }

}
