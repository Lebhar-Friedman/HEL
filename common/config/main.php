<?php

require('constants.php');
//require ('../functions/GlobalFunctions.php');
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'ip2location' => [
            'class' => '\common\components\IP2Location\Geolocation',
            'database' => __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'IP2Location' . DIRECTORY_SEPARATOR . 'IP2LOCATION-LITE-DB9.BIN',
            'mode' => 'FILE_IO',
        ],
    ],
];
