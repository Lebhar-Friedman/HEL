<?php

namespace backend\models;

use common\models\Company;
use yii\base\Model;

/**
 * Login form
 */
class CompanyForm extends Model {

    public $company_number;
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
            ['c_id', 'safe'],
            // username and password are both required
            [['company_number', 'name', 'contact_name', 'phone', 'email', 'street', 'city', 'state', 'zip'], 'required'],
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

    public function validateCompanyName($attribute, $params) {
        if (isset($this->c_id) && !empty($this->c_id)) {
            $whereParams = ['AND', ['not', '_id', new \MongoDB\BSON\ObjectID($this->c_id)], ['name' => $this->name]];
        } else {
            $whereParams = ['name' => $this->name];
        }
        $model = Company::find()->andWhere($whereParams)->all();
        if (count($model) > 0) {
            $this->addError($attribute, 'This Company (' . $this->name . ') name is already taken');
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

    public static function getCsvAttributeMapArray() {
        return $attributeMapArray = [
            'company number' => 'company_number',
            'company name' => 'name',
            'contact name' => 'contact_name',
            'phone' => 'phone',
            'email' => 'email',
            'street' => 'street',
            'city' => 'city',
            'state' => 'state',
            'zip' => 'zip'
        ];
    }

    public static function saveCSV($csv) {
        $importedCompanies = [];
        $validate = \backend\models\CompanyForm::validateCSV($csv);
//        echo json_encode($validate);
        if ($validate['result']) {
            $models = $validate['models'];
            foreach ($models as $model) {
                $model->name = ucfirst($model->name);
                $company = Company::findOne(['company_number' => $model->company_number]);
                if (count($company) == 0) {
                    $company = new Company();
                }
                $company->attributes = $model->attributes;
                $company->save();
                array_push($importedCompanies, $company->_id);
            }
            \common\models\Values::saveValue('import', 'companies', $importedCompanies);
            return json_encode(['msgType' => 'SUC', 'msg' => 'All ' . count($models) . ' Companies were imported successfully.', 'validated' => 'CSV is validated Successfully.', 'importedCompanies' => $importedCompanies]);
        } else {
            return json_encode(['msgType' => 'ERR', 'msg' => $validate['msg']]);
        }
    }

    /**
     * Validates the csv data of companies.
     *
     */
    public static function validateCSV($csv) {
        $companyAttributeMapArray = self::getCsvAttributeMapArray();
        $companyAttributes = $result = [];
        $file = fopen("uploads/import/" . $csv, "r");
        $headerRow = array_map('trim', array_map('strtolower', fgetcsv($file))); //fgetcsv($file);
        if (!empty($headerRow)) {
            $rowNo = 1;
            $models = [];
            while (!feof($file)) {
                $rowNo++;
                $companyModel = new CompanyForm();
                $dataRow = fgetcsv($file);
                if (!empty($dataRow) && count(array_filter($dataRow))) {
                    foreach ($headerRow as $key => $value) {
                        if (isset($companyAttributeMapArray[$value])) {
                            $companyAttributes[$companyAttributeMapArray[$value]] = trim($dataRow[$key]);
                        } elseif (!empty($value)) {
                            fclose($file);
                            return ['result' => FALSE, 'msg' => '<b>Invalid field "' . $value . '" at Row ' . $rowNo . ' and Column ' . $key . '</b> <br>'];
                        }
                    }
                    $company = Company::findOne(['name' => $companyAttributes['name']]);
                    if (count($company) > 0) {
                        $companyModel->c_id = $company->_id;
                    }
                    $companyModel->attributes = $companyAttributes;

                    if (!$companyModel->validate()) {
                        fclose($file);
                        return ['result' => FALSE, 'msg' => '<b>Following error occured at row ' . $rowNo . '</b> <br>' . \components\GlobalFunction::modelErrorsToString($companyModel->getErrors()), 'row' => json_encode($dataRow)];
                    }
                    array_push($models, $companyModel);
                }
            }
        }

        fclose($file);
        return ['result' => TRUE, 'models' => $models];
    }

// end class
}
