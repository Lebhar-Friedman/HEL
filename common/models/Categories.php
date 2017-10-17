<?php

namespace common\models;

use yii\mongodb\ActiveRecord;

/**
 * Location model
 *
 * @property integer $_id
 * @property integer $category_id
 * @property string $name
 * @property string $sub_categories
 * @property string $category_slug
 * @property integer $created_at
 * @property integer $updated_at
 */
class Categories extends ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function collectionName() {
        return ['health_events', 'categories'];
    }

    //setup for model attributes
    public function attributes() {
        return ['_id',
            'category_id', // auto increment serial#
            'name', // 
            'sub_categories',
            'category_slug',
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
            'category_id', // auto increment serial#
            'name', // 
            'sub_categories',
            'category_slug',
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
        $this->setSlug();
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->category_id = Counter::getAutoIncrementId(Counter::COUNTER_CATEGORY_ID);
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public static function findCategory($id) {
        return static::findOne(['_id' => $id]);
    }

    public static function CategoryList() {
        return static::find()->all();
    }

    public function setSlug() {
        $slug = strtolower(str_replace(' ', '', $this->name));
        $slug = preg_replace('/[^A-Za-z0-9\-]/', '', $slug);
        $this->category_slug = $slug;
    }

// end class counter
}
