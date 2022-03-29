<?php 

$config = [
    'settings'=>[
        'dbfile' => __DIR__. '/back1.db.conf.ini.dist',
        'displayErrorDetails'=> true,
        'cors' => [
            "methods" => ["GET", "POST", "PUT", "OPTIONS", "DELETE"],
            "headers.allow" => ['Content-Type', 'Authorization', 'X-commande-token'],
            "headers.expose"=>[],
            "max.age"=> 60*60,
            "credentials"=> true
        ],
        //'debug.log' => __DIR__.'../log/debug.log',
        //'log.level' => \Monolog\Logger::DEBUG,
        //'log.name' => 'slim.log',
    ],
];

return $config;