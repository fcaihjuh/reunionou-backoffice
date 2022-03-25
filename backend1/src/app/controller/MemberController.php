<?php

namespace reu\back1\app\controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use \reu\back1\app\models\User as User;

use \reu\back1\app\utils\Writer as Writer;
use Ramsey\Uuid\Uuid;

use Firebase\JWT\JWT;



//Error

class MemberController {

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
        $userData = $req->getParsedBody();

            if (!isset($userData['fullname'])) {
                return Writer::json_error($resp, 400, "Le champ 'fullname' ne doit pas être vide et doit contenir que des lettres");
            }
            if (!isset($userData['mail'])) {
                return Writer::json_error($resp, 400, "Le champ 'email' ne doit pas être vide et doit être valide");
            }
            if (!isset($userData['username'])) {
                return Writer::json_error($resp, 400, "Le champ 'username' ne doit pas être vide et doit être valide");
            }
            if (!isset($userData['password'])) {
                return Writer::json_error($resp, 400, "Le champ 'password' ne doit pas être vide et doit être valide");
            } 

        try {

            //créer le membre et son id
            $new_user = new User();
            $new_user->id = Uuid::uuid4()->toString();
            $new_user->fullname = filter_var($userData['fullname'], FILTER_SANITIZE_EMAIL);
            $new_user->mail = filter_var($userData['mail'], FILTER_SANITIZE_EMAIL);
            $new_user->username = filter_var($userData['username'], FILTER_SANITIZE_STRING);
            $password=filter_var($userData['password'], FILTER_UNSAFE_RAW);
            $passwordHash=password_hash($password, \PASSWORD_DEFAULT);
            $new_user->password = $passwordHash;

            //Création du token unique et cryptographique
            $token_user = random_bytes(32);
            $token_user = bin2hex($token_user);
            $new_user->token = $token_user;
            
            $new_user->save();


            // Récupération du path pour le location dans header
            /*$path_user = $this->container->router->pathFor(
                '',
                ['id' => $new_user->id]
            );*/

            //Construire la réponse : 
            $response = [
                "type" => "ressource",
                "user" => $new_user->toArray(),
            ];

            //Le retour
            $resp->getBody()->write(json_encode($response));
            $resp->withHeader('X-lbs-token', $new_user->token);
            return Writer::json_output($resp, 201);//->withHeader("Location", $path_user);
        }

        catch (\Exception $e) {
            return Writer::json_error($rs, 500, $e->getMessage());
        }

        } 

        public function signIn(Request $req, Response $resp, array $args): Response {

            $userData = $req->getParsedBody();

            if (!isset($userData['mail'])) {
                return Writer::json_error($resp, 400, "Le champ 'mail' ne doit pas être vide et doit contenir que des lettres");
            }
            if (!isset($userData['password'])) {
                return Writer::json_error($resp, 400, "Le champ 'password' ne doit pas être vide et doit être valide");
            }

            try{
                $user = User::where('mail',filter_var($userData['mail'], FILTER_SANITIZE_EMAIL))->firstOrFail();
                $check=password_verify($userData['password'], $user->password);
                if($check){
                    $token = JWT::encode( [ 'iss'=>'http://docketu.iutnc.univ-lorraine.fr:62640',
                                            'aud'=>'http://docketu.iutnc.univ-lorraine.fr:62640',
                                            'iat'=>time(), 'exp'=>time()+3600,
                                            'uid' => $user->id,
                                            'lvl' => '0' ],
                                            $secret, 'HS512' );
                    return Writer::json_output($resp, 200);
                }
                else{
                    return Writer::json_error($resp, 400, 'Wrong login or password');
                }

            }
            catch (\Exception $e) {
                return Writer::json_error($resp, 500, $e->getMessage());
            }
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

    public function deleteUser(Request $req, Response $resp, array $args): Response {

        //Get the id in the URI
        $id = $args['id'];

        //Get the user with some id
        $user = User::select()
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