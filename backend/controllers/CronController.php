<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\controllers;

use common\functions\GlobalFunctions;
use common\models\Alerts;
use common\models\Event;
use common\models\User;
use yii\web\Controller;

/**
 * Description of CronController
 *
 * @author zeeshan
 */
class CronController extends Controller {

    public function actionSendMailOfAlerts() {
        
        $alerts_objs = Alerts::find()->all();

        foreach ($alerts_objs as $single_alert_obj) {
            $user_id = $single_alert_obj['user_id'];
            $alerts_array = $single_alert_obj['alerts'];

            $events = $this->getEventsWithDistance($alerts_array, $single_alert_obj['location']['longitude'], $single_alert_obj['location']['latitude']);
            echo "<pre>";
            print_r(sizeof($events));
            if (sizeof($events) > 0) {
                $user = User::findOne($user_id);
                if ( isset($user)) {
                    $arguments =['events' => $events, 'user_name'=> $user->first_name];
                    GlobalFunctions::sendEmail('upcoming-events', $user->email , 'Up-coming events ', $arguments);
                }
            }
        }
    }

    public function getEventsWithDistance($keywords, $filters, $longitude, $latitude, $max_distance = 50, $min_distance = 0, $sort = 'Closest') {
        $current_date = new \MongoDB\BSON\UTCDateTime(strtotime(date('Y-m-d')) * 1000);
        $last_date = new \MongoDB\BSON\UTCDateTime(strtotime(date('Y-m-d', strtotime("+230 days"))) * 1000);

        if (isset($keywords) && sizeof($keywords) > 0) {
            if (sizeof($filters) > 0) {
                $keywords_params = ['OR', ['categories' => $keywords], ['sub_categories' => $keywords]];
                $matchParams = ['AND', $keywords_params, ['categories' => ['$all' => $filters]], ['date_end' => ['$gte' => $current_date]], ['date_end' => ['$lte' => $last_date]], ['is_post' => true]];
            } else {
                $keywordParams = ['OR', ['categories' => $keywords], ['sub_categories' => $keywords]];
                $matchParams = ['AND', $keywordParams, ['date_end' => ['$gte' => $current_date]], ['date_end' => ['$lte' => $last_date]], ['is_post' => true]];
            }
        } else if (isset($filters) && sizeof($filters) > 0) {
            if (sizeof($keywords) > 0) {
                $keywords_params = ['OR', ['categories' => $keywords], ['sub_categories' => $keywords]];
                $matchParams = ['AND', $keywords_params, ['categories' => ['$all' => $filters]], ['date_end' => ['$gte' => $current_date]], ['date_end' => ['$lte' => $last_date]], ['is_post' => true]];
            } else {
                $matchParams = ['AND', ['date_end' => ['$gte' => $current_date]], ['date_end' => ['$lte' => $last_date]], ['categories' => ['$all' => $filters]], ['is_post' => true]];
            }
        } else {
            $matchParams = ['AND', ['date_end' => ['$gte' => $current_date]], ['date_end' => ['$lte' => $last_date]], ['is_post' => true]];
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
