<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * Description of ErrorController
 *
 * @author zeeshan
 */
class ErrorController extends Controller {

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
        $error_record = \common\models\EventError::find()->all();
        print_r($error_record);
    }

}
