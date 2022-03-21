<?php 

$config = [
    'settings'=>[
        'dbfile' => __DIR__. '/back1.db.conf.ini.dist',
        'displayErrorDetails'=> true,
        'debug.log' => __DIR__.'../log/debug.log',
        'log.level' => \Monolog\Logger::DEBUG,
        'log.name' => 'slim.log'
    ],
];

return $config;