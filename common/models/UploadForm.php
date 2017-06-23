<?php

namespace common\models;

use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model {

    /**
     * @var UploadedFile
     */
    public $file;
    public $fileName;

    public function rules() {
        return [
            [['file'], 'file', 'skipOnEmpty' => FALSE], // 'extensions' => 'pdf',
        ];
    }

    public function upload($path) {
        if ($this->validate()) {
            $this->file->saveAs($path . $this->file->baseName . '.' . $this->file->extension); //'uploads/'
            return true;
        } else {
            return false;
        }
    }

}
