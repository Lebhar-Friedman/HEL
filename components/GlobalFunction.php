<?php

namespace components;

use app\common\models\User;
use DateTime;
use DateTimeZone;
use Yii;
use function GuzzleHttp\json_decode;

class GlobalFunction {

    public static function getUserRoles() {
        return [User::ROLE_ADMIN => 'Admin',
            User::ROLE_USER => 'user',];
    }

//    ,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,
    public static function getMonths() {
        return ['01' => 'Jan.',
            '02' => 'Feb.',
            '03' => 'Mar.',
            '04' => 'Apr.',
            '05' => 'May.',
            '06' => 'June.',
            '07' => 'July.',
            '08' => 'Aug.',
            '09' => 'Sept.',
            '10' => 'Oct.',
            '11' => 'Nov.',
            '12' => 'Dec.'];
    }
    
    public static function getAbbreviatedMonth($month_number){
        foreach( GlobalFunction::getMonths() as $key => $value){
            if($key == $month_number){
                return $value;
            }
        }
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
        $date1 = new DateTime($d1);
        $date2 = new DateTime($d2);
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

    public static function getZipFromLongLat($long, $lat) {
        $url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng=' . $lat . ',' . $long;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $json = curl_exec($curl);
        curl_close($curl); //echo $anAddress.'<br>'.$json;
        $mapData = json_decode($json);
        if ($mapData && $mapData->status == 'OK') {
            foreach ($mapData->results[0]->address_components as $component) {
                if (in_array('postal_code', $component->types)) {
                    return $component->long_name;
                }
            }
        }
        return FALSE;
    }

    public static function getDate($format, $mongoDate) {
        $datetime = $mongoDate->toDateTime();
        $datetime->setTimezone(new DateTimeZone(\Yii::$app->timeZone));
        if (empty($format)) {
            $format = DATE_ATOM;
        }
        return $datetime->format($format);
    }

    public static function getEventDate($start, $end) {
        $start_date = GlobalFunction::getDate('Y-m-d', $start);
        $end_date = GlobalFunction::getDate('Y-m-d', $end);
        $diff = GlobalFunction::dateDiff($start_date, $end_date, false);
        if ($diff < 1) {
            return GlobalFunction::printableDate($start);
        } else {
            $start_month = explode('-', $start_date);
            $end_month = explode('-', $end_date);
            $start_month[1] != $end_month[1] ? $ret = GlobalFunction::printableDate($start) . ' - ' . GlobalFunction::printableDate($end) : $ret = GlobalFunction::printableDate($start) . ' - ' . GlobalFunction::printableDate($end);
            return $ret;
        }
    }

    public static function printableDate($date_event) {
        
        $date_arr = explode(' ', GlobalFunction::getDate('m d', $date_event));
        $month = GlobalFunction::getAbbreviatedMonth($date_arr[0]);
        return $month . ' ' . $date_arr[1];
        
    }

    public static function distanceBetweenPoints($lat1, $lon1, $lat2, $lon2) {

        $pi80 = M_PI / 180;
        $lat1 *= $pi80;
        $lon1 *= $pi80;
        $lat2 *= $pi80;
        $lon2 *= $pi80;

        $r = 6372.797; // mean radius of Earth in km
        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;
        $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlon / 2) * sin($dlon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $km = $r * $c;

        return $km * 0.621371;
    }

    public static function locationsInRadius($lat, $lng, $locations, $radius) {
        $locations_to_return = array();
        foreach ($locations as $location) {
            if (round(GlobalFunction::distanceBetweenPoints($lat, $lng, $location['geometry']['coordinates'][1], $location['geometry']['coordinates'][0]), 1) > $radius) {
                continue;
            } else {
                $locations_to_return[] = $location;
            }
        }
        $locations_to_return = GlobalFunction::deleteDuplicateLocations($locations_to_return);
        return $locations_to_return;
    }

    public static function nearestLocation($lat, $lng, $locations) {
        $location_to_return = array();
        $nearest = 999999999;
        foreach ($locations as $location) {
            $distance = round(GlobalFunction::distanceBetweenPoints($lat, $lng, $location['geometry']['coordinates'][1], $location['geometry']['coordinates'][0]), 2);
            if ($distance < $nearest) {
                $location_to_return = $location;
                $nearest = $distance;
            }
        }
        return $location_to_return;
    }

    public static function deleteDuplicateLocations($locations) {
//        $epsilon = 0.0000001;
        $locations_to_return = array();
        foreach ($locations as $location) {
            if (sizeof($locations_to_return) == 0) {
                $locations_to_return[] = $location;
            } else {
                $is_exist = false;
                foreach ($locations_to_return as $location_to_return) {
//                    if( (abs($location_to_return['geometry']['coordinates'][0] - $location['geometry']['coordinates'][0]) < $epsilon) && (abs($location_to_return['geometry']['coordinates'][1] - $location['geometry']['coordinates'][1]) < $epsilon)) {
//                        $is_exist = true;
//                    }
                    if ($location['store_number'] === $location_to_return['store_number']) {
                        $is_exist = true;
                    }
                }
                if ($is_exist)
                    continue;
                $locations_to_return[] = $location;
            }
        }
        return $locations_to_return;
    }

// end class
}
