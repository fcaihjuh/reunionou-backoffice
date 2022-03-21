<?php
require_once  __DIR__ . '/../src/vendor/autoload.php';

// Les fichiers contenant les dépendance de l'application
$config = require_once __DIR__ . '/../src/app/conf/settings.php';
$dependencies = require_once __DIR__ . '/../src/app/conf/dependencies.php';
$errors = require_once __DIR__ . '/../src/app/conf/error.php';


//Une instance du conteneur de dépendance
$c = new \Slim\Container(array_merge($config,$dependencies,$errors));
$app = new \Slim\App($c);

//Les routes de l'application
require_once __DIR__ . '/../src/app/routes/routes_td.php';
$app->run();

?>