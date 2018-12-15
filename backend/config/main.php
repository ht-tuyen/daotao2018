<?php

$params = array_merge(
        require(__DIR__ . '/../../common/config/params.php'), require(__DIR__ . '/../../common/config/params-local.php'), require(__DIR__ . '/params.php'), require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'name' => 'Quản Lý In',
    'basePath' => dirname(__DIR__),
    'language' => 'vi',
    'sourceLanguage' => 'vi',
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'gridview' => [
            'class' => '\kartik\grid\Module'
        ],
        'elearning' => [

            'class' => 'backend\modules\elearning\Elearning',

        ],
        'staticpage' => [

            'class' => 'backend\modules\staticpage\StaticPage',

        ],
        'faqs' => [

            'class' => 'backend\modules\faqs\FAQS',

        ],
        'student' => [

            'class' => 'backend\modules\student\Student',

        ],
        'message' => [

            'class' => 'backend\modules\message\Message',

        ],
    ],
    'components' => [
        'cacheFrontend' => [
            'class' => 'yii\caching\FileCache',
            'cachePath' => Yii::getAlias('@frontend') . '/runtime/cache'
        ],

        'request' => [
            'csrfParam' => '_csrf_backend',
            'class' => 'common\components\Request',
            'web' => '/backend/web',
            'adminUrl' => '/acp',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'response' => [
            // ...
            'formatters' => [
                \yii\web\Response::FORMAT_JSON => [
                    'class' => 'yii\web\JsonResponseFormatter',
                    'prettyPrint' => YII_DEBUG, // use "pretty" output in debug mode
                    'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
                    // ...
                ],
            ],
        ],
        'i18n' => [
            'translations' => [
                'app' => [
                    'class' => 'yii\i18n\DbMessageSource',
                    'db' => 'db',
                    'sourceMessageTable' => '{{%source_message}}',
                    'messageTable' => '{{%message}}',
                    'forceTranslation' => true,
                ],
                'yii' => [
                    'class' => 'yii\i18n\DbMessageSource',
                    'db' => 'db',
                    'sourceMessageTable' => '{{%source_message}}',
                    'messageTable' => '{{%message}}',
                    'forceTranslation' => true,
                ],
                'someModule.*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@kvdialog/messages',
                    'forceTranslation' => true
                ]
            ],
        ],
        'user' => [
            'identityClass' => 'backend\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
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
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'class' => 'yii\web\UrlManager',
            'baseUrl' => '/acp',
            'rules' => [
                'acp' => 'acp',
                'hoso' => 'duan/index-hoso',
                'donhang' => 'member/list-order',
                'acp/<controller:\w+>' => 'acp/<controller>',
                'acp/<controller:\w+>/<action:\w+>' => 'acp/<controller>/<action>',                
                // [
                //     'pattern' => '/filedinhkem/<a:[A-Za-z0-9 -_.]+>/<b:[A-Za-z0-9 -_.]+>',
                //     'route' => 'filedinhkem/index',                    
                // ],
            ],
        ],
        'assetManager' => [
            'linkAssets' => true,
        ],
    ],
    'as beforeRequest' => [
        'class' => 'yii\filters\AccessControl',
        'rules' => [
            [
                'allow' => true,
                'actions' => ['login','get-nhap-xuat','reset-password','captcha'],
            ],
            [
                'allow' => true,
                'roles' => ['@'],
            ],
        ],
        'denyCallback' => function () {
            return Yii::$app->response->redirect(['site/login']);
        },
    ],
    'params' => $params,
];
