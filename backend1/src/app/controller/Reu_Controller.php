<?php

namespace reu\back1\app\controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use reu\back1\app\utils\Writer;

//Model
use reu\back1\app\models\User;
use reu\back1\app\models\Event;

//Error

class Reu_Controller {

    private $container;

    public function __construct(\Slim\Container $container){
        $this->container = $container;
    }

    ///////////////USER//////////////

    public function getAllUsers(Request $req, Response $resp, array $args): Response {
        
        //Get all the Users
        $users = User::select(['id', 'mail', 'fullname', 'username', 'password'])
            ->get();
        
        //complete the data 
        $data = [
            "type" => "collection",
            "count" => count($users),
            "users" => $users,
        ];

        return Writer::json_output($resp, 200, $data);

    }

    public function oneUser(Request $req, Response $resp, array $args): Response {

        //Get the id in the URI
        $id = $args['id'];

        //Get the user with some id
        $user = User::select(['id', 'mail', 'fullname', 'username', 'password'])
            ->where('id', '=', $id);
        $user=$user->firstOrFail();

        //Complete the data
        $data = [
            "type" => "ressource",
            "user" => $user->toArray(),
        ];

        return Writer::json_output($resp, 200, $data);

    } 

    ///////////////EVENT//////////////

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

    public function oneEvent(Request $res, Response $resp, array $args): Response {

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

    ///////////////COMMENT//////////////

    public function getAllComments(Request $req, Response $resp, array $args): Response {

        //Get all the comments
        $comments = Comment::select(['id', 'id_event', 'id_user', 'content'])
            ->get();

        //complete the data
        $data = [
            "type" => "collection", 
            "count" => count($comments),
            "comments" => $comments,
        ];

        return Writer::json_output($resp, 200, $data);
    }

    public function oneComment(Request $res, Response $resp, array $args): Response {

        //Get the id in the URI
        $id = $args['id'];

        //Get the comment with some id
        $comment = Comment::select(['id', 'id_event', 'id_user', 'content'])
            ->where('id', '=', $id);

        $comment=$comment->firstOrFail();

        //Complete the data
        $data = [
            "type" => "ressource",
            "comment" => $comment,
        ];

        return Writer::json_output($resp, 200, $data);
    }

    public function signUp(Request $req, Response $resp, array $args): Response {
        // $clientError = $this->c->clientError;
        //Les données reçues pour la nouvelle commande
        $createUser = $req->getParsedBody();

        //! comment traiter le message de retours dans ce cas ? un json_error ou pas ? 
        //! quelle est l'erreur adéquate dans ce cas ? 403 ? 
        //! dois-je customiser les messages d'erreur ?
        if ($req->getAttribute('has_errors')) {

            $errors = $req->getAttribute('errors');

            if (isset($createUser['nom_user'])) {
                ($this->c->get('logger.error'))->error("error",$errors['nom_user']);
                return Writer::json_error($resp, 403, "Le champ 'nom_user' ne doit pas être vide et doit contenir que des lettres");
            }
            if (isset($createUser['mail_user'])) {
                ($this->c->get('logger.error'))->error("error",$errors['mail_user']);
                return Writer::json_error($resp, 403, "Le champ 'mail_user' ne doit pas être vide et doit être valide");
            }
            if (isset($createUser['pseudo_user'])) {
                ($this->c->get('logger.error'))->error("error",$errors['pseudo_user']);
                return Writer::json_error($resp, 403, "Le champ 'pseudo_user' ne doit pas être vide et doit être valide");
            }
            if (isset($createUser['pwd_user'])) {
                ($this->c->get('logger.error'))->error("error",$errors['pwd_user']);
                return Writer::json_error($resp, 403, "Le champ 'pwd_user' ne doit pas être vide et doit être valide");
            }
        } else {

            //créer la commande et son id
            $new_user = new User();
            $new_user_id = Uuid::uuid4();
            $new_user->id =  $new_user_id;
            $new_user->nom = filter_var($createUser['nom_user'], FILTER_SANITIZE_STRING);
            $new_user->mail = filter_var($createUser['mail_user'], FILTER_SANITIZE_EMAIL);
            $new_user->nom = filter_var($createUser['pseudo_user'], FILTER_SANITIZE_STRING);
            $new_user->nom = filter_var($createUser['pwd_user'], FILTER_UNSAFE_RAW);

            //Création du token unique et cryptographique
            $token_user = random_bytes(32);
            $token_user = bin2hex($token_user);
            //$new_user->token = $token_user;
            
            $new_user->save();


            // Récupération du path pour le location dans header
            $path_user = $this->container->router->pathFor(
                'oneUser',
                ['id' => $new_user->id]
            );

            //Construire la réponse : 
            $response = [
                "type" => "ressource",
                "user" => $new_user,
            ];

            //Le retour
            $resp->getBody()->write(json_encode($response));
            $resp->withHeader('X-lbs-token', $new_user->token);
            return Writer::json_output($resp, 201)->withHeader("Location", $path_user);
        }

        catch (\Exception $e) {
            return Writer::json_error($rs, 500, $e->getMessage());
        }

        } 

        public function signIn(Request $req, Response $resp, array $args): Response {

        //Get the id in the URI
        $id = $args['id'];

        //Get the user with some id
        $user = User::select(['id', 'mail', 'fullname', 'username', 'password'])
            ->where('id', '=', $id);

        //Complete the data
        $data = [
            "type" => "ressource",
            "user" => $user,
        ];

        return Writer::json_output($resp, 200, $data);

        } 

        public function signOut(Request $req, Response $resp, array $args): Response {

        //Get the id in the URI
        $id = $args['id'];

        //Get the user with some id
        $user = User::select(['id', 'mail', 'fullname', 'username', 'password'])
            ->where('id', '=', $id);

        //Complete the data
        $data = [
            "type" => "ressource",
            "user" => $user,
        ];

        return Writer::json_output($resp, 200, $data);
        }

}


?>