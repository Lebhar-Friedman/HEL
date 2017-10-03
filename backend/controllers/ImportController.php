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
        set_time_limit(30000);
        ini_set('memory_limit', '-1');
        if (Yii::$app->request->isAjax && Yii::$app->request->post()) {
            ini_set("auto_detect_line_endings", true);
            $model = new \common\models\UploadForm();
            $model->load(Yii::$app->request->post());
            $model->file = \yii\web\UploadedFile::getInstance($model, 'file');
            $model->file->name = Yii::$app->request->post('import_type') . '.' . $model->file->extension;

            if ($model->upload('uploads/import/')) {

//                $this->respondOK(json_encode(['msgType' => 'SUC', 'msg' => 'Successfully uploaded. wait for import.']));
                session_write_close();
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

// end class
}
