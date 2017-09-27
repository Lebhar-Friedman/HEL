<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

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
//                        'actions' => ['upload-csv', 'index'],
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
        $companies = $events = [];
        $lastImported = \common\models\Values::getValueByName('import');
        if (count($lastImported) > 0) {
            if (!empty($lastImported) && $lastImported->value_type == 'events') {
                $events = \common\models\Event::findAll(['_id' => $lastImported->value]);
            } elseif (!empty($lastImported) && $lastImported->value_type == 'companies') {
                $companies = \common\models\Company::findAll(['_id' => $lastImported->value]);
                foreach ($companies as &$company) {
                    $totalLocations = \common\models\Location::find()->where(['company' => $company->name])->count();
                    $totalEvents = \common\models\Event::find()->where(['company' => $company->name])->count();
                    $company['t_locations'] = $totalLocations;
                    $company['t_events'] = $totalEvents;
                }
            }
        }
        return $this->render('index', ['events' => $events, 'companies' => $companies]);
    }

    /**
     * Displays upload csv file and save data.
     *
     * @return array
     */
    public function actionUploadCsv() {
        set_time_limit ( 30000 );
        if (Yii::$app->request->isAjax && Yii::$app->request->post()) {
            ini_set("auto_detect_line_endings", true);
            $model = new \common\models\UploadForm();
            $model->load(Yii::$app->request->post());
            $model->file = \yii\web\UploadedFile::getInstance($model, 'file');
            $model->file->name = Yii::$app->request->post('import_type') . '.' . $model->file->extension;

            if ($model->upload('uploads/import/')) {
                if (Yii::$app->request->post('import_type') == 'company') {
                    $result = \backend\models\CompanyForm::saveCSV($model->file->name);
                    exit($result);
                } elseif (Yii::$app->request->post('import_type') == 'event') {
                    $result = \backend\models\EventForm::saveCSV($model->file->name);
                    exit($result);
                } else {
                    exit(json_encode(['msgType' => 'ERR', 'msg' => 'Invalid import type']));
                }
            }
        }
    }

// end class
}
