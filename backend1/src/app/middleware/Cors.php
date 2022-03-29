<?php

namespace reu\back1\app\middleware;

use \Psr\Http\Message\ServerRequestInterface as Request ;
use \Psr\Http\Message\ResponseInterface as Response ;

class Cors{

    private $c;

    public function __construct(\Slim\Container $container){
        $this->c = $container;
    }

    public function corsHeaders(Request $rq, Response $rs, callable $next ): Response {
        $response = $next($rq,$rs);
        $response = $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS' )
            ->withHeader('Access-Control-Allow-Headers','X-Requested-With, Content-Type, Accept, Origin, Authorization' )
            ->withHeader('Access-Control-Max-Age', $this->c['settings']['cors']['max.age']);

        if($this->c['settings']['cors']['credentials']){
            $response = $response->withHeader('Acces-Control-Allow-Credentials', 'true');
        }
           
        return $response;
    }

}

//use reu\back1\app\utils\Writer;
/*
class Cors{

    public function corsHeaders(Request $rq, Response $rs, callable $next ): Response {
        $response = $next($rq,$rs);
        $response = $response
            ->withHeader('Access-Control-Allow-Origin', $rq->getHeader('origin'))
            ->withHeader('Access-Control-Allow-Methods', 'POST, PUT, GET' )
            ->withHeader('Access-Control-Allow-Headers','Authorization, Content-type' )
            ->withHeader('Access-Control-Max-Age', 3600)
            ->withHeader('Access-Control-Allow-Credentials', 'true');
        return $response;
    }

}*/

?>