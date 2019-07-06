<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=hicolin_cn',
            'username' => 'hicolin_cn',
            'password' => 'PMSTdcCxHsdffmza',
            'charset' => 'utf8mb4',
            'tablePrefix' => 'colin_',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'useFileTransport' => true,
        ],
    ],
];
