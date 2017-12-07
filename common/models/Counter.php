<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\mongodb\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * Counter model
 *
 * @property integer $id
 * @property string $counter_name
 * @property string $sequence_value
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class Counter extends ActiveRecord {

    const COUNTER_USER_ID = 'userid';
    const COUNTER_CATEGORY_ID = 'categoryid';
    const COUNTER_SUB_CATEGORY_ID = 'subcategoryid';
    const COUNTER_COMPANY_ID = 'companyid';
    const COUNTER_EVENT_ID = 'eventid';
    const COUNTER_LOCATION_ID = 'locationid';   
    const COUNTER_VALUE_ID = 'valueid';   
    const COUNTER_ERROR_ID = 'errorid';   
    

    /**
     * @inheritdoc
     */
    public static function collectionName() {
        return ['health_events', 'counter'];
    }

    //setup for model attributes
    public function attributes() {
        return ['_id',
            'counter_name', // name of counter sequence related to sepecific model
            'sequence_value', // to save currunt incremented sequence id
            'created_at',
            'updated_at',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['_id',
            'counter_name', // name of counter sequence related to sepecific model
            'sequence_value', // to save currunt incremented sequence id
            'created_at',
            'updated_at'], 'safe'],
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

    /**
     * @inheritdoc
     */
    public static function findCounter($id) {
        return static::findOne(['_id' => $id]);
    }

    public static function getAutoIncrementId($counterName) {
        $counter = static::findOne(['counter_name' => $counterName]);
        if ($counter) {
            $counter->sequence_value++;
        } else {
            $counter = new Counter();
            $counter->counter_name = $counterName;
            $counter->sequence_value = 1;
        }
        $counter->save();
        return $counter->sequence_value;
    }

// end class counter
}
