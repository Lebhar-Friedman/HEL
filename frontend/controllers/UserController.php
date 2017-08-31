<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace frontend\controllers;

use common\functions\GlobalFunctions;
use common\models\Alerts;
use common\models\Company;
use common\models\Event;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\BaseUrl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use const IMG_URL;

/**
 * Description of UserController
 *
 * @author zeeshan
 */
class UserController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex() {
        
    }

    public function actionProfile() {
        $eventIds = $logoLinks = $events = [];
        $userEvents = !empty(\Yii::$app->user->identity->saved_events) ? \Yii::$app->user->identity->saved_events : [];
        foreach ($userEvents as $e) {
            $eventIds[] = $e['event_id'];
        }
        $events = Event::findAll(['_id' => $eventIds]);

        foreach ($events as $event) {
            $company = Company::findOne(['name' => $event->company]);
            if (count($company) > 0 && !empty($company->logo)) {
                $logoLinks[$event->event_id] = IMG_URL . $company['logo'];
            } else {
                $logoLinks[$event->event_id] = BaseUrl::base() . '/images/upload-logo.png';
            }
        }

        return $this->render("profile", ['events' => $events, 'companyLogo' => $logoLinks]);
    }

    public function actionAlerts() {
        if (Yii::$app->user->isGuest) {
            throw new ForbiddenHttpException("You are not allowed to access this page.");
        }
        $alerts_list = GlobalFunctions::getCategoryList();
        $alerts = Alerts::findOne(['user_id' => (string) Yii::$app->user->id]);

        return $this->render("alerts", ['selected_alerts' => $alerts]);
    }

    public function actionAddAlerts() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->request->post('only_zip') !== NULL) { // for company location 
            $zip_code = Yii::$app->request->post('zipcode');
            $street = Yii::$app->request->post('street');
            $city = Yii::$app->request->post('city');
            $state = Yii::$app->request->post('state');
            $store_number = Yii::$app->request->post('store_number');
            $keywords = array();
            $filters = array();
            $sort_by = 'Closest';
            $type = "exact_location";
            if (Alerts::addAlerts(['zip_code' => $zip_code, 'keywords' => $keywords, 'filters' => $filters, 'type' => $type, 'sort' => $sort_by, 'street' => $street, 'city' => $city, 'state' => $state,'store_number' => $store_number])) {
                return ['msgType' => 'SUC', 'msg' => 'Alert successfully Added'];
            } else {
                return ['msgType' => 'ERR', 'msg' => 'This alert is already in your list '];
            }
        } else {
            $zip_code = Yii::$app->request->post('zipcode');
            $keywords = Yii::$app->request->post('keywords');
            $sort_by = Yii::$app->request->post('sortBy');
            $filters = Yii::$app->request->post('filters');
            $type = "search_base";
            if (Alerts::addAlerts(['zip_code' => $zip_code, 'keywords' => $keywords, 'filters' => $filters, 'type' => $type, 'sort' => $sort_by])) {
                return ['msgType' => 'SUC', 'msg' => 'Alert successfully Added'];
            } else {
                return ['msgType' => 'ERR', 'msg' => 'This alert is already in your list '];
            }
        }
    }

    public function actionDeleteAlert() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $alert = Yii::$app->request->post('alert');
        if (Alerts::delAlert($alert)) {
            return ['msgType' => 'SUC', 'msg' => 'Alert successfully deleted'];
        } else {
            return ['msgType' => 'ERR', 'msg' => 'Unable to delete alert at this time'];
        }
    }

}
