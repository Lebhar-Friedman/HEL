<?php

namespace frontend\controllers;

use common\models\Company;
use common\models\Event;
use MongoDB\Driver\Exception\InvalidArgumentException;
use Yii;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

class ProviderController extends Controller {

    public function actionIndex() {
        $name = trim(urldecode(Yii::$app->request->get('id')));
        $name = ucfirst(strtolower($name));
        try {
            $company = Company::findOne(['name' => $name]);
            $total_events = 0;
            if ($company !== NULL) {
                $events = Event::find()->where(['company' => $company->name]);
                $total_events = $events->count();
            }
        } catch (InvalidArgumentException $ex) {
            throw new ForbiddenHttpException("Please provide correct ID of Provider.");
        }
        return $this->render('index', ['company' => $company, 'total_events' => $total_events]);
    }

    public function actionEvents($id) {
        $name = trim(urldecode(Yii::$app->request->get('id')));
        $name = ucfirst(strtolower($name));
        $events = $total_events = NULL;
        try {
            $company = Company::findOne(['name' => $name]);
            if ($company !== NULL) {
                $queryEvents = Event::find()->where(['company' => $company->name]);
                $total_events = $queryEvents->count();
                $events = $queryEvents->all();
            }
        } catch (InvalidArgumentException $ex) {
            throw new ForbiddenHttpException("Please provide correct ID of Provider.");
        }
        return $this->render('events', ['company' => $company, 'total_events' => $total_events, 'providerEvents' => $events]);
    }

}
