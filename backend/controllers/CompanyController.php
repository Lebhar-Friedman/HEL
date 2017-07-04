<?php

namespace backend\controllers;

use backend\models\CompanyForm;
use common\models\Company;
use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;

class CompanyController extends Controller {

    public function actionIndex() {
        return $this->render('index');
    }

    public function actionDetail($id = "") {

        $request = Yii::$app->request;
        $model = new CompanyForm();
        if ($request->isPost) {
            $model->load($request->post());
            $model->logo = UploadedFile::getInstance($model, 'logo');
            $company = new Company();
            $company->attributes = $model->attributes;

            $image_name = rand(100, 5000);
            if (isset($model->logo) && $model->upload($image_name)) {
                $company->logo = $image_name . '.' . $model->logo->extension;
                $model->logo=$company->logo;
            }
            $company->save();
        }
        return $this->render('detail', ['model' => $model]);
    }

}

?>