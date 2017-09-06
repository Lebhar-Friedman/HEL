<?php

namespace backend\controllers;

use backend\models\CompanyForm;
use common\models\Company;
use common\models\Event;
use common\models\Location;
use Yii;
use yii\data\Pagination;
use yii\helpers\BaseUrl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;
use yii\filters\AccessControl;

class CompanyController extends Controller {
    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex() {
        $query = Company::find();
        $companySearch = urldecode(Yii::$app->request->get('name'));
        if ($companySearch !== '') {
            $query = $query->where(['like', 'name', $companySearch]);
        }
        $count = $query->count();

        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => (10)]);
        $companies = $query->offset($pagination->offset)->limit($pagination->limit)->orderBy(['updated_at' => SORT_DESC])->all();
        $companies_arr = array();
        foreach ($companies as $company) {
            $locations = Location::find()->where(['company' => $company->company_number])->count();
            $events = Event::find()->where(['company' => $company->company_number])->count();
            $company['t_locations'] = $locations;
            $company['t_events'] = $events;
            $companies_arr[] = $company;
        }
        return $this->render('index', ['companies' => $companies_arr, 'pagination' => $pagination, 'total' => $count]);
    }

    public function actionDetail($id = "") {
        if (!(!Yii::$app->user->isGuest && Yii::$app->user->identity->role === 'admin')) {
            throw new ForbiddenHttpException("You are not allowed to access this page.");
        }
        $request = Yii::$app->request;

        $model = new CompanyForm();
        if ($request->isPost && $request->isAjax) {
            $model->load($request->post());
            if (isset($model->c_id) && $model->c_id !== NULL && $model->c_id !== '') { //update case
                $company = Company::findOne($model->c_id);
//                $model->company_number = $company->company_number;
                $image_name = $company->logo;
                $model->logo = UploadedFile::getInstance($model, 'logo');
                if (isset($model->logo) && $model->logo !== NULL && !empty($model->logo)) {
                    $image_name = str_replace(' ','_',$model->name . time());
                    $model->upload($image_name);
                    $image_name=$image_name .'.'. $model->logo->extension;
                }
                $company->attributes = $model->attributes;
                $company->logo = $image_name;
                $company->update() ? $retData['msgType'] = "SUC" : $retData['msgType'] = "ERR";
                $retData['msgType'] === "SUC" ? $msg = "Company has been Updated successfully" : $msg = "Unable to Update Company data this time";
                $retData['msg'] = $msg;
//                $retData['msg']=$err;
                $retData['companyId'] = (string) $company->_id;
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->getSession()->setFlash('success', 'company has been Updated.');
                return $retData;
            }
            $model->logo = UploadedFile::getInstance($model, 'logo');
            $company = new Company();
            $company->attributes = $model->attributes;
            $image_name = str_replace(' ','_',$model->name . time());
            if (isset($model->logo) && $model->upload($image_name)) {
                $company->logo = $image_name . '.' . $model->logo->extension;
                $model->logo = $company->logo;
            }
            $company->name = ucfirst($company->name);
            $company->save() ? $retData['msgType'] = "SUC" : $retData['msgType'] = "ERR";
            $retData['msgType'] === "SUC" ? $msg = "Company has been added successfully" : $msg = "Unable to store data this time";
            $retData['msg'] = $msg;
            $retData['companyId'] = (string) $company->_id;
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->getSession()->setFlash('success', 'New company has been added to datababse.');
            return $retData;
        } else if($request->get('cid') !== NULL) {
            $request = $request->get();
            $company_id = $request['cid'];
            $commpany = Company::findOne($company_id);
            if (isset($commpany)) {
                $model->attributes = $commpany->attributes;
                $model->c_id = (string) $commpany->_id;
            }
        }
        return $this->render('detail', ['model' => $model]);
    }

    public function actionDelete() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if (!($request->isPost && $request->isAjax)) {
            throw new ForbiddenHttpException("You are not allowed to access this page.");
        }
        $request = Yii::$app->request->post();
        $company_id = $request['cid'];
        $model = Company::findOne($company_id);
        $retData = array();
        $logo = $model->logo;
        if ($model && $model->delete()) {
            $retData['msgType'] = "SUC";
            $retData['msg'] = "Company successfully deleted";
            ($logo !== null || !empty($logo)) ? unlink('uploads/' . $logo): "";
        } else {
            $retData['msgType'] = "ERR";
            $retData['msg'] = "Can not delete the company at this time.";
        }
        return $retData;
    }

    public function actionCompanyValidation() {
        $model = new CompanyForm();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model->name = ucfirst($model->name);
            return ActiveForm::validate($model);
        }
    }

}

?>