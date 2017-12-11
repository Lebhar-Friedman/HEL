<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\models;

use common\models\Company;
use components\GlobalFunction;
use yii\base\Model;

/**
 * Error model
 *
 * @property integer $_id
 * @property integer $error_id auto increment
 * @property string $store
 * @property string $company_id
 * @property string $street
 * @property string $city
 * @property string $state
 * @property string $zip
 * @property string $contact_name
 * @property string $phone
 * @property string $title
 * @property string $date_start
 * @property string $date_end
 * @property string $time_start
 * @property string $time_end
 * @property string $categories
 * @property string $sub_categories
 * @property float $price
 * @property string $errors
 * @property string $description
 * @property integer $created_at
 * @property integer $updated_at
 */
class EventErrorForm extends Model {

    public $_id;
    public $error_id;
    public $store;
    public $company_id;
    public $street;
    public $city;
    public $state;
    public $zip;
    public $contact_name;
    public $phone;
    public $title;
    public $date_start;
    public $date_end;
    public $time_start;
    public $time_end;
    public $categories;
    public $sub_categories;
    public $price;
    public $errors;
    public $description;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['company_id', 'street', 'city', 'state', 'zip', 'contact_name', 'phone', 'title', 'date_start', 'time_start', 'time_end', 'description'], 'required'],
            // safe fields
            [['store', 'price', 'date_end', 'time_start', 'time_end', 'categories', 'sub_categories', 'errors', 'error_id'], 'safe'],
            [['title', 'company', 'street', 'city', 'state', 'zip', 'description'], 'string'],
            ['price', 'double'],
            ['company', 'validateCompany'],
            ['street', 'validateAddress']
        ];
    }

    public function validateCompanyName($attribute, $params) {
        $whereParams = ['company_number' => $this->company_id];
        $model = Company::find()->andWhere($whereParams)->all();
        if (!$model) {
            $this->addError($attribute, 'This Company  (' . $this->company_id . ') does not exist');
        }
    }

    public function validateAddress($attribute, $params) {
        $address = $this->street . ' ' . $this->city . ' ' . $this->state . ' ' . $this->zip;
        $lat_lng = GlobalFunction::getLongLat(NULL, $address);
        if (!($lat_lng) || (is_array($lat_lng) && isset($lat_lng['error']))) {
            $this->addError($attribute,'Address is not valid');
        }
    }

}
