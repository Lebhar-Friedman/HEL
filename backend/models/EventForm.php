<?php

namespace backend\models;

use yii\base\Model;

/**
 * Event form
 */
class EventForm extends Model {

    public $title;
    public $company;
    public $date_start;
    public $date_end;
    public $time_start;
    public $time_end;
    public $categories;
    public $sub_categories;
    public $location_models;
    public $price;
    public $description;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            // username and password are both required
            [['title', 'company', 'description'], 'required'],
            // safe fields
            [['price', 'date_start', 'date_end', 'time_start', 'time_end', 'categories', 'sub_categories', 'location_models',], 'safe'],
            // string fields
            [['title', 'company', 'description'], 'string'],
        ];
    }

    public static function getCsvAttributeMapArray() {
        return [// event attributes
            'event company' => 'company',
            'event title' => 'title',
            'event date' => 'date_start',
            'event start time' => 'time_start',
            'event end time' => 'time_end',
            'price' => 'cost',
            'health categories/themes' => 'categories',
            'health services/screenings' => 'sub_categories',
            'description' => 'description',
        ];
    }

    /**
     * Save csv data of events.
     *
     */
    public static function saveCSV($csv) {
        $validate = \backend\models\EventForm::validateCSV($csv);
//        echo json_encode($validate);
        if ($validate['result']) {
            $models = $validate['models'];

            foreach ($models as $model) {
                $locationForm = $model->location_models;
                $location = \common\models\Location::findOne(['street' => $locationForm->street, 'city' => $locationForm->city, 'state' => $locationForm->state, 'zip' => $locationForm->zip]);
                if (count($location) == 0) {
                    $location = new \common\models\Location();
                }
                $location->attributes = $locationForm->attributes;
                $latlong = \components\GlobalFunction::getLongLat($location);
                if ($latlong) {
                    $location->latitude = $latlong['lat'];
                    $location->longitude = $latlong['long'];
                }//echo '<br>' . json_encode($location->attributes);
                $location->save();
                $event = \common\models\Event::findOne(['title' => $model->title, 'company' => $model->company]);
                if (count($event) == 0) {
                    $event = new \common\models\Event();
                    $event->locations = [$location->attributes];
                } else {
                    $event->locations = self::mergeEventLocations($event->locations, $location->attributes);
                }
                $event->attributes = $model->attributes;
                if (!$event->save()) {
                    return json_encode(['msgType' => 'ERR', 'msg' => \Component\GlobalFunction::modelErrorsToString($event->getErrors()), 'validated' => 'CSV file validation failed.']);
                }
            }
            return json_encode(['msgType' => 'SUC', 'msg' => 'All ' . count($models) . ' Events were imported successfully.', 'validated' => 'CSV is validated Successfully.']);
        } else {
            return json_encode(['msgType' => 'ERR', 'msg' => $validate['msg']]);
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
        $headerRow = array_map('trim', array_map('strtolower', fgetcsv($file))); //fgetcsv($file);
        if (!empty($headerRow)) {
            $rowNo = 1;
            $models = [];
            while (!feof($file)) {
                $rowNo++;
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
                            return ['result' => FALSE, 'msg' => '<b>Invalid field "' . $value . '" at Row ' . $rowNo . ' and Column ' . $key . '</b> <br>'];
                        }
                    }
                    $locationModel->attributes = $locationAttributes;
                    $eventModel->attributes = $eventAttributes;
                    if (!$locationModel->validate()) {//echo json_encode($model->getErrors());exit();
                        fclose($file);
                        return ['result' => FALSE, 'msg' => '<b>Following error occured at row ' . $rowNo . ' </b> <br>' . \components\GlobalFunction::modelErrorsToString($locationModel->getErrors()), 'row' => json_encode($dataRow)];
                    }
                    if (!$eventModel->validate()) {//echo json_encode($model->getErrors());exit();
                        fclose($file);
                        return ['result' => FALSE, 'msg' => '<b>Following error occured at row ' . $rowNo . '</b> <br>' . \components\GlobalFunction::modelErrorsToString($eventModel->getErrors()), 'row' => json_encode($dataRow)];
                    }

                    $eventModel->location_models = $locationModel;
                    array_push($models, $eventModel);
                }
            }
        }

        fclose($file);
        return ['result' => TRUE, 'models' => $models];
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

// end class
}
