<?php
namespace reu\backoffice\BO\controller;

use Psr\Http\Message\ResponseInterface as Response;

abstract class Controller {

    protected $container;

    public function __construct($c)
    {
        $this->container = $c;
    }

    public function __get($name) {
        return $this->container->get($name);
    }

    public function render(Response $resp, $file, $params = []) {
        $this->container->view->render($resp, $file, $params);
    }

    public function redirect($response, $name, $args = []) {
        return $response->withStatus(200)->withHeader('Location', $this->router->pathFor($name, $args));
    }

    public function flash($message, $type = 'success') {
        if(!isset($_SESSION['flash'])) {
            $_SESSION['flash'] = [];
        }
        return $_SESSION['flash'][$type] = $message;
    }

}