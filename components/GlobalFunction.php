<?php

namespace components;

use Yii;
use app\common\models\Countries;
use app\common\models\User;
use app\components\FreshBooksRequest;

class GlobalFunction {

    public static function getUserRoles() {
        return [User::ROLE_ADMIN => 'Admin',
            User::ROLE_USER => 'user',];
    }

//    ,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,
    public static function getMonths() {
        return ['01' => 'Jan',
            '02' => 'Feb',
            '03' => 'Mar',
            '04' => 'Apr',
            '05' => 'May',
            '06' => 'Jun',
            '07' => 'Jul',
            '08' => 'Aug',
            '09' => 'Sep',
            '10' => 'Oct',
            '11' => 'Nov',
            '12' => 'Dec'];
    }

    public static function getYears() {
        $curYear = date("Y");
        $yTill = $curYear + 10;
        for ($i = $curYear; $i <= $yTill; $i++)
            $yearArr[$i] = $i;

        return $yearArr;
    }

    public static function getDates() {
        for ($i = 1; $i <= 31; $i++) {
            $value = $i;
            if ($i < 10) {
                $value = str_pad($i, 2, "0", STR_PAD_LEFT);
            }
            $dateArr[$value] = $value;
        }
        return $dateArr;
    }

    public function getData($className, $whereParams) {
        $query = $className::find();
        if ($whereParams) {
            $query->where($whereParams);
        }
        return $query->orderBy(['created' => SORT_DESC])->all();
    }

    public static function findModel($className, $condition) {
        if (($model = $className::findOne($condition)) !== null) {
            return $model;
        } else {
            return false;
        }
    }

    public static function sendMail($params) {
        $emailTo = $params['emailTo'];
        $message = $params['message'];
        $subject = $params['subject'];
        $layout = $params['layout'];
        $module = ''; //$params['module'];
        $files = (isset($params['files']) ? $params['files'] : []);
        $emailFrom = ['admin@healthevents.com' => 'Health Events Live']; //'4link@4linkadmin.com';

        $message = Yii::$app->mailer->compose($layout, ['message' => $message, 'module' => $module])
                ->setFrom($emailFrom)
                ->setTo($emailTo)
                ->setSubject($subject);

        foreach ($files as $key => $value) {
            $message->attach($key, ['fileName' => $value]);
        }

        if (isset($params['cc'])) {
            $message->setCc($params['cc']);
        }

        $message->send();
        return $message;
    }

    public static function getListing($params) {
        $className = (isset($params['className']) ? $params['className'] : '');
        $pagination = (isset($params['pagination']) ? $params['pagination'] : '');
        $whereParams = (isset($params['whereParams']) ? $params['whereParams'] : '');
        $filterWhereParam = (isset($params['filterWhereParam']) ? $params['filterWhereParam'] : '');
        $selectParams = (isset($params['selectParams']) ? $params['selectParams'] : '');
        $nameS = (isset($params['nameS'])) ? (!is_array($params['nameS']) ? trim($params['nameS']) : $params['nameS']) : '';
        $sort = (isset($params['sort']) ? $params['sort'] : '');
        $distinct = (isset($params['distinct']) ? $params['distinct'] : '');

        if ($sort != "") {
            if ($sort[0] == '-') {
                $sortBy = SORT_DESC;
                $sortField = ltrim($sort, "-");
                //echo $sortField; exit;
                $sortOrder = [$sortField => SORT_DESC];
            } else {
                $sortBy = SORT_ASC;
                $sortField = $sort;
                $sortOrder = [$sortField => SORT_ASC];
            }
        } else {
            $sortOrder = ['created' => SORT_DESC];
        }

        $query = $className::find();
        $query->select($selectParams);

        if ($whereParams != '')
            $query->andWhere($whereParams);
        if ($filterWhereParam != '') {
            $query->andFilterWhere($filterWhereParam);
        }

        if (is_array($nameS)) {
            foreach ($nameS as $key => $value) {
                $query->andWhere(['LIKE', $key, trim($value)]);
            }
        } elseif ($nameS != '') {
            if (preg_match('/\s/', $nameS)) {
                $nameArr = explode(" ", $nameS);
                $query->andWhere(['or', ['LIKE', 'first_name', $nameArr[0], ['LIKE', 'last_name', $nameArr[1]]]]);
            } else {
                //$query->where[0] ='and';
                //$query->where[1] = ['or', ['LIKE', 'first_name', $nameS], ['LIKE', 'last_name', $nameS]];
                $query->andWhere(['or', ['LIKE', 'first_name', $nameS], ['LIKE', 'last_name', $nameS]]);
            }
        }

        if ($distinct != '') {
            $query->distinct($distinct);
        }

        if ($pagination)
            $query->offset($pagination->offset)->limit($pagination->limit);

        $query->orderBy($sortOrder);
        //$query->orderBy(['miles'=>SORT_DESC]);

        return $query->all();
    }

    public function getCount($params) {
        $className = (isset($params['className']) ? $params['className'] : '');
        $whereParams = (isset($params['whereParams']) ? $params['whereParams'] : '');
        $nameS = (isset($params['nameS']) ? $params['nameS'] : '');
        $distinct = (isset($params['distinct']) ? $params['distinct'] : '');

        $query = $className::find();
        if ($whereParams != '')
            $query->andWhere($whereParams);
        if (is_array($nameS)) {
            foreach ($nameS as $key => $value) {
                $query->andWhere(['LIKE', $key, trim($value)]);
            }
        } elseif ($nameS != '') {
            if (preg_match('/\s/', $nameS)) {
                $nameArr = explode(" ", $nameS);
                $query->andWhere(['or', ['LIKE', 'first_name', $nameArr[0], ['LIKE', 'last_name', $nameArr[1]]]]);
            } else {
                //$query->where[0] ='and';
                //$query->where[1] = ['or', ['LIKE', 'first_name', $nameS], ['LIKE', 'last_name', $nameS]];
                $query->andWhere(['or', ['LIKE', 'first_name', $nameS], ['LIKE', 'last_name', $nameS]]);
            }
        }

        if ($distinct != '') {
            $query->distinct($distinct);
        }

        return $query->count();
    }

    public static function modelErrorsToString($errors) {
        $errorString = '';
        foreach ($errors as $error) {
            $errorString = $errorString . $error[0] . '<br>';
        }
        return $errorString;
    }

    public static function dateDiff($d1, $d2, $date = FALSE) {
        $d1 = str_replace('/', '-', $d1);
        $d2 = str_replace('/', '-', $d2);
        $date1 = new \DateTime($d1);
        $date2 = new \DateTime($d2);
        $diff = date_diff($date1, $date2);
        if ($date) {
            return $diff;
        }
        return $diff->format("%a");
    }

    public static function getLongLat($location) {
        $anAddress = $location->street . " " . $location->city . " " . $location->state . " " . $location->zip;
        $url = 'http://maps.googleapis.com/maps/api/geocode/json?address=' . rawurlencode($anAddress);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $json = curl_exec($curl);
        curl_close($curl); //echo $anAddress.'<br>'.$json;
        $mapData = json_decode($json);
        if ($mapData && $mapData->status == 'OK') {
            return ['lat' => $mapData->results[0]->geometry->location->lat, 'long' => $mapData->results[0]->geometry->location->lng];
        }
        return FALSE;
    }

    public static function getLongLatFromZip($zip) {
        $url = 'http://maps.googleapis.com/maps/api/geocode/json?address=' . $zip;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $json = curl_exec($curl);
        curl_close($curl); //echo $anAddress.'<br>'.$json;
        $mapData = json_decode($json);
        if ($mapData && $mapData->status == 'OK') {
            return ['lat' => $mapData->results[0]->geometry->location->lat, 'long' => $mapData->results[0]->geometry->location->lng];
        }
        return FALSE;
    }

    public static function getZipFromLongLat($long,$lat) {
        $url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng=' . $lat . ','. $long ;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $json = curl_exec($curl);
        curl_close($curl); //echo $anAddress.'<br>'.$json;
        $mapData = json_decode($json);
        if ($mapData && $mapData->status == 'OK') {
            foreach ($mapData->results[0]->address_components as  $component){
                if(in_array('postal_code', $component->types) ){
                    return $component->long_name;
                }
            }
        }
        return FALSE;
    }

    public static function getDate($format, $mongoDate) {
        $datetime = $mongoDate->toDateTime();
        $datetime->setTimezone(new \DateTimeZone(\Yii::$app->timeZone));
        if (empty($format)) {
            $format = DATE_ATOM;
        }
        return $datetime->format($format);
    }

// end class
}
