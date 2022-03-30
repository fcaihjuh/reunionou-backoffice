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

$app = new \Slim\App($c);

$container = $app->getContainer();

$container['view'] = function($container) {
    $view = new \Slim\Views\Twig(__DIR__.'/../src/BO/views', [
        // 'cache' => $dir . '/tmp/cache'
    ]);
    
    $router = $container->get('router');
    $uri = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));
    $view->addExtension(new \Slim\Views\TwigExtension($router, $uri));

    $view->getEnvironment()->addGlobal('auth', $_SESSION);

    return $view;
};


$app->add(new \reu\backoffice\BO\middleware\FlashMiddleware($container->view->getEnvironment()));

$app->get('/login[/]', \reu\backoffice\BO\controller\BOController::class.':login')->add(new GuestMiddleware($container));
$app->post('/login[/]', \reu\backoffice\BO\controller\MemberController::class.':login')->add(new GuestMiddleware($container));

$app->get('/', \reu\backoffice\BO\controller\BOController::class .':home')->setName('home')->add(new AuthMiddleware($container));

/*$app->get('/profile', \reu\backoffice\BO\controller\BOController::class':profile')->setName('profile')->add(new AuthMiddleware($container));

$app->post('/profile', \reu\backoffice\BO\controller\MemberController::class':profile')->add(new AuthMiddleware($container));

$app->get('/logout', \reu\backoffice\BO\controller\BOController::class':logout')->setName('logout')->add(new AuthMiddleware($container));

$app->get('/user', \reu\backoffice\BO\controller\BOController::class':createUser')->setName('createUser')->add(new AuthMiddleware($container));

$app->post('/user', \reu\backoffice\BO\controller\MemberController::class':createUser')->add(new AuthMiddleware($container));

$app->get('/users', \reu\backoffice\BO\controller\BOController::class':users')->setName('users')->add(new AuthMiddleware($container));

$app->delete('/user/{id}[/]', \reu\backoffice\BO\controller\MemberController::class':userDelete')->setName('userDelete')->add(new AuthMiddleware($container));

$app->put('/user/{id}[/]', \reu\backoffice\BO\controller\MemberController::class':userUpdate')->setName('userUpdate')->add(new AuthMiddleware($container));

$app->get('/events', \reu\backoffice\BO\controller\EventController::class':events')->setName('events')->add(new AuthMiddleware($container));

$app->delete('/event/{id}[/]', \reu\backoffice\BO\controller\EventController::class':deleteEvent')->setName('eventDelete')->add(new AuthMiddleware($container));
*/
$app->run();