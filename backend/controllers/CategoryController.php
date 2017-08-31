<?php

namespace backend\controllers;

use backend\models\CategoryForm;
use backend\models\SubCategoryForm;
use common\models\Categories;
use common\models\SubCategories;
use common\models\Event;
use common\models\Location;
use yii\helpers\ArrayHelper;
use Yii;
use yii\data\Pagination;
use yii\helpers\BaseUrl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;
use yii\filters\AccessControl;

class CategoryController extends Controller {

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
        $sub_categories_model = new SubCategoryForm();
        $categories_model = new CategoryForm();
        $query = Categories::find();
        $count = $query->count();
        $categories = $query->orderBy(['updated_at' => SORT_DESC])->all();
        $query1 = SubCategories::find();
        $count1 = $query1->count();
        $sub_categories_list = $query1->orderBy(['updated_at' => SORT_DESC])->all();

        return $this->render('index', ['categories' => $categories, 'categories_model' => $categories_model, 'sub_categories_model' => $sub_categories_model, 'sub_categories_list' => $sub_categories_list]);
    }

    public function actionAddSubCategory() {
        $model = new SubCategories();
        $model1 = new SubCategoryForm();
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if ($request->isPost && $request->isAjax) {
            $model1->load($request->post());
            $model->name = $model1->name;
            if ($model->save()) {
                $retData['msgType'] = "SUC";
                $retData['msg'] = "Category successfully added";
            } else {
                $retData['msgType'] = "ERR";
                $retData['msg'] = "Can not add the category at this time.";
            }
            return $retData;
        }
        //$sub_categories = $query1->orderBy(['updated_at' => SORT_DESC])->all();
        //return $this->render('index', ['categories' => $categories, 'sub_categories' => $sub_categories]);
    }

    public function actionUpdateSubCategory() {
        $model1 = new SubCategoryForm();
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if ($request->isPost && $request->isAjax) {
            $model1->load($request->post());
            //print_r($request->post());die;
            $model = SubCategories::findOne(['_id' => new \MongoDB\BSON\ObjectID($model1->sub_category_id)]);
            $model->name = $model1->name;

            if ($model->update()) {
                $retData['msgType'] = "SUC";
                $retData['msg'] = "Category successfully updated";
            } else {
                $retData['msgType'] = "ERR";
                $retData['msg'] = "Category successfully updated";
            }
            return $retData;
        }
        //$sub_categories = $query1->orderBy(['updated_at' => SORT_DESC])->all();
        //return $this->render('index', ['categories' => $categories, 'sub_categories' => $sub_categories]);
    }

    public function actionSubCategoryDelete() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if (!($request->isPost && $request->isAjax)) {
            throw new ForbiddenHttpException("You are not allowed to access this page.");
        }
        $request = Yii::$app->request->post();
        $sub_cat_id = $request['sub_cat_id'];
        $model = SubCategories::findOne(['_id' => new \MongoDB\BSON\ObjectID($sub_cat_id)]);
        $Is_Categories = Categories::findAll(['sub_categories' => ['$in' => [$model->name]]]);
        if (empty($Is_Categories)) {
            $retData = array();
            if ($model && $model->delete()) {
                $retData['msgType'] = "SUC";
                $retData['msg'] = "Sub category successfully deleted.";
            } else {
                $retData['msgType'] = "ERR";
                $retData['msg'] = "Can not delete the sub category at this time.";
            }
        }else {
                $retData['msgType'] = "INF";
                $retData['msg'] = "Sub category is linked.";
        }
        return $retData;
    }

    public function actionAddSubCategoryLink() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if (!($request->isPost && $request->isAjax)) {
            throw new ForbiddenHttpException("You are not allowed to access this page.");
        }
        $request = Yii::$app->request->post();
        $category_id = $request['category_id'];
        $sub_categories = $request['sub_categories'];
        $model = Categories::findOne(['_id' => new \MongoDB\BSON\ObjectID($category_id)]);
        $retData = array();
        if (!empty($model)) {
            if (isset($model->sub_categories) && !ArrayHelper::isIn($sub_categories, $model->sub_categories)) {
                $model->sub_categories = ArrayHelper::merge($model->sub_categories, [$sub_categories]);
            } else if (isset($model->sub_categories) && ArrayHelper::isIn($sub_categories, $model->sub_categories)) {
                
            } else {
                $model->sub_categories = [$sub_categories];
            }
            $model->save();
            $retData['msgType'] = "SUC";
            $retData['msg'] = "Sub Category linked successfully!";
        } else {
            $retData['msgType'] = "ERR";
            $retData['msg'] = "!";
        }
        return $retData;
    }

    public function actionAddCategory() {
        $model = new Categories();
        $model1 = new CategoryForm();
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if ($request->isPost && $request->isAjax) {
            $model1->load($request->post());
            $model->name = $model1->name;
            if ($model->save()) {
                $retData['msgType'] = "SUC";
                $retData['msg'] = "Category successfully added";
            } else {
                $retData['msgType'] = "ERR";
                $retData['msg'] = "Can not add the category at this time.";
            }
            return $retData;
        }
        //$sub_categories = $query1->orderBy(['updated_at' => SORT_DESC])->all();
        //return $this->render('index', ['categories' => $categories, 'sub_categories' => $sub_categories]);
    }

    public function actionUpdateCategory() {
        $model1 = new CategoryForm();
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if ($request->isPost && $request->isAjax) {
            $model1->load($request->post());
            //print_r($request->post());die;
            $model = Categories::findOne(['_id' => new \MongoDB\BSON\ObjectID($model1->category_id)]);
            $model->name = $model1->name;

            if ($model->update()) {
                $retData['msgType'] = "SUC";
                $retData['msg'] = "Category successfully updated";
            } else {
                $retData['msgType'] = "ERR";
                $retData['msg'] = "Category successfully updated";
            }
            return $retData;
        }
        //$sub_categories = $query1->orderBy(['updated_at' => SORT_DESC])->all();
        //return $this->render('index', ['categories' => $categories, 'sub_categories' => $sub_categories]);
    }

    public function actionCategoryDelete() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if (!($request->isPost && $request->isAjax)) {
            throw new ForbiddenHttpException("You are not allowed to access this page.");
        }
        $request = Yii::$app->request->post();
        $cat_id = $request['cat_id'];
        $model = Categories::findOne(['_id' => new \MongoDB\BSON\ObjectID($cat_id)]);
        $retData = array();
        if ($model && $model->delete()) {
            $retData['msgType'] = "SUC";
            $retData['msg'] = "Company successfully deleted";
        } else {
            $retData['msgType'] = "ERR";
            $retData['msg'] = "Can not delete the company at this time.";
        }
        return $retData;
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
                $model->company_number = $company->company_number;
                $image_name = $company->logo;
                $model->logo = UploadedFile::getInstance($model, 'logo');
                if (isset($model->logo) && $model->logo !== NULL && !empty($model->logo)) {
                    $image_name = str_replace(' ', '_', $model->name . time());
                    $model->upload($image_name);
                    $image_name = $image_name . '.' . $model->logo->extension;
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
            $image_name = str_replace(' ', '_', $model->name . time());
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
        } else if ($request->get('cid') !== NULL) {
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
            ($logo !== null || !empty($logo)) ? unlink('uploads/' . $logo) : "";
        } else {
            $retData['msgType'] = "ERR";
            $retData['msg'] = "Can not delete the company at this time.";
        }
        return $retData;
    }

    public function actionCategoryValidation() {
        $model = new CategoryForm();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model->name = ucfirst($model->name);
            return ActiveForm::validate($model);
        }
    }

    public function actionSubCategoryValidation() {
        $model = new SubCategoryForm();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model->name = ucfirst($model->name);
            return ActiveForm::validate($model);
        }
    }

    public function actionDeleteSubCategoryLink() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if (!($request->isPost && $request->isAjax)) {
            throw new ForbiddenHttpException("You are not allowed to access this page.");
        }
        $request = Yii::$app->request->post();
        $category_id = $request['category_id'];
        $sub_categories = $request['sub_categories'];
        $model = Categories::findOne(['_id' => new \MongoDB\BSON\ObjectID($category_id)]);
        $retData = array();
        if (!empty($model)) {
            if (isset($model->sub_categories) && ArrayHelper::isIn($sub_categories, $model->sub_categories)) {
                $model->sub_categories = array_diff($model->sub_categories, [$sub_categories]);
            }
            $model->save();
            $retData['msgType'] = "SUC";
            $retData['msg'] = "Sub Category linked successfully!";
        } else {
            $retData['msgType'] = "ERR";
            $retData['msg'] = "!";
        }
        return $retData;
    }

}

?>