<?php

namespace backend\models;

use common\models\Company;
use yii\base\Model;
use common\models\Categories;

/**
 * Login form
 */
class CategoryForm extends Model {

    public $name;
    public $sub_categories;
    public $category_id;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['category_id', 'sub_categories'], 'safe'],
            // username and password are both required
            ['name', 'required'],
            // string fields
            ['name', 'string'],
            ['name', 'validateCategoryName'],
//          ['name', 'unique','targetClass'=> '\common\models\Company', 'message' => 'Company name must be unique.'],
        ];
    }

    public function validateCategoryName($attribute, $params) {
        if (isset($this->category_id) && !empty($this->category_id)) {
            $whereParams = ['AND', ['not', '_id', new \MongoDB\BSON\ObjectID($this->category_id)], ['name' => $this->name]];
        } else {
            $whereParams = ['name' => $this->name];
        }
        $model = Categories::find()->andWhere($whereParams)->all();
        if (count($model) > 0) {
            $this->addError($attribute, 'This category name (' . $this->name . ') already exist');
        }
    }

// end class
}
