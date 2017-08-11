<?php

namespace common\models;

use yii\mongodb\ActiveRecord;

/**
 * Location model
 *
 * @property integer $_id
 * @property integer $location_id
 * @property string $company
 * @property string $street
 * @property string $city
 * @property string $state
 * @property string $zip
 * @property string $contact_name
 * @property string $phone
 * @property string $email
 * @property double $latitude
 * @property double $longitude
 * @property Object $geometry
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
            'location_id', // auto increment serial#
            'store_number', // Number aloted to the store
            'company', // 
            'street',
            'city',
            'state',
            'zip',
            'contact_name',
            'phone',
//            'email',
//            'latitude',
//            'longitude',
            'geometry',
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
            'location_id', // auto increment serial#
            'store_number',
            'company', // 
            'street',
            'city',
            'state',
            'zip',
            'contact_name',
            'phone',
//            'email',
//            'latitude',
//            'longitude',
            'geometry',
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
                $this->location_id = Counter::getAutoIncrementId(Counter::COUNTER_LOCATION_ID);
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public static function findLocation($id) {
        return static::findOne(['_id' => $id]);
    }

// end class counter
}
