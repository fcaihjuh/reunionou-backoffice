<?php

namespace reu\backoffice\BO\controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use \reu\backoffice\BO\utils\Writer;


class EventController extends Controller{

    private $container;

    public function __construct(\Slim\Container $container){
        $this->container = $container;
    }

    
    public function deleteEvent(Request $req, Response $resp, array $args) : Response {
    
        $id = $args['id'];
        $event = Event::where('id', '=', $id)->count();
    
        if($event)
        {
             Event::where('id', $id)->delete();
             Comment::where('id_event', $id)->delete();
    
             $data = [
                "delete" => "OK",
            ];
     
             return Writer::json_output($resp, 200, $data);       
        }
        else
        {
            return Writer::json_error($resp, 400, "Event $id not found");;
        }
    }

}
