<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace frontend\controllers;

use common\models\Event;
use components\GlobalFunction;
use Yii;
use yii\web\Controller;

class EventController extends Controller {

    public function actionIndex() {
        if (Yii::$app->request->get('zipcode') === NULL) {
            $ip = Yii::$app->request->userIP;
            $ip = '103.7.78.171';
            $latitude = Yii::$app->ip2location->getLatitude($ip);
            $longitude = Yii::$app->ip2location->getLongitude($ip);
            $zip_code = Yii::$app->ip2location->getZIPCode($ip);
        } else {
            $zip_code = urldecode(Yii::$app->request->get('zipcode'));
            $longlat = GlobalFunction::getLongLatFromZip($zip_code);
            $latitude = $longlat['lat'];
            $longitude = $longlat['long'];
        }

//        $whereParams = ['locations.zip' => $zip_code];

        $current_date = new \MongoDB\BSON\UTCDateTime(strtotime(date('Y-m-d')) * 1000);
        $whereParams = ['AND', ['locations.zip' => $zip_code], ['date_end' => ['$gte' => $current_date]]];
        if (Yii::$app->request->isPost) {
            $zip_code = Yii::$app->request->post('zipcode');
            $keywords = Yii::$app->request->post('keywords');
            $sort_by = Yii::$app->request->post('sortBy');
            $filters = Yii::$app->request->post('filters');
            
            $whereParams = ['AND', ['locations.zip' => $zip_code], ['date_end' => ['$gte' => $current_date]], ["locations.geometry" => ['$geoWithin' => ['$centerSphere' => [[74.329376, 31.582045], 50 / 3963.2]]]]];

            $query = Event::find()->andWhere($whereParams);
            $keywords_params = [];
            if (sizeof($keywords) > 0) {
//            $whereParams = ['AND', ['OR',['categories' => $keywords, 'sub_categories' => $keywords ]], ['locations.zip' => $zip_code], ['date_end'=> ['$gte'=> $current_date]] ];
                $query = $query->andWhere(['OR', ['categories' => $keywords], ['sub_categories' => $keywords]]);
                $keywords_params = ['OR', ['categories' => $keywords], ['sub_categories' => $keywords]];
            }
            if ($filters !== null) {
                $query = $query->andWhere(['AND', ['categories' => $filters]]);
                if (sizeof($keywords) > 0) {
                    $keywords_params = ['AND', $keywords_params, ['categories' => ['$all' => $filters]]];
                } else {
//                    $keywords_params =['AND',['categories'=>$filters] ];
//                    favouriteFoods: { "$in" : ["sushi"]} 
                    $keywords_params = ['categories' => ['$all' => $filters]];
                }
            }
            $total_events = $query->count();
            $events = $query->all();
            $events_dist = $this->getEventsWithDistance($zip_code, $keywords, $filters,$sort_by);
            return $this->render('result', ['events' => $events_dist, 'zip_code' => $zip_code, 'total_events' => $total_events, 'ret_keywords' => $keywords, 'ret_filters' => $filters]);
        }

//        $query = Event::find()->andWhere($whereParams);
        $query = Event::find()->andWhere($whereParams);
        $total_events = $query->count();
        $events = $query->all();
        return $this->render('result', ['events' => $events, 'zip_code' => $zip_code, 'total_events' => $total_events]);
    }

    public function getEventsWithDistance($zip_code, $keywords, $filters, $sort='Closest') {
        $current_date = new \MongoDB\BSON\UTCDateTime(strtotime(date('Y-m-d')) * 1000);
        $last_date = new \MongoDB\BSON\UTCDateTime(strtotime(date('Y-m-d', strtotime("+230 days"))) * 1000);

        if (sizeof($keywords) > 0) {
            if (sizeof($filters) > 0) {
                $keywords_params = ['OR', ['categories' => $keywords], ['sub_categories' => $keywords]];
                $matchParams = ['AND', $keywords_params, ['categories' => ['$all' => $filters]], ['date_end' => ['$gte' => $current_date]], ['date_end' => ['$lte' => $last_date]], ['locations.zip' => $zip_code]];
            } else {
                $keywordParams = ['OR', ['categories' => $keywords], ['sub_categories' => $keywords]];
                $matchParams = ['AND', $keywordParams, ['date_end' => ['$gte' => $current_date]], ['date_end' => ['$lte' => $last_date]], ['locations.zip' => $zip_code]];
            }
        } else if (sizeof($filters) > 0) {
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
                        "coordinates" => [74.329376, 31.582045]
                    ],
                    "maxDistance" => 41 * 1609,
                    "spherical" => true,
                    "distanceField" => "distance",
                    "distanceMultiplier" => 0.000621371
                ],
            ],
                ['$match' => $matchParams],
                
                [ '$sort'=> $sort === 'Soonest'? [ "event_id"=> 1, "distance"=> 1 ] : ["distance"=> 1 ] ]
        ]);

        return $events;
    }

}
