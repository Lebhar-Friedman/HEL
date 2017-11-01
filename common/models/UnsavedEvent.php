<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models;

use yii\mongodb\ActiveRecord;

/**
 * Description of UnsavedEvent
 *
 * @author WhiteHat
 */
class UnsavedEvent extends ActiveRecord {

    public static function CollectionName() {
        return ['health_events', 'unsaved_events'];
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
            'error_type',
            'error_msg',
            'created_at',
            'updated_at',
        ];
    }

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
            'error_type',
            'error_msg',
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

}
