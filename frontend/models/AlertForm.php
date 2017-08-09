<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace frontend\models;

use yii\base\Model;

/**
 * Description of AlertForm
 *
 * @author zeeshan
 */
class AlertForm extends Model {
    public $user_id;
    public $alerts;
    
    public function rules() {
        return [
            // username and password are both required
            [['user_id'], 'required'],
            [['alerts'], 'safe'],
        ];
    }
}
