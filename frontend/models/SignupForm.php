<?php

namespace frontend\models;

use common\models\User;
use Yii;
use yii\base\Model;

/**
 * Signup form
 */
class SignupForm extends Model {

    public $username;
    public $first_name;
    public $last_name;
    public $email;
    public $password;
    public $confirm_password;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['username', 'first_name', 'last_name', 'email'], 'trim'],
                [['username','first_name','last_name','email'], 'required'],
                ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
                ['username', 'string', 'min' => 2, 'max' => 255],
                ['email', 'email'],
                ['email', 'string', 'max' => 255],
                ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],
                ['password', 'required'],
                ['password', 'string', 'min' => 6],
                [['password', 'confirm_password'], 'required', 'on' => 'create'],
                [['confirm_password'], 'validateConfirmPassword'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup() {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->first_name = $this->first_name;
        $user->last_name = $this->last_name;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->status = 0;
        return $user->save() ? $user : null;
    }

    public function validateConfirmPassword($attribute) {
        if ($this->password != $this->confirm_password) {
            $this->addError($attribute, 'Confirm Password is not same.');
        }
    }

    public function confirmationEmail($user) {
        return Yii::$app->mailer->compose(
                                ['html' => 'registration-confirmation-html'], ['user' => $user]
                        )
                        ->setTo($user->email)
                        ->setFrom(Yii::$app->params['supportEmail'])
                        ->setSubject('Health Events Live Confirmation')
                        ->send();
    }

}
