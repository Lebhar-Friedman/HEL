<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models;

use yii\mongodb\ActiveRecord;

/**
 * Error model
 *
 * @property integer $_id
 * @property integer $error_id auto increment
 * @property string $store
 * @property string $company_id
 * @property string $street
 * @property string $city
 * @property string $state
 * @property string $zip
 * @property string $contact_name
 * @property string $phone
 * @property string $title
 * @property string $date_start
 * @property string $date_end
 * @property string $time_start
 * @property string $time_end
 * @property string $categories
 * @property string $sub_categories
 * @property float $price
 * @property string $errors
 * @property string $description
 * @property integer $created_at
 * @property integer $updated_at
 */
class EventError extends ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function collectionName() {
        return ['health_events', 'event_errors'];
    }

    public function attributes() {
        return ['_id',
            'error_id', // auto increment serial#
            'store',
            'company_id',
            'street',
            'city',
            'state',
            'zip',
            'contact_name',
            'phone',
            'title', // 
            'date_start',
            'date_end',
            'time_start',
            'time_end',
            'categories',
            'sub_categories',
            'price',
            'description',
            'errors',
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
            'error_id', // auto increment serial#
            'store',
            'company_id',
            'street',
            'city',
            'state',
            'zip',
            'contact_name',
            'phone',
            'title', // 
            'date_start',
            'date_end',
            'time_start',
            'time_end',
            'categories',
            'sub_categories',
            'price',
            'description',
            'errors',
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
                $this->error_id = Counter::getAutoIncrementId(Counter::COUNTER_ERROR_ID);
            }
            return true;
        } else {
            return false;
        }
    }

}
