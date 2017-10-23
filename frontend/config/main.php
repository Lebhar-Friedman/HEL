<?php

$params = array_merge(
        require(__DIR__ . '/../../common/config/params.php'), require(__DIR__ . '/../../common/config/params-local.php'), require(__DIR__ . '/params.php'), require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                    [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'ip2location' => [
            'class' => '\frontend\components\IP2Location\Geolocation',
            'database' => __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'IP2Location' . DIRECTORY_SEPARATOR . 'IP2LOCATION-LITE-DB9.BIN',
            'mode' => 'FILE_IO',
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => require(__DIR__.'/routs.php'),
        ],
        'assetManager' => [
            'bundles' => [
                'dosamigos\google\maps\MapAsset' => [
                    'options' => [
//                        'key' => 'AIzaSyC9m6ZyxIkBWWbr9Iztnn-XTW-yZO4Zp7c', //AIzaSyDnMgUFBWXPTu5dYrrSTHTh10JWV9u8Rd8
                        'key' => GOOGLE_API_KEY_For_All, 
                        'libraries' => 'places',
                        'v' => '3.exp',
                        'sensor' => 'false'
                    ]
                ]
            ]
        ],
    ],
    'params' => $params,
];
