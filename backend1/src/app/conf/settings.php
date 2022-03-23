<?php 

$config = [
    'settings'=>[
        'dbfile' => __DIR__. '/commande.db.conf.ini.dist',
        'displayErrorDetails'=> true,
        'debug.log' => __DIR__.'../log/debug.log',
        'log.level' => \Monolog\Logger::DEBUG,
        'log.name' => 'slim.log'
    ],
];

return $config;