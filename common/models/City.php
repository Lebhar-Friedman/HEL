<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models;

use yii\mongodb\ActiveRecord;

/**
 * Description of City
 *
 * @property string $zipcode
 * @property string $city
 */
class City extends ActiveRecord {

    public static function collectionName() {
        return ['health_events', 'cities'];
    }

    //setup for model attributes
    public function attributes() {
        return ['_id',
            'zipcode',
            'city',
            'created_at',
            'updated_at',
        ];
    }
    
     public function rules() {
        return [
                [['_id', 'zipcode', 'city', 'created_at', 'updated_at'], 'safe'],
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
