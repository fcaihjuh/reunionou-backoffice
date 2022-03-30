<?php
namespace reu\backoffice\BO\controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \reu\backoffice\BO\controller\AuthController;
use \reu\backoffice\BO\utils\Writer;

class BOController extends Controller {

    private $container;

    public function __construct(\Slim\Container $container){
        $this->container = $container;
    }

    public function home(Request $req, Response $resp) {
        $this->render($resp, 'pages/Home.twig');
    }

    public function login(Request $req, Response $resp) {
        $this->render($resp, 'pages/Login.twig');
    }

    public function logout(Request $req, Response $resp) {
        AuthController::logout();
        return $this->redirect($resp, 'login');
    }

    public function profile(Request $req, Response $resp) {
        $this->render($resp, 'pages/Profile.twig');
    }

    public function createUser(Request $req, Response $resp) {
        $this->render($resp, 'pages/CreateUser.twig');
    }

    public function users(Request $req, Response $resp) {
        $users = User::select()->get();
        $this->render($resp, 'pages/Users.twig', ['users' => $users]);
    }

    public function events(Request $req, Response $resp) {
        $events = Event::select()->get();
        $this->render($resp, 'pages/Events.twig', ['events' => $events]);
    }
}