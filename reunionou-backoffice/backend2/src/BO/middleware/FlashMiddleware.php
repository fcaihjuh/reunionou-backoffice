<?php
namespace reu\backoffice\BO\middleware;

use Slim\Http\Request;
use Slim\Http\Response;

class FlashMiddleware {

    public function __construct(\Twig\Environment $twig)
    {
        $this->twig = $twig;   
    }

    public function __invoke(Request $request, Response $response, $next) {
        $this->twig->addGlobal('flash', isset($_SESSION['flash']) ? $_SESSION['flash'] : []);
        if(isset($_SESSION['flash'])) {
            unset($_SESSION['flash']);
        }
        return $next($request, $response);
    }

}