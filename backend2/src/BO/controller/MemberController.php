<?php


class MemberController extends Controller {

    private $container;

    public function __construct(\Slim\Container $container){
        $this->container = $container;
    }

    public function createUser(Request $req, Response $resp, array $args) : Response {

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

        $fullname = htmlspecialchars(trim($userData['fullname'])));
        $mail = htmlspecialchars(trim($userData['mail']));
        $username = htmlspecialchars(trim($userData['username']));
        $password = htmlspecialchars(trim($userData['password']));
        $token = bin2hex(random_bytes(16));

        if(!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            $this->flash('Cette adresse email est invalide !', 'error');
        } 
        else {
            if(empty($fullname || $mail || $username || $password)) {
                $this->flash('Veuillez renseigner tous les champs !', 'error');
            } else {
                $user = User::where('mail', '=', $mail)->count();
                if($user) {
                    $this->flash('Cette adresse e-mail est déjà utilisée !', 'error');
                } else {
                    $password_hash = AuthController::hashPassword($password);
                    User::insert(['fullname' => $fullname, 'mail' => $mail, 'username' => $username, 'password' => $password_hash, 'token' => $token]);
                    $this->flash("L'utilisateur a été créé avec succès !");
                }
            }
        }        
        return $this->redirect($response, 'createUser');
    }

    public function userDelete(Request $req, Response $resp, array $args) : Response {
        $id = $args['id'];
        $user = User::where('id', '=', $id)->count();
        
        if($user) {
            User::where('id', $id)->delete();
            $events = Event::where('user_id', $id)->get();
            Event::where('user_id', $id)->delete();

            foreach($events as $event) {
                Comment::where('event_id', $event->id)->delete();
            }

            $data=[
                'delete' => 'OK'
            ]

            return Writer::json_output($resp, 200, $data);
            
        } else {

            return Writer::json_error($resp, 400, "User $id not found");;
        }
    }

    public function userUpdate(Request $req, Response $resp, array $args) : Response {
        
        $id = $args['id'];

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

        $fullname = htmlspecialchars(trim($userData['fullname'])));
        $mail = htmlspecialchars(trim($userData['mail']));
        $username = htmlspecialchars(trim($userData['username']));
        $checkToken =  htmlspecialchars(trim($userData['token']));
        $newtoken = bin2hex(random_bytes(16));


        if(!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            $this->flash('Cette adresse mail est invalide !', 'error');
        } else {
            $user = User::where('id', '=', $id)->count();
            if(!$user) {
                return Writer::json_error($resp, 400, "User $id not found");;
            } else {
                if($checkToken == 1)  {
                    User::where('id', '=', $id)->update(['fullname' => $fullname, 'email' => $mail, 'username' => $username 'token' => $newToken]);
                } else {
                    User::where('id', '=', $id)->update(['fullname' => $fullname, 'email' => $mail]);
                }
                
                $data= [
                    'put' => 'OK'
                ]

                return Writer::json_output($resp, 200, $data);;
            }
        } 
    }

    public function login(Request $req, Response $resp, array $args) {

        $userData = $req->getParsedBody();

        if (!isset($userData['mail'])) {
            return Writer::json_error($resp, 400, "Le champ 'mail' ne doit pas être vide et doit être valide");
        }
        if (!isset($userData['password'])) {
            return Writer::json_error($resp, 400, "Le champ 'password' ne doit pas être vide et doit être valide");
        }

        $mail = htmlspecialchars(trim($userData['mail'])));
        $password = $userData['password'];
        
        if(empty($mail) || empty($password)) {
            $this->flash('Un ou plusieurs champs sont vide(s) !', 'error');
        } else {
            if(!AuthController::login($mail, $password)) {
                $this->flash('Adresse email ou mot de passe incorrect !', 'error');
            } else {
                return $this->redirect($resp, 'home');
            }
        }
        return $this->redirect($resp, 'login');
    }

    public function profile(Request $req, Response $resp, array $args) {

        $userData = $req->getParsedBody();

        if (!isset($userData['currentPassword'])) {
            return Writer::json_error($resp, 400, "Le champ 'currentPassword' ne doit pas être vide et doit être valide");
        }
        if (!isset($userData['newPassword'])) {
            return Writer::json_error($resp, 400, "Le champ 'newPassword' ne doit pas être vide et doit être valide");
        }
        
        $currentPassword = $userData['currentPassword'];
        $newPassword = $userData['newPassword'];

        
        $db_password = Admin::select('password')->where('id', $_SESSION['id'])->first();

        if(AuthController::verifyPassword($currentPassword, $db_password->password)) {
            $hashedPassword = AuthController::hashPassword($newPassword);
            Admin::where('id', $_SESSION['id'])->update(['password' => $hashedPassword]);
            $this->flash("Le mot de passe a bien été sauvegardé !");
        } else {
            $this->flash("Mot de passe actuel incorrect !", 'error');
        }
        return $this->redirect($response, 'profile');
    }

}
