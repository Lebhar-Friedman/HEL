<?php

namespace backend\controllers;

use backend\models\CompanyForm;
use common\models\Event;
use common\models\Company;
use common\models\Categories;
use common\models\SubCategories;
use Yii;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\filters\AccessControl;

class EventController extends Controller {

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
        $query = Event::find();
        $eventTerm = urldecode(Yii::$app->request->get('eventTerm'));
        $eventFrom = urldecode(Yii::$app->request->get('eventFrom'));
        $eventTo = urldecode(Yii::$app->request->get('eventTo'));
        $eventCompany = urldecode(Yii::$app->request->get('eventCompany'));
        $eventCategory = urldecode(Yii::$app->request->get('eventCategory'));
        $eventSubCategory = urldecode(Yii::$app->request->get('eventSubCategory'));
        if ($eventTerm !== '') {
            $query->andWhere(['like', 'title', $eventTerm]);
        }
//        var_dump($eventFrom);
//        var_dump(date('m/d/Y',strtotime($eventFrom)));
//        if($eventFrom != ''){
//            $query=$query->andWhere(['=','date_start', date('m/d/Y',strtotime($eventFrom))]);
//        }
//        if($eventTo != ''){
//            $query=$query->andWhere(['=','date_end', date('m/d/Y',strtotime($eventTo))]);
//        }
        if ($eventCompany !== '-1' && $eventCompany !== '') {
            $query->andWhere(['company' => $eventCompany]);
        }
        if ($eventCategory !== '-1' && $eventCategory !== '') {
            $query = $query->andWhere(['categories' => $eventCategory]);
        }
        if ($eventSubCategory !== '-1' && $eventSubCategory !== '') {
            $query = $query->andWhere(['sub_categories' => $eventSubCategory]);
        }
        $count = $query->count();

        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => (10)]);
        $events = $query->offset($pagination->offset)->limit($pagination->limit)->orderBy(['updated_at' => SORT_DESC])->all();

        $companies = Company::CompanyList();
        $categories = Categories::CategoryList();
        $sub_categories = SubCategories::SubCategoryList();
        return $this->render('index', ['events' => $events, 'companies' => $companies, 'categories' => $categories, 'sub_categories' => $sub_categories, 'pagination' => $pagination, 'total' => $count]);
    }

    public function actionDelete() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if (!($request->isPost && $request->isAjax)) {
            throw new ForbiddenHttpException("You are not allowed to access this page.");
        }
        $request = Yii::$app->request->post();
        $event_id = $request['eid'];
        $model = Event::findOne($event_id);
        $retData = array();
        if ($model && $model->delete()) {
            $retData['msgType'] = "SUC";
            $retData['msg'] = "Event successfully deleted";
        } else {
            $retData['msgType'] = "ERR";
            $retData['msg'] = "Can not delete the event at this time.";
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
            $event = Event::findOne(['_id' => new \MongoDB\BSON\ObjectID($id)]);
            $model = $locations = NULL;

            if (count($event) > 0) {
                $request = Yii::$app->request;
                $model = new \backend\models\EventForm();
                $model->attributes = $event->attributes;
                $model->eid = $event->_id;
                $locations = \common\models\Location::findAll(['_id' => Event::findEventLocationsIDs($event->_id)]);

                if ($request->isPost && $request->isAjax) {
                    $model->load($request->post());
                }
            }
            return $this->render('detail', ['model' => $model, 'locations' => $locations]);
        } else {
            throw new ForbiddenHttpException("You are not allowed to access this page.");
        }
    }

}

?>