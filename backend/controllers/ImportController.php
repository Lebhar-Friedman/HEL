<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;

/**
 * Import controller
 */
class ImportController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['upload-csv', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'upload-csv' => ['post', 'ajax'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays upload csv page.
     *
     * @return string
     */
    public function actionIndex() {
        return $this->render('index');
    }

    /**
     * Displays upload csv file and save data.
     *
     * @return array
     */
    public function actionUploadCsv() {
        if (Yii::$app->request->isAjax && Yii::$app->request->post()) {
            echo json_encode(Yii::$app->request->post());
            $model = new \common\models\UploadForm();
            $model->load(Yii::$app->request->post());
            $model->file = \yii\web\UploadedFile::getInstance($model, 'file');
            $model->file->name = 'import.' . $model->file->extension;

            if ($model->upload('uploads/import/')) {
                if(Yii::$app->request->post('import_type')=='company'){
                    
                }elseif (Yii::$app->request->post('import_type')=='event') {
                    
                } else {
                    
                }
            }
        }
    }

}
