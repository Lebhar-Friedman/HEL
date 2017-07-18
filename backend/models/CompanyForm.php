<?php

namespace backend\models;

use common\models\Company;
use yii\base\Model;

/**
 * Login form
 */
class CompanyForm extends Model {

    public $name;
    public $contact_name;
    public $phone;
    public $email;
    public $logo;
    public $street;
    public $city;
    public $state;
    public $zip;
    public $c_id;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['c_id','safe'],
            // username and password are both required
                [['name', 'contact_name', 'phone', 'email', 'street', 'city', 'state', 'zip'], 'required'],
            // string fields
            [['name', 'contact_name', 'street', 'city', 'state', 'zip'], 'string'],
            // email validation
            ['email', 'email'],
            // image field
            [['logo'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
                ['name', 'validateCompanyName'],
//            ['name', 'unique','targetClass'=> '\common\models\Company', 'message' => 'Company name must be unique.'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params) {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || $this->role != $user->role || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    public function validateCompanyName($attribute, $params) {
        if (isset($this->c_id) && !empty($this->c_id)){
            $whereParams = ['AND', ['not', '_id', new \MongoDB\BSON\ObjectID($this->c_id)], ['name' => $this->name]];
        }
        else {
            $whereParams = ['name' => $this->name];
        }
        $model = Company::find()->andWhere($whereParams)->all();
        if (count($model) > 0) {
            $this->addError($attribute, 'This Company name is already taken');
        }
    }

    public function upload($image_name = '') {
        if ($this->validate()) {
            if ($this->logo == null) {
                return true;
            }
            if ($image_name == '') {
                $image_name = $this->logo->baseName;
            }
            $this->logo->saveAs('uploads/' . $image_name . '.' . $this->logo->extension);
            return true;
        } else {
            return false;
        }
    }

// end class
}
