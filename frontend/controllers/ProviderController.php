<?php

namespace frontend\controllers;

use common\models\Company;
use MongoDB\Driver\Exception\InvalidArgumentException;
use Yii;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

class ProviderController extends Controller {

    public function actionProfile() {
        $p_id = urldecode(Yii::$app->request->get('pid'));
        try {
            $company = Company::findOne(['_id' => new \MongoDB\BSON\ObjectID($p_id)]);
        } catch (InvalidArgumentException $ex) {
            throw new ForbiddenHttpException("Please provide correct ID of Provider.");
        }
        return $this->render('profile', ['company' => $company]);
    }

}
