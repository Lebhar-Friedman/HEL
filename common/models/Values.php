<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models;

use Yii;
use yii\mongodb\ActiveRecord;
use yii\web\IdentityInterface;
use yii\behaviors\AttributeBehavior;

/**
 * Description of MilesAccount
 *
 * @author Muhammad Asif
 */
class Values extends ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function collectionName() {
        return ['health_events', 'values'];
    }

    public function attributes() {
        return [
            '_id',
            'value_id',
            'value_type',
            'name',
            'value',
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
            'value_id',
            'value_type',
            'name',
            'value',], 'safe'],
        ];
    }

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
                $this->value_id = Counter::getAutoIncrementId(Counter::COUNTER_VALUE_ID);
            }
            return true;
        } else {
            return false;
        }
    }

    public static function saveValue($type, $name, $value) {
        $model = Values::findOne(['value_type' => $type, 'name' => $name]);
        if (count($model) > 0) {
            $model->value = $value;
        } else {
            $model = new Values();
            $model->value_type = $type;
            $model->name = $name;
            $model->value = $value;
        }

        if ($model->save()) {
            return TRUE;
        } else {
            return $model->errors;
        }
    }

    public static function getValue($type, $name) {
        $value = Values::find(['value_type' => $type, 'name' => $name])->select(['value'])->one();
        return $value;
    }

// end class
}
