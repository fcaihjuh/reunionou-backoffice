<?php

require_once '../src/vendor/autoload.php';

session_start();

use reu\backoffice\BO\middleware\AuthMiddleware;
use reu\backoffice\BO\middleware\GuestMiddleware;

$conf = parse_ini_file(__DIR__ .'/../src/BO/conf/back1.db.conf.ini.dist');

$db = new Illuminate\Database\Capsule\Manager();

$db->addConnection($conf); 
$db->setAsGlobal();           
$db->bootEloquent();   


$config = require_once __DIR__. '/../src/BO/conf/settings.php';
$c=new \Slim\Container($config);


$c['view'] = function($container) {
    $view = new \Slim\Views\Twig(__DIR__.'/../src/BO/views', [
        // 'cache' => $dir . '/tmp/cache'
    ]);
    
    $router = $container->get('router');
    $uri = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));
    $view->addExtension(new \Slim\Views\TwigExtension($router, $uri));

    return $view;
};

$app = new \Slim\App($c);

$app->add(new \reu\backoffice\BO\middleware\FlashMiddleware($c->view->getEnvironment()));

$app->get('/login[/]', \reu\backoffice\BO\controller\BOController::class.':login')->setName('login')->add(new GuestMiddleware($c));

$app->post('/login[/]', \reu\backoffice\BO\controller\MemberController::class.':login')->add(new GuestMiddleware($c));

$app->get('/', \reu\backoffice\BO\controller\BOController::class.':home')->setName('home')->add(new AuthMiddleware($c));

$app->get('/profile[/]', \reu\backoffice\BO\controller\BOController::class.':profile')->setName('profile')->add(new AuthMiddleware($c));

$app->post('/profile[/]', \reu\backoffice\BO\controller\MemberController::class.':profile')->add(new AuthMiddleware($c));

$app->get('/logout[/]', \reu\backoffice\BO\controller\BOController::class.':logout')->setName('logout')->add(new AuthMiddleware($c));

$app->get('/user[/]', \reu\backoffice\BO\controller\BOController::class.':createUser')->setName('createUser')->add(new AuthMiddleware($c));

$app->post('/user[/]', \reu\backoffice\BO\controller\MemberController::class.':createUser')->add(new AuthMiddleware($c));

$app->get('/users[/]', \reu\backoffice\BO\controller\BOController::class.':users')->setName('users')->add(new AuthMiddleware($c));

$app->delete('/user[/]', \reu\backoffice\BO\controller\MemberController::class.':userDelete')->setName('userDelete')->add(new AuthMiddleware($c));

$app->put('/user[/]', \reu\backoffice\BO\controller\MemberController::class.':userUpdate')->setName('userUpdate')->add(new AuthMiddleware($c));

$app->get('/events[/]', \reu\backoffice\BO\controller\BOController::class.':events')->setName('events')->add(new AuthMiddleware($c));

$app->delete('/event[/]', \reu\backoffice\BO\controller\EventController::class.':deleteEvent')->setName('eventDelete')->add(new AuthMiddleware($c));

$app->run();