<?php


use \reu\back1\app\controller\EventController;
use \reu\back1\app\middleware\Middleware;
use \reu\back1\app\middleware\EventValidator as EventValidator;
use \DavidePastore\Slim\Validation\Validation as Validation ;

$validators = EventValidator::create_validators();

//Routes de l'API

//Route pour retourner le contenu d'un événement
$app->get('/events/{id}[/]', EventController::class. ':OneEvent')->setName('oneEvent')->add(middleware::class. ':putIntoJson');


//Route pour retourner le contenu de tous les événements
$app->get('/events[/]', EventController::class. ':getAllEvents')->setName('getAllEvents')->add(middleware::class. ':putIntoJson');


//Route pour modifier le contenu d'un événement
$app->put('/events/{id}[/]', EventController::class. ':putEvent')->setName('putEvent')->add(middleware::class. ':putIntoJson');


//Route pour les participants d'un événement
$app->get('/events/{id}/participants', EventController::class.':getAllUser')->setName('allUser')->add(middleware::class. ':putIntoJson');

//Route pour un user
$app->get('/events/{id}/participant', EventController::class.':OneUser')->setName('OneUser')->add(middleware::class. ':putIntoJson');


//Route pour tous les commentaires
$app->get('/comments[/]', EventController::class.':getAllComment')->setName('allComment')->add(middleware::class. ':putIntoJson');

///Route pour retourner le contenu d'un commentaire
$app->get('/comments/{id}[/]', EventController::class. ':OneComment')->setName('oneComment')->add(middleware::class. ':putIntoJson');


?>