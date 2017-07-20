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
        $zip_code = urldecode(Yii::$app->request->get('zipcode'));
        $query= Event::find()->where(['locations.zip'=> $zip_code]);
        $total_events=$query->count();
        $events=$query->all();
//        echo '<pre>';
//        print_r($events);
//        exit;
        return $this->render('result',['events'=> $events ,'zip_code'=> $zip_code, '$total_events'=> $total_events]);
    }

}
