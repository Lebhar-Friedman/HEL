<?php

namespace backend\controllers;

use backend\models\CompanyForm;
use common\models\Location;
use common\models\Event;
use common\models\Company;
use Yii;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\filters\AccessControl;

class LocationController extends Controller {
    
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
        $query = Location::find();
        $eid = urldecode(Yii::$app->request->get('eid'));
        $keyword = urldecode(Yii::$app->request->get('keyword'));
        $company = urldecode(Yii::$app->request->get('company'));
        if($eid !== ''){
            $locationIDs = Event::findEventLocationsIDs($eid);
            $query->andWhere(['in','_id', $locationIDs]);
        }
        if($keyword !== ''){
            $query->orWhere(['like','street', $keyword]);
            $query->orWhere(['like','city', $keyword]);
            $query->orWhere(['like','state', $keyword]);
            $query->orWhere(['like','zip', $keyword]);
        }
        if($company !== '-1' && $company !== ''){
            $query->andWhere(['company'=> $company]);
        }
        $count = $query->count();        
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => (10)]);
        $locations = $query->offset($pagination->offset)->limit($pagination->limit)->orderBy(['updated_at' => SORT_DESC])->all();
        $companies = Company::CompanyList();
        
        return $this->render('index', ['locations' => $locations, 'companies'=>$companies, 'pagination' => $pagination, 'total' => $count]);
    }
    
    public function actionDelete() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if (!($request->isPost && $request->isAjax)) {
            throw new ForbiddenHttpException("You are not allowed to access this page.");
        }
        $request = Yii::$app->request->post();
        $location_id = $request['lid'];
        $model = Location::findOne($location_id);
        $retData = array();
        if ($model && $model->delete()) {
            $retData['msgType'] = "SUC";
            $retData['msg'] = "Location successfully deleted";
        } else {
            $retData['msgType'] = "ERR";
            $retData['msg'] = "Can not delete the location at this time.";
        }
        return $retData;
    }
    
    public function actionDeleteSelected() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if (!($request->isPost && $request->isAjax)) {
            throw new ForbiddenHttpException("You are not allowed to access this page.");
        }
        $request = Yii::$app->request->post();
        $event_ids = $request['eids'];
        $retData = array();
        if (Event::deleteAll(['_id'=>$event_ids])) {
            $retData['msgType'] = "SUC";
            $retData['msg'] = "Event successfully deleted";
        } else {
            $retData['msgType'] = "ERR";
            $retData['msg'] = "Can not delete the event at this time.";
        }
        return $retData;
    }
    
    public function actionPost() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if (!($request->isPost && $request->isAjax)) {
            throw new ForbiddenHttpException("You are not allowed to access this page.");
        }
        $request = Yii::$app->request->post();
        $event_id = $request['eid'];
        $model = Event::findOne($event_id);
        $model->is_post = true;
        $retData = array();
        if ($model && $model->update()) {
            $retData['msgType'] = "SUC";
            $retData['msg'] = "Event successfully posted";
        } else {
            $retData['msgType'] = "ERR";
            $retData['msg'] = "Can not post the event at this time.";
        }
        return $retData;
    }
    
    public function actionPostSelected() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if (!($request->isPost && $request->isAjax)) {
            throw new ForbiddenHttpException("You are not allowed to access this page.");
        }
        $request = Yii::$app->request->post();
        $event_ids = $request['eids'];
        $retData = array();
        if (Event::updateAll(['is_post'=>true],['_id'=>$event_ids])) {
            $retData['msgType'] = "SUC";
            $retData['msg'] = "Event successfully posted";
        } else {
            $retData['msgType'] = "ERR";
            $retData['msg'] = "Can not post the event at this time.";
        }
        return $retData;
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

                $image_name = rand(100, 5000);
                if (isset($model->logo) && $model->upload($image_name)) {
                    $company->logo = $image_name . '.' . $model->logo->extension;
                    $model->logo = $company->logo;
                }
                $company->save() ? $retData['msgType'] = "SUC" : $retData['msgType'] = "ERR";
                $retData['msgType'] === "SUC" ? $msg="Company has been added successfully" : $msg="Unable to store data this time";
                $retData['msg'] = $msg;
                $retData['companyId']=(string) $company->_id;
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->getSession()->setFlash('success', 'New company has been added to datababse.');
                return $retData;
            }
            return $this->render('detail', ['model' => $model]);
        }
        else {
            throw new ForbiddenHttpException("You are not allowed to access this page.");
        }
    }

}

?>