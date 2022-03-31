<?php

namespace reu\back1\app\controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use \reu\back1\app\models\User;

use \reu\back1\app\utils\Writer;
use Ramsey\Uuid\Uuid;

use Firebase\JWT\JWT;



//Error

class MemberController {

    private $container;

    public function __construct(\Slim\Container $container){
        $this->container = $container;
    }

    public function getUsers(Request $req, Response $resp, array $args): Response {
        
        $users = User::select(['id', 'mail', 'fullname'])->get();
        
        $data = [
            "type" => "collection",
            "count" => count($users),
            "users" => $users,
        ];

        return Writer::json_output($resp, 200, $data);
    }

    public function getUser(Request $req, Response $resp, array $args): Response {

        $id = $args['id'];

        $user = User::select(['id', 'mail', 'fullname'])
            ->where('id', '=', $id)->firstOrFail();

        $data = [
            "type" => "ressource",
            "user" => $user,
        ];

        return Writer::json_output($resp, 200, $data);

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
            if (!isset($userData['password'])) {
                return Writer::json_error($resp, 400, "Le champ 'password' ne doit pas être vide et doit être valide");
            } 

        try {

            $user = User::where('mail', $userData['mail'])->first();
            if(is_null($user)){
                //créer le membre et son id
                $new_user = new User();
                $new_user->fullname = filter_var($userData['fullname'], FILTER_SANITIZE_STRING);
                $new_user->mail = filter_var($userData['mail'], FILTER_SANITIZE_EMAIL);

                $password=filter_var($userData['password'], FILTER_UNSAFE_RAW);
                $new_user->password = AuthController::hashPassword($password);;

                //Création du token unique et cryptographique
                $token_user = bin2hex(random_bytes(16));
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
                        'mail'     => $user->mail,
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
}


?>