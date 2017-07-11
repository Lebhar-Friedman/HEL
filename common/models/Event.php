<?php

namespace common\models;

use yii\mongodb\ActiveRecord;

/**
 * Location model
 *
 * @property integer $_id
 * @property integer $event_id auto increment
 * @property string $title
 * @property string $company
 * @property string $date_start
 * @property string $date_end
 * @property string $time_start
 * @property string $time_end
 * @property string $categories
 * @property string $sub_categories
 * @property string $locations
 * @property float $price
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
            'company',
            'date_start',
            'date_end',
            'time_start',
            'time_end',
            'categories',
            'sub_categories',
            'locations',
            'price',
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
            'title', // 
            'company',
            'date_start',
            'date_end',
            'time_start',
            'time_end',
            'categories',
            'sub_categories',
            'locations',
            'price',
            'description',
            'is_post',
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
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->event_id = Counter::getAutoIncrementId(Counter::COUNTER_EVENT_ID);
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public static function findEvent($id) {
        return static::findOne(['_id' => $id]);
    }

// end class counter
}
