<?php

namespace backend\models;

use yii\base\Model;

/**
 * Location form
 */
class LocationForm extends Model {

    public $company;
    public $street;
    public $city;
    public $state;
    public $zip;
    public $contact_name;
    public $phone;
//    public $email;
//    public $latitude;
//    public $longitude;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            // username and password are both required
            [['company', 'street', 'city', 'state', 'zip', 'contact_name', 'phone'], 'required'],
            // string fields
            [['company', 'street', 'city', 'state', 'zip', 'contact_name', 'phone'], 'string'],
        ];
    }

    /**
     * 
     */
    public static function getCsvAttributeMapArray() {
        return [// location attributes
            'store name' => 'company',
            'street address' => 'street',
            'city' => 'city',
            'state' => 'state',
            'zip' => 'zip',
            'contact name' => 'contact_name',
            'store phone' => 'phone',
        ];
    }

// end class
}
