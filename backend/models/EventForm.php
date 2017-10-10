<?php

namespace backend\models;

use common\models\Company;
use common\models\Event;
use common\models\Location;
use common\models\Values;
use components\GlobalFunction;
use Exception;
use yii\base\Model;
use function GuzzleHttp\json_encode;

/**
 * Event form
 */
class EventForm extends Model {

    public $eid;
    public $title;
    public $company; // company number
    public $date_start;
    public $date_end;
    public $time_start;
    public $time_end;
    public $categories;
    public $sub_categories;
    public $location_models;
    public $price;
    public $description;
    public $is_post;
    public static $importedEvents;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            // username and password are both required
                [['title', 'company', 'description'], 'required'],
            // safe fields
            [['is_post', 'price', 'date_start', 'date_end', 'time_start', 'time_end', 'categories', 'sub_categories', 'location_models',], 'safe'],
            // string fields
            [['title', 'company', 'description'], 'string'],
                ['company', 'validateCompany']
        ];
    }

    public function validateCompany($attribute, $params) {
        $this->company = trim($this->company);
        $company = Company::find()->andWhere(['company_number' => $this->company])->one();        //print_r($company);die;
        if (count($company) > 0) {
            ;
        } else {
            $this->addError($attribute, 'This Company (' . $this->company . ') does not exist.');
        }
    }

    public static function getCsvAttributeMapArray() {
        return [// event attributes
            'event company id' => 'company',
            'event title' => 'title',
            'event date' => 'date_start',
            'event start time' => 'time_start',
            'event end time' => 'time_end',
            'price' => 'price',
            'categories' => 'categories',
            'sub categories' => 'sub_categories',
            'description' => 'description',
        ];
    }

    /**
     * Save csv data of events.
     *
     */
    public static function saveCSV($csv) {
        try {
            self::$importedEvents = [];
            $validate = EventForm::validateCSV($csv);
            $total_rows = $validate['row_number'];
//        echo json_encode($validate);
            if ($validate['result']) {
//                $models = $validate['models'];
//                $total_rows = sizeof($models);
//                $event_number = 0;
//                foreach ($models as $key => $model) {
//                    $event_number++;
//                    $locationForm = $model->location_models;
//                    $location = Location::findOne(['store_number' => $locationForm->store_number]);
//                    if (count($location) == 0) {
//                        $location = new Location();
//                    }
////                $latlong = \components\GlobalFunction::getLongLat($locationForm); //exit(print_r($latlong));
////                if ($latlong) {
////                    $location->geometry = ['type' => 'Point',
////                        'coordinates' => [$latlong['long'],
////                            $latlong['lat']]
////                    ];
////                }
//                    $location->attributes = $locationForm->attributes;
//                    Event::updateLocationInEvents($location);
//                    //echo '<br>' . json_encode($location->attributes);
//                    $location->save();
//                    self::saveCsvEvent($model, $location);
//                    Values::saveValue('import_status', 'saving', $event_number, '', $total_rows);
//                    unset($models[$key]);
//                }
                Values::saveValue('import', 'events', self::$importedEvents);
                Values::saveValue('import_status', 'suc_csv_uploaded', $total_rows + 1, 'All ' . $total_rows . ' Events were imported successfully.');
                return json_encode(['msgType' => 'SUC', 'msg' => 'All ' . $total_rows . ' Events were imported successfully.', 'validated' => 'CSV is validated Successfully.', 'importedEvents' => self::$importedEvents]);
            } else {
                Values::saveValue('import_status_last_error', 'error_on_saving', $validate['row_number'], $validate['msg']);
                return json_encode(['msgType' => 'ERR', 'msg' => $validate['msg']]);
            }
        } catch (Exception $e) {
//            echo 'Message: ' . $e->getMessage();
            Values::saveValue('import_status', 'exception', $e->getLine(), $e->getMessage() . ' File name : ' . $e->getFile(), isset($validate['row_number']) ? $validate['row_number']: 0);
        }
    }

    /**
     * Validates the csv data of events.
     *
     */
    public static function validateCSV($csv) {
        $eventAttributeMapArray = self::getCsvAttributeMapArray();
        $locationAttributeMapArray = LocationForm::getCsvAttributeMapArray();
        $locationAttributes = $eventAttributes = $result = [];
        $file = fopen("uploads/import/" . $csv, "r");

        $total_rows = 0;
        $rowNo = 1;
        while (fgetcsv($file) !== false) {
            ++$total_rows;
        }
//        if($total_rows > 1000){
//            Values::saveValue('import_status', 'error_on_validation', $total_rows,'<b>Number of rows must not be greater than 1000</b> <br>');
//            return ['result' => FALSE, 'msg' => '<b>Number of rows must not be greater than 1000</b> <br>', 'row_number' => $total_rows];
//        }

        rewind($file);
        $headerRow = array_map('trim', array_map('strtolower', fgetcsv($file))); //fgetcsv($file);
        if (!empty($headerRow)) {
            $models = [];
//            $value_obj = Values::getValueByName('import_status');
//            if ($value_obj == NULL) {
                Values::saveValue('import_status', 'csv_importing', 0, 'start_validating', $total_rows);
                $value_obj = Values::getValueByName('import_status');
//            }
            while (!feof($file)) {
                $rowNo++;
//                if ($rowNo % 45 == 0) {
//                    sleep(1);
//                }
                $eventModel = new EventForm();
                $locationModel = new LocationForm();
                $dataRow = fgetcsv($file);
                if (!empty($dataRow) && count(array_filter($dataRow))) {
                    foreach ($headerRow as $key => $value) {
                        if (isset($eventAttributeMapArray[$value])) {
                            $eventAttributes[$eventAttributeMapArray[$value]] = trim($dataRow[$key]);
                        } elseif (isset($locationAttributeMapArray[$value])) {
                            $locationAttributes[$locationAttributeMapArray[$value]] = trim($dataRow[$key]);
                        } elseif (!empty($value)) {
                            fclose($file);
                            Values::saveValue('import_status', 'error_on_validation', $rowNo, 'Invalid field "' . $value . '" at Row ' . $rowNo . ' and Column ' . ($key + 1));
                            return ['result' => FALSE, 'msg' => '<b>Invalid field "' . $value . '" at Row ' . $rowNo . ' and Column ' . ($key + 1) . '</b> <br>', 'row_number' => $rowNo];
                        }
                    }
                    $locationModel->attributes = $locationAttributes;
                    $locationModel->company = $locationModel->company; //ucfirst($locationModel->company);
                    $locationModel->zip = sprintf("%05d", $locationModel->zip);
                    $eventModel->attributes = $eventAttributes;
                    $eventModel->categories = explode(',', $eventModel->categories);
                    $eventModel->categories = array_map('common\functions\GlobalFunctions::processString', $eventModel->categories);
                    $eventModel->sub_categories = explode(',', $eventModel->sub_categories);
                    $eventModel->sub_categories = array_map('common\functions\GlobalFunctions::processString', $eventModel->sub_categories);
                    $eventModel->company = $eventModel->company; //ucfirst($eventModel->company);
                    $latlong = GlobalFunction::getLongLat($locationModel); //exit(print_r($latlong));
                    if (isset($latlong['error'])) {
                        Values::saveValue('import_status', 'error_on_validation', $rowNo, 'Following error occured at row ' . $rowNo . ' Invalid location address, Please enter a valid address and try again. ' . json_encode($dataRow));
                        return ['result' => FALSE, 'msg' => '<b>Following error occured at row ' . $rowNo . ' </b> <br> Invalid location address, Please enter a valid address and try again. ' . json_encode($dataRow). '</br>', 'row' => json_encode($dataRow), 'row_number' => $rowNo];
                    } elseif (!$latlong) {
                        Values::saveValue('import_status', 'error_on_validation', $rowNo, 'Following error occured at row ' . $rowNo . ' Invalid location address, Please enter a valid address and try again. ' . json_encode($dataRow));
                        return ['result' => FALSE, 'msg' => '<b>Following error occured at row ' . $rowNo . ' </b> <br> Invalid location address, Please enter a valid address and try again.', 'row' => json_encode($dataRow), 'row_number' => $rowNo];
                    } else {
                        $locationModel->geometry = ['type' => 'Point',
                            'coordinates' => [$latlong['long'],
                                $latlong['lat']]
                        ];
                    }
                    if (!$locationModel->validate()) {
                        Values::saveValue('import_status', 'error_on_validation', $rowNo, '<b>Following error occured at row ' . $rowNo . ' </b> <br>' . GlobalFunction::modelErrorsToString($locationModel->getErrors()));
                        fclose($file);
                        return ['result' => FALSE, 'msg' => '<b>Following error occured at row ' . $rowNo . ' </b> <br>' . GlobalFunction::modelErrorsToString($locationModel->getErrors()), 'row' => json_encode($dataRow), 'row_number' => $rowNo];
                    }
                    if (!$eventModel->validate()) {
                        Values::saveValue('import_status', 'error_on_validation', $rowNo, '<b>Following error occured at row ' . $rowNo . '</b> <br>' . GlobalFunction::modelErrorsToString($eventModel->getErrors()));
                        fclose($file);
                        return ['result' => FALSE, 'msg' => '<b>Following error occured at row ' . $rowNo . '</b> <br>' . GlobalFunction::modelErrorsToString($eventModel->getErrors()), 'row' => json_encode($dataRow),'row_number' => $rowNo];
                    }

                    $eventModel->location_models = $locationModel;

//                    array_push($models, $eventModel);
                    EventForm::singleEventSave($eventModel);
                    Values::saveValue('import_status', 'csv_importing', $rowNo, 'validating');
                }
            }
        }

        fclose($file);
        return ['result' => TRUE, 'models' => $models,'row_number' => $total_rows - 1];
    }

    public static function singleEventSave($model) {
        $locationForm = $model->location_models;
//        $location = Location::findOne(['store_number' => $locationForm->store_number]);]
        $query = Location::find()->andWhere(['zip' => $locationForm->zip]);
        $locations = $query->andWhere(['street' => strtolower($locationForm->street)])->all();
        if (count($locations) == 0) {
            $location = new Location();
        }else{
            $location = $locations[0];
        }
        $location->attributes = $locationForm->attributes;
        Event::updateLocationInEvents($location);
        $location->save();
        self::saveCsvEvent($model, $location);
    }

    public static function validateSingleRowOfCSV($headerRow, $dataRow) {
        $eventAttributeMapArray = self::getCsvAttributeMapArray();
        $locationAttributeMapArray = LocationForm::getCsvAttributeMapArray();
        $locationAttributes = $eventAttributes = $result = [];
        $eventModel = new EventForm();
        $locationModel = new LocationForm();

        foreach ($headerRow as $key => $value) {
            if (isset($eventAttributeMapArray[$value])) {
                $eventAttributes[$eventAttributeMapArray[$value]] = trim($dataRow[$key]);
            } elseif (isset($locationAttributeMapArray[$value])) {
                $locationAttributes[$locationAttributeMapArray[$value]] = trim($dataRow[$key]);
            } elseif (!empty($value)) {
                return ['result' => FALSE, 'msg' => '<b>Invalid field "' . $value . '" at Row ' . $rowNo . ' and Column ' . ($key + 1) . '</b> <br>'];
            }
        }
    }

    public static function mergeEventLocations($eventLocations, $newLocation) {
        foreach ($eventLocations as &$Location) {
            if ($Location['location_id'] == $newLocation['location_id']) {
                $Location = $newLocation;
                return $eventLocations;
            }
        }
        array_push($eventLocations, $newLocation);
        return $eventLocations;
    }

    public static function saveCsvEvent($eventModel, $location) {
        $newEventDate = str_replace('-', '/', $eventModel->date_start);
        $newEventDate = date('m/d/Y', strtotime($newEventDate));
        $newStartEventDate = date('Y-m-d', strtotime($newEventDate . ' + 1 days'));
        $newStartEventDate = new \MongoDB\BSON\UTCDateTime(strtotime($newStartEventDate) * 1000);
        $newEndEventDate = date('Y-m-d', strtotime($newEventDate . ' - 1 days'));
        $newEndEventDate = new \MongoDB\BSON\UTCDateTime(strtotime($newEndEventDate) * 1000);

        $newEventDate = new \MongoDB\BSON\UTCDateTime(strtotime($newEventDate) * 1000);
        $events = Event::find()->andWhere(['title' => $eventModel->title, 'company' => $eventModel->company])
                        ->andWhere(['OR', ['date_start' => $newStartEventDate], ['date_end' => $newEndEventDate]])->all();

        if (count($events) > 0) {
            foreach ($events as $event) {
                $eventModel->date_start = $event->date_start;
                $eventModel->date_end = $event->date_end;
                if ($event->date_start->toDateTime() == $newStartEventDate->toDateTime()) {
                    $eventModel->date_start = $newEventDate;
                } else {
                    $eventModel->date_end = $newEventDate;
                }
                $event->locations = self::mergeEventLocations($event->locations, $location->attributes);
                $event->attributes = $eventModel->attributes;
                $event->save();
                array_push(self::$importedEvents, $event->_id);
            }
        } else {
            $event = Event::find()->where(['title' => $eventModel->title, 'company' => $eventModel->company])
                    ->andWhere(['<=', 'date_start', $newEventDate])
                    ->andWhere(['>=', 'date_end', $newEventDate])
                    ->one();
            if (count($event) > 0) {
                $event->locations = self::mergeEventLocations($event->locations, $location->attributes);
                $eventModel->date_start = $event->date_start;
                $eventModel->date_end = $event->date_end;
            } else {
                $event = new Event();
                $event->locations = [$location->attributes];
                $eventModel->date_start = $eventModel->date_end = $newEventDate;
            }
            $event->attributes = $eventModel->attributes;
            $event->save();
            array_push(self::$importedEvents, $event->_id);
        }
    }

    public function saveEvent() {
        if ($this->validate()) {
            $event = Event::findOne(['_id' => new \MongoDB\BSON\ObjectID($this->eid)]);
            $event->attributes = $this->attributes;
            $event->date_start = new \MongoDB\BSON\UTCDateTime(strtotime($this->date_start) * 1000);
            $event->date_end = new \MongoDB\BSON\UTCDateTime(strtotime($this->date_end) * 1000);
            if (empty($event->categories)) {
                $event->categories = [];
            }
            if (empty($event->sub_categories)) {
                $event->sub_categories = [];
            }

            if ($event->update() !== FALSE) {
                return TRUE;
            } else {
                $this->errors = $event->errors;
                return FALSE;
            }
        }
    }

// end class
}
