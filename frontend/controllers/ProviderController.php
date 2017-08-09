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
        $events = [];
        $total_events = 0;
        try {
            $company = Company::findOne(['name' => $name]);
            if ($company !== NULL) {
                $queryEvents = Event::find()->where(['company' => $company->name]);
                $total_events = $queryEvents->count();
                $events = $queryEvents->all();

                $cookies = Yii::$app->request->cookies;

                if (($cookie_long = $cookies->get('longitude')) !== null && ($cookie_lat = $cookies->get('latitude'))) {
                    $longitude = $cookie_long->value;
                    $latitude = $cookie_lat->value;
                    $temp_zip = \components\GlobalFunction::getZipFromLongLat($longitude, $latitude);
                    $zip_code = $temp_zip ? $temp_zip : $zip_code;
                }
                if (Yii::$app->request->isPost) {
                    $zip_code = Yii::$app->request->post('zipcode');
                    $keywords = Yii::$app->request->post('keywords');
                    $sort_by = Yii::$app->request->post('sortBy');
                    $filters = Yii::$app->request->post('filters');

                    $longlat = \components\GlobalFunction::getLongLatFromZip($zip_code);
                    $latitude = $longlat['lat'];
                    $longitude = $longlat['long'];
                    if (($cookie_long = $cookies->get('longitude')) !== null && ($cookie_lat = $cookies->get('latitude'))) {
                        $longitude = $cookie_long->value;
                        $latitude = $cookie_lat->value;
                        $temp_zip = GlobalFunction::getZipFromLongLat($longitude, $latitude);
                        $zip_code = $temp_zip ? $temp_zip : $zip_code;
                    }
                    $z_lng_lat = EventController::getZipLongLat();
                    $events_dist = EventController::getEventsWithDistance($z_lng_lat['zip_code'], $keywords, $filters, $z_lng_lat['longitude'], $z_lng_lat['latitude'], 50, 0, $sort_by, $company->name);
                    $total_events = sizeof($events_dist);
                    return $this->render('events', ['company' => $company, 'events' => $events_dist, 'zip_code' => $z_lng_lat['zip_code'], 'total_events' => $total_events, 'ret_keywords' => $keywords, 'ret_filters' => $filters, 'ret_sort' => $sort_by, 'longitude' => $z_lng_lat['longitude'], 'latitude' => $z_lng_lat['latitude']]);
                }
            }
        } catch (InvalidArgumentException $ex) {
            throw new ForbiddenHttpException("Please provide correct ID of Provider.");
        }
        return $this->render('events', ['company' => $company, 'total_events' => $total_events, 'events' => $events]);
    }

}
