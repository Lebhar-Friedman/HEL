<?php

namespace backend\models;

use common\models\SubCategories;
use yii\base\Model;

/**
 * Login form
 */
class SubCategoryForm extends Model {

    public $name;
    public $sub_categories;
    public $sub_category_id;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['sub_category_id', 'safe'],
            // username and password are both required
            ['name', 'required'],
            ['name', 'trim'],
            // string fields
            ['name', 'string'],
            ['name', 'validateSubCategoryName'],
//            ['name', 'unique','targetClass'=> '\common\models\Company', 'message' => 'Company name must be unique.'],
        ];
    }

    public function validateSubCategoryName($attribute, $params) {
        if (isset($this->sub_category_id) && !empty($this->sub_category_id)) {
            $whereParams = ['AND', ['not', '_id', new \MongoDB\BSON\ObjectID($this->sub_category_id)], ['name' => $this->name]];
        } else {
            $whereParams = ['name' => $this->name];
        }
        $model = SubCategories::find()->andWhere($whereParams)->all();
        if (count($model) > 0) {
            $this->addError($attribute, 'This Sub Category name (' . $this->name . ') already exist');
        }
    }

// end class
}
