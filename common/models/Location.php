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
class Location extends ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function collectionName() {
        return ['health_events', 'location'];
    }

    //setup for model attributes
    public function attributes() {
        return ['_id',
            'location_id', // auto increment serial#
            'company', // 
            'street',
            'city',
            'state',
            'zip',
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
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id) {
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
