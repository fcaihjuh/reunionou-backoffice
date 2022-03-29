<?php

require_once  __DIR__ . '/../src/vendor/autoload.php';

session_start();

use \Psr\Http\Message\ServerRequestInterface as Request ;
use \Psr\Http\Message\ResponseInterface as Response ;

use reu\back1\app\models\User;

$conf = parse_ini_file(__DIR__ .'/../src/app/conf/back1.db.conf.ini.dist');

$db = new Illuminate\Database\Capsule\Manager();

$db->addConnection($conf); 
$db->setAsGlobal();           
$db->bootEloquent();   

$config = require_once __DIR__. '/../src/app/conf/settings.php';
$deps = require_once __DIR__.'/../src/app/conf/deps.php';
$errors = require_once __DIR__.'/../src/app/conf/error.php';

$c=new \Slim\Container(array_merge($config, $deps, $errors));

$app = new \Slim\App($c);

$app->get('/events[/]', \reu\back1\app\controller\EventController::class.':getEvents');
$app->get('/event/{id}[/]', \reu\back1\app\controller\EventController::class.':getEvent');
$app->get('/public_event/{token}[/]', \reu\back1\app\controller\EventController::class.':getPublicEvent');
$app->post('/event[/]', \reu\back1\app\controller\EventController::class.':createEvent');
$app->put('/event/{id}[/]', \reu\back1\app\controller\EventController::class.':editEvent')->add(new \reu\back1\app\middleware\BackMiddleware($c));
$app->delete('/event/{id}[/]', \reu\back1\app\controller\EventController::class.':deleteEvent')->add(new \reu\back1\app\middleware\BackMiddleware($c));

$app->get('/comments[/]', \reu\back1\app\controller\CommentController::class.':getComments');
$app->get('/comments/{id}[/]', \reu\back1\app\controller\CommentController::class.':getComment')->add(new \reu\back1\app\middleware\BackMiddleware($c));
$app->get('/public_comments/{token}[/]', \reu\back1\app\controller\CommentController::class.':getPublicComments');
$app->post('/comment/{id}[/]', \reu\back1\app\controller\CommentController::class.':postComment')->add(new \reu\back1\app\middleware\BackMiddleware($c));

$app->get('/users[/]', \reu\back1\app\controller\MemberController::class.':getUsers');
$app->get('/user/{id}[/]', \reu\back1\app\controller\MemberController::class.':getUser')->add(new \reu\back1\app\middleware\BackMiddleware($c));

$app->post('/signup[/]', \reu\back1\app\controller\MemberController::class.':signUp');
$app->post('/signin[/]', \reu\back1\app\controller\MemberController::class.':signIn');

$app->add(\reu\back1\app\middleware\Cors::class.':corsHeaders') ;

$app->options('/{routes:.+}',
    function(Request $rq, Response $rs, array $args) {
        return $rs;
    });

$app->run();
