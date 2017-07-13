<?php

namespace backend\controllers;

use backend\models\CompanyForm;
use common\models\Company;
use common\models\Location;
use Yii;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;

class CompanyController extends Controller {

    public function actionIndex() {
        $query = Company::find();
        $companySearch = urldecode(Yii::$app->request->get('name'));
        if ($companySearch !== '') {
            $query = $query->where(['like', 'name', $companySearch]);
        }
        $count = $query->count();

        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => (10)]);
        $companies = $query->offset($pagination->offset)->limit($pagination->limit)->orderBy(['name' => SORT_ASC])->all();
        $companies_arr=array();
        foreach ($companies as $company){
            $locations= Location::find()->where(['company'=> $company->name])->count();
            $events= \common\models\Event::find()->where(['company'=> $company->name])->count();
            $company['t_locations']=$locations;
            $company['t_events']=$events;
            $companies_arr[]=$company;
        }
//        echo '<pre>';
//        print_r($companies_arr);exit;
        return $this->render('index', ['companies' => $companies_arr, 'pagination' => $pagination, 'total' => $count]);
    }

    public function actionDetail($id = "") {
        if (!Yii::$app->user->isGuest && Yii::$app->user->identity->role === 'admin') {
            $request = Yii::$app->request;
            $model = new CompanyForm();
            if ($request->isPost && $request->isAjax) {
                $model->load($request->post());
                $model->logo = UploadedFile::getInstance($model, 'logo');
                $company = new Company();
                $company->attributes = $model->attributes;
//                $image_name = rand(100, 5000);
                $image_name=$company->name;
                if (isset($model->logo) && $model->upload($image_name)) {
                    $company->logo = $image_name . '.' . $model->logo->extension;
                    $model->logo = $company->logo;
                }
                $company->name= ucfirst($company->name);
                $company->save() ? $retData['msgType'] = "SUC" : $retData['msgType'] = "ERR";
                $retData['msgType'] === "SUC" ? $msg = "Company has been added successfully" : $msg = "Unable to store data this time";
                $retData['msg'] = $msg;
                $retData['companyId'] = (string) $company->_id;
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->getSession()->setFlash('success', 'New company has been added to datababse.');
                return $retData;
            }
            return $this->render('detail', ['model' => $model]);
        } else {
            throw new ForbiddenHttpException("You are not allowed to access this page.");
        }
    }

    public function actionCompanyValidation() {
        $model = new CompanyForm();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model->name= ucfirst($model->name);
            return ActiveForm::validate($model);
        }
    }

}

?>