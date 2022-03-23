<?php

namespace reu\back1\app\controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

//Model
use \reu\back1\app\models\User as User;

use \reu\back1\app\utils\Writer as Writer;
//use DateTime;
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

        return Writer::json_output($resp, 200, $data);
    }

    public function getOneUser(Request $req, Response $resp, array $args): Response {

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

 

    public function signUp(Request $req, Response $resp, array $args): Response {
        
        //Les données reçues pour le nouveau membre
        $createUser = $req->getParsedBody();


        if ($req->getAttribute('has_errors')) {

            $errors = $req->getAttribute('errors');

            if (isset($createUser['fullname'])) {
                ($this->c->get('logger.error'))->error("error",$errors['fullname']);
                return Writer::json_error($resp, 403, "Le champ 'fullname' ne doit pas être vide et doit contenir que des lettres");
            }
            if (isset($createUser['mail'])) {
                ($this->c->get('logger.error'))->error("error",$errors['mail']);
                return Writer::json_error($resp, 403, "Le champ 'email' ne doit pas être vide et doit être valide");
            }
            if (isset($createUser['username'])) {
                ($this->c->get('logger.error'))->error("error",$errors['username']);
                return Writer::json_error($resp, 403, "Le champ 'username' ne doit pas être vide et doit être valide");
            }
            if (isset($createUser['password'])) {
                ($this->c->get('logger.error'))->error("error",$errors['password']);
                return Writer::json_error($resp, 403, "Le champ 'password' ne doit pas être vide et doit être valide");
            }
        } else {

            //créer le membre et son id
            $new_user = new User();
            //$new_user_id = Uuid::uuid4();
            //$new_user->id =  $new_user_id;
            $new_user->fullname = filter_var($createUser['fullname'], FILTER_SANITIZE_STRING);
            $new_user->mail = filter_var($createUser['mail'], FILTER_SANITIZE_EMAIL);
            $new_user->username = filter_var($createUser['username'], FILTER_SANITIZE_STRING);
            $new_user->password = filter_var($createUser['password'], FILTER_UNSAFE_RAW);

            //Création du token unique et cryptographique
            $token_user = random_bytes(32);
            $token_user = bin2hex($token_user);
            //$new_user->token = $token_user;
            
            $new_user->save();


            // Récupération du path pour le location dans header
            $path_user = $this->container->router->pathFor(
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