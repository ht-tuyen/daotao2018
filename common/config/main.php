<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'settings' => [
            'class' => 'common\components\Settings'
        ],
        'AcpHelper' => array(
            'class' => 'backend\helpers\AcpHelper'
        ),
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'nullDisplay' => '',
        ],
        'request' => [
            'enableCsrfValidation' => true,
        ],
    ],
    'modules' => [

        'api' => [
            
            'class' => 'common\modules\api\API',

        ],

    ],
];
