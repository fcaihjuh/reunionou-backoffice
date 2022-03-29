<?php

namespace reu\back1\app\controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

//Model
use \reu\back1\app\models\User as User;

use \reu\back1\app\utils\Writer as Writer;
//use DateTime;
use Ramsey\Uuid\Uuid;
use Firebase\JWT\JWT;




//Error

class memberController {

    private $container;

    public function __construct(\Slim\Container $container){
        $this->container = $container;
    }
    

    public function getAllUsers(Request $req, Response $resp, array $args): Response {
        
        //Get all the Users
        $users = User::select(['id', 'mail', 'fullname', 'password'])
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
        $user = User::select(['id', 'mail', 'fullname', 'password'])
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
        //Les données reçues pour le nouveau membre
        $userData = $req->getParsedBody();

            if (!isset($userData['fullname'])) {
                return Writer::json_error($resp, 400, "Le champ 'fullname' ne doit pas être vide et doit être valide");
            }
            if (!isset($userData['mail'])) {
                return Writer::json_error($resp, 400, "Le champ 'mail' ne doit pas être vide et doit être valide");
            }
            if (!isset($userData['username'])) {
                return Writer::json_error($resp, 400, "Le champ 'username' ne doit pas être vide et doit être valide");
            }
            if (!isset($userData['password'])) {
                return Writer::json_error($resp, 400, "Le champ 'password' ne doit pas être vide et doit être valide");
            } 

        try {

            $user = User::where('mail', $email)->first();
            if(is_null($user)){
                //créer le membre et son id
                $new_user = new User();
                $new_user->id = Uuid::uuid4()->toString();
                $new_user->fullname = filter_var($userData['fullname'], FILTER_SANITIZE_EMAIL);
                $new_user->mail = filter_var($userData['mail'], FILTER_SANITIZE_EMAIL);
                $new_user->username = filter_var($userData['username'], FILTER_SANITIZE_STRING);

                $password=filter_var($userData['password'], FILTER_UNSAFE_RAW);
                $new_user->password = AuthController::hashPassword($password);;

                //Création du token unique et cryptographique
                $token_user = bin2hex(random_bytes(32));
                $new_user->token = $token_user;
                
                $new_user->save();

                $response = [
                    "post" => "OK",
                ];

                return Writer::json_output($resp, 201, $response);
            }
            else{
                return Writer::json_error($resp, 400, 'This email is already taken');
            } 
        }
        catch (\Exception $e) {
            return Writer::json_error($resp, 500, $e->getMessage());
        }

        } 

    public function signIn(Request $req, Response $resp, array $args): Response {
        $userData = $req->getParsedBody();

            if (!isset($userData['mail'])) {
                return Writer::json_error($resp, 400, "Le champ 'mail' ne doit pas être vide et doit être valide");
            }
            if (!isset($userData['password'])) {
                return Writer::json_error($resp, 400, "Le champ 'password' ne doit pas être vide et doit être valide");
            }

            try{
                $user = User::where('mail',filter_var($userData['mail'], FILTER_SANITIZE_EMAIL))->firstOrFail();
                if(AuthController::verifyPassword($userData['password'], $user->password)){
                    $data=[
                        'post'      => true,
                        'fullname'  => $user->fullname,
                        'email'     => $user->email,
                        'token'     => $user->token
                    ];
                    return Writer::json_output($resp, 200, $data);
                } else{
                    return Writer::json_error($resp, 400, 'Wrong login or password');
                }
            }
            catch (\Exception $e) {
                return Writer::json_error($resp, 400, $e->getMessage());
            }
        } 

    public function signOut(Request $req, Response $resp, array $args): Response {

        //Get the id in the URI
        $id = $args['id'];

        //Get the user with some id
        $user = User::select(['id', 'mail', 'fullname', 'password'])
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