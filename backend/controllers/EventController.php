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

class EventController extends Controller {

    public function actionIndex() {
        $query = Event::find();
        $eventTerm = urldecode(Yii::$app->request->get('eventTerm'));
        $eventFrom = urldecode(Yii::$app->request->get('eventFrom'));
        $eventTo = urldecode(Yii::$app->request->get('eventTo'));
        $eventCompany = urldecode(Yii::$app->request->get('eventCompany'));
        $eventCategory = urldecode(Yii::$app->request->get('eventCategory'));
        $eventSubCategory = urldecode(Yii::$app->request->get('eventSubCategory'));
        if($eventTerm !== ''){
            $query->andWhere(['like','title', $eventTerm]);
        }
//        if($eventFrom != ''){
//            $query=$query->andWhere(['=','date_start', $eventFrom]);
//        }
//        if($eventTo != ''){
//            $query=$query->andWhere(['=','date_end', $eventTo]);
//        }
        if($eventCompany !== '-1' && $eventCompany !== ''){
            $query->andWhere(['=','company', $eventCompany]);
        }
        if($eventCategory !== '-1' && $eventCategory !== ''){
            $query = $query->andWhere(['=','categories', $eventCategory]);
        }
        if($eventSubCategory !== '-1' && $eventSubCategory !== ''){
            $query = $query->andWhere(['=','sub_categories', $eventSubCategory]);
        }
        $count = $query->count();
        
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => (10)]);
        $events = $query->offset($pagination->offset)->limit($pagination->limit)->orderBy(['updated_at' => SORT_DESC])->all();
        
        $companies = Company::CompanyList();
        $categories = Categories::CategoryList();
        $sub_categories = SubCategories::SubCategoryList();
        return $this->render('index', ['events' => $events, 'companies' => $companies, 'categories' => $categories, 'sub_categories' => $sub_categories, 'pagination' => $pagination, 'total' => $count]);
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