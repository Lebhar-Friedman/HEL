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
 * @property integer $sub_category_id
 * @property string $name
 * @property integer $created_at
 * @property integer $updated_at
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
            'sub_category_id', // auto increment serial#
            'name', // 
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
            'sub_category_id', // auto increment serial#
            'name', // 
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
                    ActiveRecord::EVENT_BEFORE_INSERT => ['sub_category_id'],
                ],
                'value' => Counter::getAutoIncrementId(Counter::COUNTER_SUB_CATEGORY_ID),
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findSubCategory($id) {
        return static::findOne(['_id' => $id]);
    }


// end class counter
}
