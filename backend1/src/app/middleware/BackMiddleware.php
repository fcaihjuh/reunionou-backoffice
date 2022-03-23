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

    public function checkToken(Request $rq, Response $rs, callable $next): Response {
        $token = null;
        $token = $rq->getQueryParam('token', null);
        if(is_null($token)){
            $api_header=$rq->getHeader('X-commande_api-token');
            $token = isset($api_header[0]) ? $api_header[0] : null;
        }
        if(empty($token)){
            $this->c->get('logger.error')->error("Missing token in command route", [403]);
            return Writer::json_error($rs, 403, "missing token ($token)");
        }

        $id = $rq->getAttribute('route')->getArgument('id');
        $c=null;
        try {
            $c=User::where('id', '=', $id)->firstOrFail();

            if($c->token !== $token){
                $this->c->get('logger.error')->error("Invalid token in command route", [403]);
                return Writer::json_error($rs, 403, "invalid token ($token)");
            }
        } 
        catch (ModelNotFoundException $e) {
            $this->c->get('logger.error')->error("command $id not found", [404]);
            return Writer::json_error($rs, 404, "command $id not found");
        };

        $rq = $rq->withAttribute('back', $c);
        return $next($rq, $rs);
    }
}   