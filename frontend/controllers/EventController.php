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
            $ip='103.7.78.171';
            $latitude = Yii::$app->ip2location->getLatitude($ip);
            $longitude = Yii::$app->ip2location->getLongitude($ip);
            $zip_code = Yii::$app->ip2location->getZIPCode($ip);
            
        } else {
            $zip_code = urldecode(Yii::$app->request->get('zipcode'));
            $longlat=GlobalFunction::getLongLatFromZip($zip_code);
            $latitude=$longlat['lat'];
            $longitude=$longlat['long'];
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
            $keywords_params=[];
            if (sizeof($keywords) > 0) {
//            $whereParams = ['AND', ['OR',['categories' => $keywords, 'sub_categories' => $keywords ]], ['locations.zip' => $zip_code], ['date_end'=> ['$gte'=> $current_date]] ];
                $query = $query->andWhere(['OR', ['categories' => $keywords], ['sub_categories' => $keywords]]);
                $keywords_params = ['OR', ['categories' => $keywords], ['sub_categories' => $keywords] ];
            }
            if ($filters !== null) { 
               $query = $query->andWhere(['AND', ['categories' => $filters]]);    
               $keywords_params=['AND',$keywords_params,['categories' => $filters]];
            }
            $total_events = $query->count();
            $events = $query->all();
//            echo"<pre>";
//            print_r($keywords_params);
//            exit;
            $events_dist=$this->getEventsWithDistance($zip_code,$keywords_params);
            return $this->render('result', ['events' => $events_dist, 'zip_code' => $zip_code, 'total_events' => $total_events, 'ret_keywords' => $keywords, 'ret_filters' => $filters]);
        }

//        $query = Event::find()->andWhere($whereParams);
        $query = Event::find()->andWhere($whereParams);
        $total_events = $query->count();
        $events = $query->all();
        return $this->render('result', ['events' => $events, 'zip_code' => $zip_code, 'total_events' => $total_events]);
    }
    
    public function actionDetail(){
        $query = Event::find();
        $eid = urldecode(Yii::$app->request->get('eid'));
        if($eid !== ''){
            $query->andWhere(['=','_id', $eid]);
        }        
        $count = $query->count();  
        
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => (10)]);
        $event = $query->offset($pagination->offset)->limit($pagination->limit)->orderBy(['updated_at' => SORT_DESC])->all();
       // var_dump($event);die;
        return $this->render('index', ['event' => $event, 'pagination' => $pagination, 'total' => $count]);
    
    }

    public function getEventsWithDistance($zip_code,$keywords_params) {
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
                ['$match' => ['locations.zip' => $zip_code]]
//                ['$match' => $keywords_params]
        ]);
        
        return $events;
    }

}
