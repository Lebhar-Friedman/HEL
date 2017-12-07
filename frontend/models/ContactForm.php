<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model {

    public $reason;
    public $name;
    public $email;
    public $subject;
    public $body;
    public $organization;
    public $event_address;
    public $event_date;
    public $event_time;
    public $event_title;
    public $event_categories_services;
    public $event_cost;
    public $event_insurance;
    public $detail;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['reason','name','email','organization','event_address','event_date','event_time','event_title','event_categories_services','event_cost','event_insurance','detail'],'safe'],
            [['detail'], 'required'],
            ['email', 'email'],
        ];
    }

    /**
     * @inheritdoc
     */
//    public function attributeLabels()
//    {
//        return [
//            'verifyCode' => 'Verification Code',
//        ];
//    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param string $email the target email address
     * @return bool whether the email was sent
     */
    public function sendEmail($email) {
        $subject ='Query from Health Events Live';
        if($this->reason == 1){
            $subject = 'Event query from Health Events Live';
        }
        return Yii::$app->mailer->compose(['html' => 'contact-email'], ['model' => $this])
                        ->setTo($email)
                        ->setBcc(Yii::$app->params['zeeshanEmail'])
                        ->setFrom(Yii::$app->params['contactEmail'])
                        ->setSubject($subject)
                        ->send();
    }

}
