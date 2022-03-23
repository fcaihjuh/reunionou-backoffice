<?php

namespace reu\back1\app\controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Ramsey\Uuid\Codec\TimestampFirstCombCodec;

use \reu\back1\app\models\Event;

class eventController {

    private $container;

    public function __construct(\Slim\Container $container){
        $this->container = $container;
    }


    public function getAllEvents(Request $req, Response $resp, array $args): Response {

       //Get all the events
       $events = Event::select(['id', 'title', 'description', 'date', 'place', 'id_user'])
       ->get();

        //Complete the data
        $data = [
            "type" => "collection",
            "count" => count($events),
            "events" => $events,
        ];

        return Writer::json_output($resp, 200, $data);
    }


    public function getOneEvent(Request $res, Response $resp, array $args): Response {

        //Get the id in the URI
        $id = $args['id'];

        //Get the event with some id
        $event = Event::select(['id', 'title', 'description', 'date', 'place', 'id_user'])
            ->where('id', '=', $id);
        $event=$event->firstOrFail();
          
        //Complete the data
        $data = [
            "type" => "ressource",
            "event" => $event,
        ];

        return Writer::json_output($resp, 200, $data);
    }
}

?>