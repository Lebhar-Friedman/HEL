<?php
return [
    'timeZone' => 'Asia/Karachi',
    'components' => [
//        'db' => [
//            'class' => 'yii\db\Connection',
//            'dsn' => 'mysql:host=localhost;dbname=healthevents',
//            'username' => 'root',
//            'password' => '',
//            'charset' => 'utf8',
//        ],
        'mongodb' => require(__DIR__ . '/mongodb.php'),
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            //
            'useFileTransport' => FALSE,
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => array(
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
        ),
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'facebook' => [
                    'class' => 'yii\authclient\clients\Facebook',
                    'authUrl' => 'https://www.facebook.com/dialog/oauth?display=popup',
                    'clientId' => '306153573106027',
                    'clientSecret' => 'becb54315614a98345f33978b6f51e60',
                    'attributeNames' => ['name', 'email', 'first_name', 'last_name'],
                ],
                'google' => [
                    'class' => 'yii\authclient\clients\Google',
                    'clientId' => '743187643300-0q5i61hds3sm182dla83l0579620612n.apps.googleusercontent.com',
                    'clientSecret' => 'yvhkrX6LkE3raa6o7CnYkzKO',
                ],
            ],
        ],
    ],
];
