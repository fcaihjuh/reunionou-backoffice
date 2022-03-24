<?php

namespace reu\back1\app\controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Ramsey\Uuid\Codec\TimestampFirstCombCodec;

use \reu\back1\app\models\Event;

class EventController {

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

    public function createEvent(Request $req, Response $resp, array $args): Response {
        
        //Les données reçues pour le nouveau membre
        $eventData = $req->getParsedBody();

        if (!isset($eventData['title'])) {
            return Writer::json_error($resp, 400, "Le champ 'fullname' ne doit pas être vide et doit être validé");
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

        //créer l'événement et son id
        $new_event = new Event();
        $new_event->title = filter_var($eventData['title'], FILTER_SANITIZE_EMAIL);
        $new_event->description = filter_var($eventData['desc'], FILTER_SANITIZE_EMAIL);
        $new_event->date = \DateTime::CreateFromFormat('Y-m-d H:i', $eventData['date'].);
        $new_event->place = filter_var($eventData['place'], FILTER_SANITIZE_EMAIL);;

        //Création du token unique et cryptographique
        $token_event = random_bytes(32);
        $token_event = bin2hex($token_event);
        $new_event->token = $token_event;
        
        $new_event->save();


        // Récupération du path pour le location dans header
        /*$path_user = $this->container->router->pathFor(
            '',
            ['id' => $new_user->id]
        );*/

        //Construire la réponse : 
        $response = [
            "type" => "ressource",
            "user" => $new_event->toArray(),
        ];

        //Le retour
        $resp->getBody()->write(json_encode($response));
        $resp->withHeader('X-lbs-token', $new_event->token);
        return Writer::json_output($resp, 201);//->withHeader("Location", $path_user);
    }

    catch (\Exception $e) {
        return Writer::json_error($rs, 500, $e->getMessage());
    }
}

?>