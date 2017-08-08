<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace frontend\controllers;

use common\functions\GlobalFunctions;
use yii\web\Controller;

/**
 * Description of UserController
 *
 * @author zeeshan
 */
class UserController extends Controller{
    public function actionIndex() {
        
    }
    
    public function actionProfile() {
        return $this->render("profile");
    }
    
    public function actionAlerts() {
        $keywords=GlobalFunctions::getKeywords();
        $alerts_list= array();
        foreach ($keywords as $keyword){
            array_push($alerts_list, $keyword['text']);
        }
        return $this->render("alerts",['alerts_list' => $alerts_list]);
    }
}
