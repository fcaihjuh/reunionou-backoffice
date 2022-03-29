<?php

namespace reu\back1\app\middleware;

use \Psr\Http\Message\ServerRequestInterface as Request ;
use \Psr\Http\Message\ResponseInterface as Response ;

use reu\back1\app\models\User;

class BackMiddleware{

    private $c;

    public function __construct(\Slim\Container $c){
        $this->c=$c;
    }

    public function __invoke(Request $request, Response $response, $next) {
        $token = $request->getParam('token');
        if(empty($token)) {
            $resp = $response->withHeader('Content-Type', 'application/json');
            $resp->getBody()->write(json_encode(['error' => 'missing token']));
            return $resp;
        } else {
            $user = User::where('token', $token)->count();
            if(!$user) {
                $resp = $response->withHeader('Content-Type', 'application/json');
                $resp->getBody()->write(json_encode(['error' => 'invalid token']));
                return $resp;
            } else {
                $user = User::select('id')->where('token', $token)->first();
                $_SESSION['id'] = $user->id;
            }
        }

        return $next($request, $response);
    }
}   