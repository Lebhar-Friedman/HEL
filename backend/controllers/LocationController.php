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
        if ($eid !== '') {
            $locationIDs = Event::findEventLocationsIDs($eid);
            $query->andWhere(['in', '_id', $locationIDs]);
        }
        if ($keyword !== '') {
            $query->orWhere(['like', 'street', $keyword]);
            $query->orWhere(['like', 'city', $keyword]);
            $query->orWhere(['like', 'state', $keyword]);
            $query->orWhere(['like', 'zip', $keyword]);
            $query->orWhere(['=', 'location_id', intval($keyword)]);
        }
        if ($company !== '-1' && $company !== '') {
            $query->andWhere(['company' => $company]);
        }
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => (10)]);
        $locations = $query->offset($pagination->offset)->limit($pagination->limit)->orderBy(['updated_at' => SORT_DESC])->all();
        $companies = Company::CompanyList();

        return $this->render('index', ['locations' => $locations, 'companies' => $companies, 'pagination' => $pagination, 'total' => $count]);
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
            Event::deleteLocationFromEvents($model);
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
        if (Event::deleteAll(['_id' => $event_ids])) {
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
        if (Event::updateAll(['is_post' => true], ['_id' => $event_ids])) {
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
            $location = Location::findOne(['_id' => new \MongoDB\BSON\ObjectID($id)]);
            $model = $locations = NULL;
            $companies = Company::CompanyList();
            $companyList = [];
            foreach ($companies as $c) {
                $companyList[$c->company_number]=$c->name;
            }

            if (count($location) > 0) {
                $request = Yii::$app->request;
                $model = new \backend\models\LocationForm();
                $model->attributes = $location->attributes;
                $model->id = $location->_id;

                if ($request->isPost) {
                    $model->load($request->post());
                    if ($model->saveLocation()) {
                        $location->attributes = $model->attributes;
                        Event::updateLocationInEvents($location);
                        Yii::$app->getSession()->setFlash('success', 'Location has been updated successfully.');
                    }
                }
            }
            return $this->render('detail', ['detail' => $location, 'model' => $model, 'companies' => $companyList]);
        } else {
            throw new ForbiddenHttpException("You are not allowed to access this page.");
        }
    }

}

?>