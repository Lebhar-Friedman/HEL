<?php

namespace backend\controllers;

use backend\models\CompanyForm;
use common\models\Company;
use Yii;
use yii\web\Controller;

class CompanyController extends Controller {

    public function actionIndex() {
        return $this->render('index');
    }

    public function actionDetail($id = "") {
        
        $request = Yii::$app->request;
        $model = new CompanyForm();
        if ($request->isPost) {
            $model->load($request->post());
            $company = new Company();
            $company->attributes = $model->attributes;
            $company->save();
        }
        return $this->render('detail', ['model' => $model]);
    }

}

?>