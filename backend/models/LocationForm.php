<?php

namespace backend\models;

use yii\base\Model;

/**
 * Location form
 */
class LocationForm extends Model {

    public $id;
    public $store_number;
    public $company;
    public $street;
    public $city;
    public $state;
    public $zip;
    public $contact_name;
    public $phone;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            // username and password are both required
            [['store_number', 'company', 'street', 'city', 'state', 'zip', 'contact_name', 'phone'], 'required'],
            // string fields
            [['company', 'street', 'city', 'state', 'zip', 'contact_name', 'phone'], 'string'],
            ['company', 'validateCompany']
        ];
    }

    public function validateCompany($attribute, $params) {
        $this->company = ucfirst(strtolower(trim($this->company)));
        $company = \common\models\Company::find()->andWhere(['name' => $this->company])->one();        //print_r($company);die;
        if (count($company) > 0) {
            ;
        } else {
            $this->addError($attribute, 'This Company (' . $this->company . ') does not exist.');
        }
    }

    /**
     * 
     */
    public static function getCsvAttributeMapArray() {
        return [// location attributes
            'store' => 'store_number',
            'store name' => 'company',
            'street address' => 'street',
            'city' => 'city',
            'state' => 'state',
            'zip' => 'zip',
            'contact name' => 'contact_name',
            'store phone' => 'phone',
        ];
    }

    public function saveLocation() {
        if ($this->validate()) {
            $location = \common\models\Location::findOne(['_id' => new \MongoDB\BSON\ObjectID($this->id)]);
            $location->attributes = $this->attributes;
            $latlong = \components\GlobalFunction::getLongLat($this); //exit(print_r($latlong));
            if ($latlong) {
                $location->geometry = ['type' => 'Point',
                    'coordinates' => [
                        $latlong['long'],
                        $latlong['lat']
                    ]
                ];
            }
            if ($location->update() !== FALSE) {
                return TRUE;
            } else {
                $this->errors = $location->errors;
                return FALSE;
            }
        }
    }

// end class
}
