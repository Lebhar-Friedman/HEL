<?php

namespace common\functions;

use common\models\Company;

class GlobalFunctions {

    public function getCompanyLogo($name) {
        $company = Company::findOne(['name' => $name]); //->where(['name'=> $name]);
        if ($company && $company->logo !== NULL) {
            return IMG_URL . $company->logo;
        }
        return \yii\helpers\BaseUrl::base().'/images/upload-logo.png';
    }

}
