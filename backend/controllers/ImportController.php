<?php

namespace backend\controllers;

use backend\models\CompanyForm;
use backend\models\EventForm;
use common\models\Company;
use common\models\Event;
use common\models\Location;
use common\models\UploadForm;
use common\models\Values;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;
use function GuzzleHttp\json_encode;

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
        $lastImported = Values::getValueByName('import');
        if (count($lastImported) > 0) {
            if (!empty($lastImported) && $lastImported->value_type == 'events') {
                $events = Event::findAll(['_id' => $lastImported->value]);
            } elseif (!empty($lastImported) && $lastImported->value_type == 'companies') {
                $companies = Company::findAll(['_id' => $lastImported->value]);
                foreach ($companies as &$company) {
                    $totalLocations = Location::find()->where(['company' => $company->name])->count();
                    $totalEvents = Event::find()->where(['company' => $company->name])->count();
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
        set_time_limit(30000);
        ini_set('memory_limit', '-1');
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->request->isAjax && Yii::$app->request->post()) {
            ini_set("auto_detect_line_endings", true);
            $model = new UploadForm();
            $model->load(Yii::$app->request->post());
            $model->file = UploadedFile::getInstance($model, 'file');
            $model->file->name = Yii::$app->request->post('import_type') . '.' . $model->file->extension;

            if ($model->upload('uploads/import/')) {
//                fastcgi_finish_request();
                session_write_close();

                if (Yii::$app->request->post('import_type') == 'company') {
                    $result = CompanyForm::saveCSV($model->file->name);
                    exit($result);
                } elseif (Yii::$app->request->post('import_type') == 'event') {
                    $result = EventForm::saveCSV($model->file->name);
                    exit($result);
                } else {
                    exit(json_encode(['msgType' => 'ERR', 'msg' => 'Invalid import type']));
                }
            }
        }
    }

    public function actionServerAlive() {
        exit(json_encode(['msgType' => 'SUC', 'msg' => 'Alive']));
    }

// end class
}
