<?php

namespace reu\back1\app\controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Ramsey\Uuid\Codec\TimestampFirstCombCodec;

use \reu\back1\app\models\Event;
use \reu\back1\app\models\Comment;
use \reu\back1\app\utils\Writer;

class EventController {

    private $container;

    public function __construct(\Slim\Container $container){
        $this->container = $container;
    }

    public function getEvents(Request $req, Response $resp, array $args): Response {

       $events = Event::select(['id', 'title', 'description', 'date', 'place'])->get();

        $data = [
            "type" => "collection",
            "count" => count($events),
            "events" => $events,
        ];

        return Writer::json_output($resp, 200, $data);
    }


    public function getEvent(Request $res, Response $resp, array $args): Response {
        $id = $args['id'];
        $owned = Event::where(['id' => $id, 'id_user' => $_SESSION['id']])->count();
        if($owned){
            $event = Event::where('id', '=', $id)->firstOrFail();
            $data = [
                "type" => "ressource",
                "event" => $event,
            ];
            return Writer::json_output($resp, 200, $data);
        }
        else{
            return Writer::json_error($resp, 403, 'Permission forbidden');
        }
    }

    public function getPublicEvent(Request $res, Response $resp, array $args) {
        $token = $args['token'];
        $event = Event::where('token', $token)->first();
        $data = [
            "type" => "ressource",
            "event" => $event,
        ];
        return Writer::json_output($resp, 200, $data);
    }

    public function createEvent(Request $req, Response $resp, array $args): Response {
        
        //Les données reçues pour le nouveau membre
        $eventData = $req->getParsedBody();

        if (!isset($eventData['title'])) {
            return Writer::json_error($resp, 400, "Le champ 'fullname' ne doit pas être vide et doit être valide");
        }
        if (!isset($eventData['desc'])) {
            return Writer::json_error($resp, 400, "Le champ 'email' ne doit pas être vide et doit être valide");
        }
        if (!isset($eventData['date'])) {
            return Writer::json_error($resp, 400, "Le champ 'date' ne doit pas être vide et doit être valide");
        }
        if (!isset($eventData['place'])) {
            return Writer::json_error($resp, 400, "Le champ 'place' ne doit pas être vide et doit être valide");
        } 

        try {

            //créer l'événement 
            $new_event = new Event();
            $new_event->title = filter_var($eventData['title']);
            $new_event->description = filter_var($eventData['desc']);
            $new_event->date = \DateTime::CreateFromFormat('Y-m-d H:i', $eventData['date']);
            $new_event->place = filter_var($eventData['place']);;
            $new_event->id_user = $_SESSION['id'];

            //Création du token unique et cryptographique
            $token_event = bin2hex(random_bytes(16));
            $new_event->token = $token_event;
            
            $new_event->save();

            //Construire la réponse : 
            $data = [
                "post" => "OK",
            ];

            return Writer::json_output($resp, 201, $data);
        }

        catch (\Exception $e) {
            return Writer::json_error($resp, 500, $e->getMessage());
        }

    }

    public static function editEvent(Request $req, Response $resp, array $args): Response {

        $eventData = $req->getParsedBody();

        if (!isset($eventData['title'])) {
            return Writer::json_error($resp, 400, "Le champ 'fullname' ne doit pas être vide et doit être valide");
        }
        if (!isset($eventData['desc'])) {
            return Writer::json_error($resp, 400, "Le champ 'email' ne doit pas être vide et doit être valide");
        }
        if (!isset($eventData['date'])) {
            return Writer::json_error($resp, 400, "Le champ 'date' ne doit pas être vide et doit être valide");
        }
        if (!isset($eventData['place'])) {
            return Writer::json_error($resp, 400, "Le champ 'place' ne doit pas être vide et doit être valide");
        } 

        try{
            $id = $args['id'];
            $event= Event::where('id', $id)->count();
            if($event){

                $title = filter_var($eventData['title']);
                $description = filter_var($eventData['desc']);
                $date = \DateTime::CreateFromFormat('Y-m-d H:i', $eventData['date']);
                $place = filter_var($eventData['place']);

                Event::where('id', $id)->update(['title' => $title, 'description' => $description, 'date' => $date, 'place' => $place]);
                $data = [
                    "post" => "OK",
                ];
                return Writer::json_output($resp, 200, $data);
            }
            else{
                return Writer::json_error($resp, 200, "Event $id not found");
            }
        }
        catch (\Exception $e){
            return Writer::json_error($resp, 500, $e->getMessage());
        }
    }

    public function deleteEvent(Request $req, Response $resp, array $args): Response {
        $id = $args['id'];
        $event = Event::where(['id' => $id, 'id_user' => $_SESSION['id']])->count();
        if($event){
            Comment::where('id_event', $id)->delete();
            Event::where('id', $id)->delete();

            return Writer::json_output($resp, 200);
        }
        else{
            return Writer::json_error($resp, 404, 'Event not found');
        }
    }
}

?>