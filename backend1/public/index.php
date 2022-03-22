<?php
/**
 * File:  index.php
 *
 */

require_once  __DIR__ . '/../src/vendor/autoload.php';

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

$c=new \Slim\Container(array_merge($config, $deps));

$app = new \Slim\App($c);

$app->get('/hello/{name}',
    function (Request $req, Response $resp, $args) {
        $name = $args['name'];
        $dbfile = $this->settings['dbfile'];

        $r = User::select()->get();
        foreach($r as $l){
           echo $l->fullname; 
        }

        $resp->getBody()->write("<h1>Hello, $name </h1> <h2>$dbfile</h2>");
        return $resp;
    }
);


$app->get('/users[/]', \reu\back1\app\controller\Reu_Controller::class.':getAllUsers');

$app->get('/users/{id}[/]', \reu\back1\app\controller\Reu_Controller::class.':oneUser');

$app->get('/events[/]', \reu\back1\app\controller\Reu_Controller::class.':getAllEvents');

$app->get('/events/{id}[/]', \reu\back1\app\controller\Reu_Controller::class.':oneEvent');

$app->get('/comments[/]', \reu\back1\app\controller\Reu_Controller::class.':getAllComments');

$app->get('/comments/{id}[/]', \reu\back1\app\controller\Reu_Controller::class.':oneCommebt');

/*

$app->post('/commands[/]', \lbs\command\app\controller\CommandController::class.':createCommand');

$app->get('/commands/{id}[/]', \lbs\command\app\controller\CommandController::class.':getCommand')
    ->add(\lbs\command\app\middleware\CommandMiddleware::class.':checkToken');


$app->put('/commands/{id}[/]', \lbs\command\app\controller\CommandController::class.':replaceCommand')
    ->add(\lbs\command\app\middleware\CommandMiddleware::class.':checkToken');*/

$app->run();
