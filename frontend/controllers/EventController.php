<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace frontend\controllers;

use common\models\Event;
use yii\web\Controller;

/**
 * Description of EventController
 *
 * @author zeeshan
 */
class EventController extends Controller {

    public function actionIndex() {
        $query= Event::find();
    }

}
