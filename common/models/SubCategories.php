<?php

namespace common\models;

use yii\mongodb\ActiveRecord;

/**
 * Location model
 *
 * @property integer $_id
 * @property integer $sub_category_id
 * @property string $name
 * @property string $sub_category_slug
 * @property integer $created_at
 * @property integer $updated_at
 */
class SubCategories extends ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function collectionName() {
        return ['health_events', 'sub_categories'];
    }

    //setup for model attributes
    public function attributes() {
        return ['_id',
            'sub_category_id', // auto increment serial#
            'name', // 
            'sub_category_slug', // 
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
            'sub_category_slug',
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
            $this->setSlug();
            if ($insert) {
                $this->sub_category_id = Counter::getAutoIncrementId(Counter::COUNTER_SUB_CATEGORY_ID);
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public static function findSubCategory($id) {
        return static::findOne(['_id' => $id]);
    }

    public static function SubCategoryList() {
        return static::find()->all();
    }
    
    public function setSlug() {
        $slug = strtolower(str_replace(' ', '-', $this->name));
        $slug = str_replace('-','',preg_replace('/[^A-Za-z0-9\-]/', '', $slug));
        $this->sub_category_slug = $slug;
    }

// end class counter
}
