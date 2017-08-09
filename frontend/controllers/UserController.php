<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace frontend\controllers;

use common\functions\GlobalFunctions;
use yii\web\Controller;
use yii\filters\AccessControl;

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
        $logoLinks = $events = [];
        $events = \common\models\Event::findAll(['_id' => \Yii::$app->user->identity->saved_events]);

        foreach ($events as $event) {
            $company = \common\models\Company::findOne(['name' => $event->company]);
            if (count($company) > 0 && !empty($company->logo)) {
                $logoLinks[$event->event_id] = IMG_URL . $company['logo'];
            } else {
                $logoLinks[$event->event_id] = \yii\helpers\BaseUrl::base() . '/images/upload-logo.png';
            }
        }

        return $this->render("profile", ['events' => $events, 'companyLogo' => $logoLinks]);
    }

    public function actionAlerts() {
        $keywords = GlobalFunctions::getKeywords();
        $alerts_list = array();
        foreach ($keywords as $keyword) {
            array_push($alerts_list, $keyword['text']);
        }
        return $this->render("alerts", ['alerts_list' => $alerts_list]);
    }

}
