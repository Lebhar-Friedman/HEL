<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\mongodb\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * Location model
 *
 * @property integer $_id
 * @property integer $event_id
 * @property string $title
 * @property string $date_start
 * @property string $date_end
 * @property string $time_start
 * @property string $time_end
 * @property string $categories
 * @property string $sub_categories
 * @property string $locations
 * @property float $cost
 * @property string $description
 * @property boolean $is_post
 * @property integer $created_at
 * @property integer $updated_at
 */
class Event extends ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function collectionName() {
        return ['health_events', 'event'];
    }

    //setup for model attributes
    public function attributes() {
        return ['_id',
            'event_id', // auto increment serial#
            'title', // 
            'date_start',
            'date_end',
            'time_start',
            'time_end',
            'categories',
            'sub_categories',
            'locations',
            'cost',
            'description',
            'is_post',
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
            'event_id', // auto increment serial#
            'company', // 
            'street',
            'city',
            'state',
            'zip',
            'contact_name',
            'phone',
            'email',
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
            ],
            [
                'class' => \yii\behaviors\AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['event_id'],
                ],
                'value' => Counter::getAutoIncrementId(Counter::COUNTER_EVENT_ID),
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findEvent($id) {
        return static::findOne(['_id' => $id]);
    }


// end class counter
}
