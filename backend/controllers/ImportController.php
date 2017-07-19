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
        $events = [];
        $importEvents = \common\models\Values::getValue('import', 'events');
        $importEvents = $importEvents->value;
        if (!empty($importEvents)) {
            $events = \common\models\Event::findAll(['_id' => $importEvents]);
        }
        return $this->render('index', ['events' => $events]);
    }

    /**
     * Displays upload csv file and save data.
     *
     * @return array
     */
    public function actionUploadCsv() {
        if (Yii::$app->request->isAjax && Yii::$app->request->post()) {
            $model = new \common\models\UploadForm();
            $model->load(Yii::$app->request->post());
            $model->file = \yii\web\UploadedFile::getInstance($model, 'file');
            $model->file->name = Yii::$app->request->post('import_type') . '.' . $model->file->extension;

            if ($model->upload('uploads/import/')) {
                if (Yii::$app->request->post('import_type') == 'company') {

                    exit(json_encode(['msgType' => 'SUC', 'msg' => 'Companies Added Successfully.']));
                } elseif (Yii::$app->request->post('import_type') == 'event') {
                    $result = \backend\models\EventForm::saveCSV($model->file->name);
                    exit($result);
                } else {
                    exit(json_encode(['msgType' => 'ERR', 'msg' => 'Invalid import type']));
                }
            }
        }
    }

    private function validateCompanyCSV() {
        $attributeMapArray = [
            'company name' => 'name',
            'contact name' => 'contact_name',
            'phone' => 'phone',
            'email' => 'email',
        ];

        $attributes = $result = [];
        $file = fopen("uploads/import/import.csv", "r");
        $headerRow = fgetcsv($file);

        if (!empty($headerRow)) {
            $rowNo = 1;
            $models = [];
            while (!feof($file)) {
                $rowNo++;
                $model = new \backend\models\CompanyForm();
                $model->scenario = 'create';
                $dataRow = fgetcsv($file);

                if (!empty($dataRow)) {
                    foreach ($headerRow as $key => $value) {
                        if (isset($attributeMapArray[$value])) {
                            $attributes[$attributeMapArray[$value]] = trim($dataRow[$key]);
                        } elseif (!empty($value)) {
                            fclose($file);
                            return ['result' => FALSE, 'msg' => '<b>Invalid field "' . $value . '" at Row ' . $rowNo . ' and Column ' . $key . '</b> <br>'];
                        }
                    }
                    $model->attributes = $attributes;
                    if (!$model->validate()) {//echo json_encode($model->getErrors());exit();
                        fclose($file);
                        return ['result' => FALSE, 'msg' => '<b>Following error occured at row ' . $rowNo . '</b> <br>' . \Component\GlobalFunction::modelErrorsToString($model->getErrors())];
                    }
                    $company = \common\models\Company::findOne(['name' => $model->name]);

                    if (count($company) > 0) {
                        $model->ma_id = $company->ma_id;
                        $model->scenario = 'update';
                    }
                    array_push($models, $model);
                }
            }
        }

        fclose($file);
        return ['result' => TRUE, 'models' => $models];
    }

// end class
}
