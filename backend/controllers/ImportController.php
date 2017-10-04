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
                session_write_close();
                $this->respondOK(json_encode(['msgType' => 'VALID', 'msg' => 'File is in process']));

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

    public function respondOK($text = null) {
        // check if fastcgi_finish_request is callable
        if (is_callable('fastcgi_finish_request')) {
            if ($text !== null) {
                echo $text;
            }
            /*
             * http://stackoverflow.com/a/38918192
             * This works in Nginx but the next approach not
             */
            session_write_close();
            fastcgi_finish_request();

            return;
        }

        ignore_user_abort(true);

        ob_start();

        if ($text !== null) {
            echo $text;
        }

        $serverProtocol = filter_input(INPUT_SERVER, 'SERVER_PROTOCOL', FILTER_SANITIZE_STRING);
        header($serverProtocol . ' 200 OK');
        // Disable compression (in case content length is compressed).
        header('Content-Encoding: none');
        header('Content-Length: ' . ob_get_length());

        // Close the connection.
        header('Connection: close');

        ob_end_flush();
        ob_flush();
        flush();
        sleep(5);
    }

    public function actionServerAlive() {
        $import_status = Values::getValueByName('import_status');
        if ($import_status !== NULL) {
            if ($import_status->value_type == 'suc_csv_uploaded') {
                $import_status_clone = $import_status;
                $import_status->delete();
                exit(json_encode(['msgType' => 'SUC', 'msg' => $import_status_clone->status]));
            } else if ($import_status->value_type == 'error_on_saving') {
                $import_status_clone = $import_status;
                $import_status->delete();
                exit(json_encode(['msgType' => 'ERR', 'msg' => $import_status_clone->status]));
            } else if ($import_status->value_type == 'error_on_validation') {
                $import_status_clone = $import_status;
                $import_status->delete();
                exit(json_encode(['msgType' => 'ERR', 'msg' => $import_status_clone->status . ' at row '. $import_status_clone->value]));
            } else {
                $completed = $import_status->value;
                $total = $import_status->total_rows;
                $percentage =  round(($completed / (float) $total ) * 100, 2);
                if($percentage == 100){
                    $percentage =99;
                }
                exit(json_encode(['msgType' => 'PROC', 'msg' => $percentage]));
            }
        } else {
            exit(json_encode(['msgType' => 'NOT_EXIST', 'msg' => 'No file is pending']));
        }
    }

// end class
}
