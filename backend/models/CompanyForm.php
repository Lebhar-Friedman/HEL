<?php

namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model {

    public $name;
    public $contact_name;
    public $phone;
    public $email;
    public $logo;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            // username and password are both required
            [['name', 'contact_name', 'phone', 'email'], 'required'],
            // string fields
            [['name', 'contact_name'], 'string'],
            // email validation
            ['email', 'email'],
            // image field
            ['logo', 'file'],
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

// end class
}
