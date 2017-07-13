<?php

namespace common\models;

use yii\behaviors\AttributeBehavior;
use yii\mongodb\ActiveRecord;

/**
 * Location model
 *
 * @property integer $_id
 * @property integer $company_id
 * @property string $name
 * @property string $contact_name
 * @property string $phone
 * @property string $email
 * @property string $logo
 * @property integer $created_at
 * @property integer $updated_at
 */
class Company extends ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function collectionName() {
        return ['health_events', 'Company'];
    }

    //setup for model attributes
    public function attributes() {
        return ['_id',
            'company_id', // auto increment serial#
            'name', //
            'contact_name',
            'phone',
            'email',
            'logo',
            'street',
            'city',
            'state',
            'zip',
            'created_at',
            'updated_at'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['_id',
            'company_id', // auto increment serial#
            'name', //
            'contact_name',
            'phone',
            'email',
            'logo',
            'street',
            'city',
            'state',
            'zip',
            'created_at',
            'updated_at'], 'safe'
            ]
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
                $this->company_id = Counter::getAutoIncrementId(Counter::COUNTER_COMPANY_ID);
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public static function findCompany($id) {
        return static::findOne(['_id' => $id]);
    }

}
