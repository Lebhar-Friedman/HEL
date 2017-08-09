<?php

namespace common\models;

use Yii;
use yii\mongodb\ActiveRecord;

/**
 * Location model
 *
 * @property integer $_id
 * @property string $user_id
 * @property string $alerts
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

        $user_id = (string) Yii::$app->user->id;

        $existing_entry = Alerts::findOne(['user_id' => $user_id]);
        if ($existing_entry) {
            $alerts = $existing_entry->alerts;
            if (!in_array($updatable_alert, $alerts)) {
                array_push($alerts, $updatable_alert);
                $existing_entry->alerts = $alerts;
                if ($existing_entry->save()) {
                    return true;
                }
            }
            return false;
        } else {
            $alert_obj = new Alerts();
            $alert_obj->user_id = $user_id;
            $alerts = array();
            array_push($alerts, $updatable_alert);
            $alert_obj->alerts = $alerts;
            if ($alert_obj->save()) {
                return true;
            }
            return false;
        }
    }

    public static function delAlert($del_alert) {
        $user_id = (string) Yii::$app->user->id;
        $existing_entry = Alerts::findOne(['user_id' => $user_id]);
        if ($existing_entry) {
            $alerts_old = $existing_entry->alerts;
            $alerts_new = array();
            foreach ($alerts_old as $alert_old) {
                if ($alert_old === $del_alert) {
                    continue;
                }
                array_push($alerts_new, $alert_old);
            }
            $existing_entry->alerts = $alerts_new;
            $existing_entry->save();
            if ($alerts_old == $alerts_new) {
                return FALSE;
            } else {
                return TRUE;
            }
        }
        return FALSE;
    }

// end class counter
}
