<?php

    $params = require(__DIR__.'/params.php');

    $config = [
        'id'                => 'basic',
        'basePath'          => dirname(__DIR__),
        'language'          => 'ru',
        'bootstrap'         => ['log'],
        'defaultRoute' => 'document/default/index',
        'components'        => [
            'request'      => [
                // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
                'cookieValidationKey' => 'ZsLhBLhq4u4y9-BWtNwWIDN0wtpfB8fZ',
            ],
            'cache'        => [
                'class' => 'yii\caching\FileCache',
            ],
            'user'         => [
                'identityClass'   => 'app\models\User',
                'enableAutoLogin' => true,
            ],
            'errorHandler' => [
                'errorAction' => 'site/error',
            ],
            'mailer'       => [
                'class'            => 'yii\swiftmailer\Mailer',
                // send all mails to a file by default. You have to set
                // 'useFileTransport' to false and configure a transport
                // for the mailer to send real emails.
                'useFileTransport' => true,
            ],
            'log'          => [
                'traceLevel' => YII_DEBUG ? 3 : 0,
                'targets'    => [
                    [
                        'class'  => 'yii\log\FileTarget',
                        'levels' => ['error', 'warning'],
                    ],
                ],
            ],
            'db'           => require(__DIR__.'/db.php'),
            'urlManager'   => [
                'class'           => 'yii\web\UrlManager',
                // Disable index.php
                'showScriptName'  => false,
                // Disable r= routes
                'enablePrettyUrl' => true,
                'rules'           => array(
                    '<controller:\w+>/<id:\d+>'              => '<controller>/view',
                    '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                    '<controller:\w+>/<action:\w+>'          => '<controller>/<action>',
                ),
            ],
            'formatter'    => [
                'class'          => 'yii\i18n\Formatter',
                'dateFormat'     => 'php:d-m-Y',
                'datetimeFormat' => 'php:d-m-Y H:i:s',
                'timeFormat'     => 'php:H:i:s',
            ],
        ],
        'params'            => $params,
        'modules'           => [
            'document' => [
                'class' => 'app\modules\document\Module',
            ],
        ],
    ];

    if (YII_ENV_DEV) {
        // configuration adjustments for 'dev' environment
        $config['bootstrap'][] = 'debug';
        $config['modules']['debug'] = [
            'class' => 'yii\debug\Module',
        ];

        $config['bootstrap'][] = 'gii';
        $config['modules']['gii'] = [
            'class' => 'yii\gii\Module',
        ];
    }

    return $config;
