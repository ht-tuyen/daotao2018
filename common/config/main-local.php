<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',            
            'dsn' => 'mysql:host=localhost;dbname=daotao2018',
            'username' => 'root',
            'password' => 'r123456',
            'charset' => 'utf8',
            'tablePrefix' => 'qli_',
        ],
       
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',
                'username' => 'vsqisoft@gmail.com',
                'password' => 'htecom.vn',
                'port' => '465',
                'encryption' => 'ssl',
            ],
            'useFileTransport' => false,
        ],
    ],
];
