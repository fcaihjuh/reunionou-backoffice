<?php

namespace reu\back1\app\controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

//Model
use \reu\back1\app\models\User as User;
use \reu\back1\app\models\Event as Event;
use \reu\back1\app\models\Comment as Comment;

use \reu\back1\app\utils\Writer as Writer;
use DateTime;
use Ramsey\Uuid\Uuid;



//Error

class memberController {

    private $container;

    public function __construct(\Slim\Container $container){
        $this->container = $container;
    }

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

        //Configure the response headers
        $resp = $resp->withStatus(200)
            ->withHeader('Content-Type', 'application/json; charset=utf-8');

        //Write in the body with data encode with json_encode
        $resp->getBody()->write(json_encode($data));

        //Return the response
        return $resp;

    }

    public function getOneUser(Request $req, Response $resp, array $args): Response {

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

        //Configure the response header
        $resp = $resp->withStatus(200)
            ->withHeader('Content-Type', 'application/json; charset=utf-8');
        
        //Write in the body with data encode a json_encode
        $resp->getBody()->write(json_encode($data));

        //Return the response 
        return $resp;

    } 

 

    public function signUp(Request $req, Response $resp, array $args): Response {
        //Les données reçues pour le nouvel utilisateur
        $received_user = $req->getParsedBody();

        if ($req->getAttribute('has_errors')) {

            $errors = $req->getAttribute('errors');

            if (isset($errors['nom_user'])) {
                ($this->c->get('logger.error'))->error("error",$errors['nom_user']);
                return Writer::json_error($resp, 403, "Le champ 'nom_user' ne doit pas être vide et doit contenir que des lettres");
            }
            if (isset($errors['mail_user'])) {
                ($this->c->get('logger.error'))->error("error",$errors['mail_user']);
                return Writer::json_error($resp, 403, "Le champ 'mail_user' ne doit pas être vide et doit être valide");
            }
            if (isset($errors['pwd_user'])) {
                ($this->c->get('logger.error'))->error("error",$errors['pwd_user']);
                return Writer::json_error($resp, 403, "Le champ 'pwd_user' ne doit pas être vide et doit être valide");
            }
            if (isset($errors['events'])) {
                ($this->c->get('logger.error'))->error("error",$errors['events']);
                return Writer::json_error($resp, 403, "le champ 'events' ne doit pas être vide et toutes les informations doivent être valide");
            }
        } else {

            //Création du token unique et cryptographique
            $token_user = random_bytes(32);
            $token_user = bin2hex($token_user);

            //créer le membre et son id
            $new_user = new User();
            $new_user_id = Uuid::uuid4();
            $new_user->id =  $new_user_id;

            $new_user->nom = filter_var($received_user['nom_user'], FILTER_UNSAFE_RAW);
            $new_user->mail = filter_var($received_user['mail_user'], FILTER_SANITIZE_EMAIL);
            $new_user->nom = filter_var($received_user['pseudo_user'], FILTER_UNSAFE_RAW);
            $new_user->nom = filter_var($received_user['pwd_user'], FILTER_UNSAFE_RAW);
            
            $new_user->save();


            // Récupération du path pour le location dans header
            $path_user = $this->c->router->pathFor(
                'getOneUser',
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
            return writer::json_output($resp, 201)->withHeader("location", $path_user);
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

        //Configure the response header
        $resp = $resp->withStatus(200)
            ->withHeader('Content-Type', 'application/json; charset=utf-8');
        
        //Write in the body with data encode a json_encode
        $resp->getBody()->write(json_encode($data));

        //Return the response 
        return $resp;
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

        //Configure the response header
        $resp = $resp->withStatus(200)
            ->withHeader('Content-Type', 'application/json; charset=utf-8');
        
        //Write in the body with data encode a json_encode
        $resp->getBody()->write(json_encode($data));

        //Return the response 
        return $resp;
    } 

    
}


?>