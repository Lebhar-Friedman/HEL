<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace frontend\controllers;

use common\functions\GlobalFunctions;
use common\models\Alerts;
use Yii;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

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
        if ( Yii::$app->user->isGuest ) {
            throw new ForbiddenHttpException("You are not allowed to access this page.");
        }
        $alerts_list=GlobalFunctions::getCategoryList();
        $alerts = Alerts::find()->where(['user_id' => (string) Yii::$app->user->id ])->all();
        
        return $this->render("alerts",['selected_alerts' => $alerts]);
    }
    public function actionAddAlerts() {
        $alert = Yii::$app->request->post('alert');
        Alerts::addAlerts($alert);
    }
    public function actionDeleteAlert() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $alert = Yii::$app->request->post('alert');
        if(Alerts::delAlert($alert)){
            return ['msgType' => 'SUC', 'msg' => 'Alert successfully deleted'];
        }else{
            return ['msgType' => 'ERR', 'msg' => 'Unable to delete alert at this time'];
        }
    }
}
