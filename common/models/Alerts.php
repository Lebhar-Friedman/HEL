<?php

namespace common\models;

use common\functions\GlobalFunctions;
use Yii;
use yii\mongodb\ActiveRecord;

/**
 * Location model
 *
 * @property integer $_id
 * @property string $user_id
 * @property string $alerts
 * @property string $location
 */
class Alerts extends ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function collectionName() {
        return ['health_events', 'user_alerts'];
    }

    //setup for model attributes
    public function attributes() {
        return ['_id',
            'user_id',
            'alerts',
            'location',
            'created_at',
            'updated_at',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['_id', 'user_id', 'alerts', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new \MongoDB\BSON\UTCDateTime(round(microtime(true) * 1000)),
            ]
        ];
    }

    public static function addAlerts($updatable_alert) {

        $session = Yii::$app->session;
        $user_id = (string) Yii::$app->user->id;
        $coordinates = array();

        $zip_code = $updatable_alert['zip_code'];
        $keywords = isset($updatable_alert['keywords']) ? $updatable_alert['keywords'] : array();
        $filters = isset($updatable_alert['filters']) ? $updatable_alert['filters'] : array();
        $sort = $updatable_alert['sort'];
        $type = $updatable_alert['type'];

        if (GlobalFunctions::getCookiesOfLngLat()) {
            $coordinates = GlobalFunctions::getCookiesOfLngLat();
        } else {
            $ip = Yii::$app->request->userIP;
            $latitude = Yii::$app->ip2location->getLatitude($ip);
            $longitude = Yii::$app->ip2location->getLongitude($ip);
            $coordinates['longitude'] = $longitude;
            $coordinates['latitude'] = $latitude;
        }

        $existing_entry = Alerts::findOne(['user_id' => $user_id]);
        if ($existing_entry) {
            $alerts = $existing_entry->alerts;
            $new_alert['_id'] = new \MongoDB\BSON\ObjectID();
            $new_alert['zip_code'] = $zip_code;
            $new_alert['keywords'] = $keywords;
            $new_alert['filters'] = $filters;
            $new_alert['sort'] = $sort;
            $new_alert['longitude'] = $session->get('lng');
            $new_alert['latitude'] = $session->get('lat');
            $new_alert['type'] = $type;
            if($type === "exact_location"){
                $new_alert['street'] = $updatable_alert['street'];
                $new_alert['city'] = $updatable_alert['city'];
                $new_alert['state'] = $updatable_alert['state'];
            }
            $alerts[] = $new_alert;
            $existing_entry->alerts = $alerts;
            if ($existing_entry->save()) {
                return true;
            }
            return false;
        } else {
            $alert_obj = new Alerts();
            $alert_obj->user_id = $user_id;
            $new_alert['_id'] = new \MongoDB\BSON\ObjectID();
            $new_alert['zip_code'] = $zip_code;
            $new_alert['keywords'] = $keywords;
            $new_alert['filters'] = $filters;
            $new_alert['sort'] = $sort;
            $new_alert['longitude'] = $session->get('lng');
            $new_alert['latitude'] = $session->get('lat');
            $new_alert['type'] = $type;
            if($type === "exact_location"){
                $new_alert['street'] = $updatable_alert['street'];
                $new_alert['city'] = $updatable_alert['city'];
                $new_alert['state'] = $updatable_alert['state'];
            }
            $alert_obj->alerts = array($new_alert);
            
            $alert_obj->location = $coordinates;
            if ($alert_obj->save()) {
                return true;
            }
            return false;
        }
    }

    public static function delAlert($del_alert) {
        $user_id = (string) Yii::$app->user->id;
        $existing_entry = Alerts::findOne(['user_id' => $user_id]);

        $alerts_old = $existing_entry->alerts;
        $alerts_new = array();
        $deleted =false;

        if ($existing_entry) {
            foreach ($alerts_old as $single_alert_obj) {
                if ((string)$single_alert_obj['_id'] === (string)$del_alert) {
                    $deleted = true;
                    continue;
                }
                array_push($alerts_new, $single_alert_obj);
            }
            $existing_entry->alerts = $alerts_new;
            $existing_entry->save();
            if ($deleted) {
                return TRUE;
            } else {
                return FLASE;
            }
        }
        return FALSE;
    }

// end class counter
}
