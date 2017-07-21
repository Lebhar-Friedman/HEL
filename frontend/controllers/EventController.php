<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace frontend\controllers;

use common\models\Event;
use Yii;
use yii\web\Controller;

class EventController extends Controller {

    public function actionIndex() {
        if (Yii::$app->request->get('zipcode') === NULL) {
            $ip = Yii::$app->request->userIP;
            $latitude = Yii::$app->ip2location->getLatitude($ip);
            $longitude = Yii::$app->ip2location->getLongitude($ip);
            $zip_code = Yii::$app->ip2location->getZIPCode($ip);
        } else {
            $zip_code = urldecode(Yii::$app->request->get('zipcode'));
        }

        $whereParams = ['locations.zip' => $zip_code];

        if (Yii::$app->request->isPost) {
            $zip_code = Yii::$app->request->post('zipcode');
            $keywords = Yii::$app->request->post('keywords');
            $sort_by = Yii::$app->request->post('sortBy');
            $whereParams = ['locations.zip' => $zip_code];
            if (sizeof($keywords) > 0) {
               $current_date= new \MongoDB\BSON\UTCDateTime(strtotime(date('Y-m-d')) * 1000);
                $whereParams = ['AND', ['categories' => $keywords], ['locations.zip' => $zip_code], ['date_end'=> ['$gte'=> $current_date]] ];
//                $whereParams = ['AND', ['categories' => $keywords], ['locations.zip' => $zip_code] ];
            }
            $query = Event::find()->andWhere($whereParams);
//            $total = $query->count();
//            $events = $query->all();
//            echo '<pre>';
//            print_r($total);
//            exit;
        }

        $query = Event::find()->andWhere($whereParams);
        $total_events = $query->count();
        $events = $query->all();
        return $this->render('result', ['events' => $events, 'zip_code' => $zip_code, 'total_events' => $total_events]);
    }

}
