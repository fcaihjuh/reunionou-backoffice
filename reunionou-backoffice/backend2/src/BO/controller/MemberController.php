<?php

namespace reu\backoffice\BO\controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use reu\backoffice\BO\controller\Controller;
use \reu\backoffice\BO\models\User;
use \reu\backoffice\BO\models\Admin;
use \reu\backoffice\BO\models\Event;
use \reu\backoffice\BO\models\Comment;

class MemberController extends Controller {

    public function createUser(Request $req, Response $resp, array $args){ 

        $fullname = htmlspecialchars(trim($req->getParam('fullname')));
        $mail = htmlspecialchars(trim($req->getParam('mail')));
        $password = htmlspecialchars(trim($req->getParam('password')));
        $token = bin2hex(random_bytes(16));

        if(!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            $this->flash('Cette adresse email est invalide !', 'error');
        } 
        else {
            if(empty($fullname || $mail || $password)) {
                $this->flash('Veuillez renseigner tous les champs !', 'error');
            } else {
                $user = User::where('mail', '=', $mail)->count();
                if($user) {
                    $this->flash('Cette adresse e-mail est déjà utilisée !', 'error');
                } else {
                    $password_hash = AuthController::hashPassword($password);
                    User::insert(['fullname' => $fullname, 'mail' => $mail, 'password' => $password_hash, 'token' => $token]);
                    $this->flash("L'utilisateur a été créé avec succès !");
                }
            }
        }        
        return $this->redirect($resp, 'createUser');
    }

    public function userDelete(Request $req, Response $resp){
        $id = $req->getParam('id');
        $user = User::where('id', '=', $id)->count();
        
        if($user) {
            User::where('id', $id)->delete();
            $events = Event::where('id_user', $id)->get();
            Event::where('id_user', $id)->delete();

            foreach($events as $event) {
                Comment::where('id_event', $event->id)->delete();
            }

            return 'success';
            
        } else {

            return "User $id not found";
        }
    }

    public function userUpdate(Request $req, Response $resp) {
        
        $id = $req->getParam('id');

        $fullname = htmlspecialchars(trim($req->getParam('fullname')));
        $mail = htmlspecialchars(trim($req->getParam('mail')));
        $checkToken =  htmlspecialchars(trim($req->getParam('token')));
        $newtoken = bin2hex(random_bytes(16));


        if(!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            $this->flash('Cette adresse mail est invalide !', 'error');
        } else {
            $user = User::where('id', '=', $id)->count();
            if(!$user) {
                return "User not found";
            } else {
                if($checkToken == 1)  {
                    User::where('id', '=', $id)->update(['fullname' => $fullname, 'mail' => $mail, 'username' => $username, 'token' => $newToken]);
                } else {
                    User::where('id', '=', $id)->update(['fullname' => $fullname, 'mail' => $mail]);
                }

                return 'success';
            }
        } 
    }

    public function login(Request $req, Response $resp, array $args) {

        $mail = htmlspecialchars(trim($req->getParam('mail')));
        $password = htmlspecialchars(trim($req->getParam('password')));
        
        if(empty($mail) || empty($password)) {
            $this->flash('Un ou plusieurs champs sont vide(s) !', 'error');
        } else {
            if(!AuthController::login($mail, $password)) {
                $this->flash('Adresse email ou mot de passe incorrect !', 'error');
                return $this->redirect($resp, 'login');
            } else {
                return $this->redirect($resp, 'home');
            }
        }
    }

    public function profile(Request $req, Response $resp, array $args) {


        
        $currentPassword = htmlspecialchars(trim($req->getParam('currentPassword')));;
        $newPassword = htmlspecialchars(trim($req->getParam('newPassword')));;

        
        $db_password = Admin::select('password')->where('id', $_SESSION['id'])->first();

        if(AuthController::verifyPassword($currentPassword, $db_password->password)) {
            $hashedPassword = AuthController::hashPassword($newPassword);
            Admin::where('id', $_SESSION['id'])->update(['password' => $hashedPassword]);
            $this->flash("Le mot de passe a bien été sauvegardé !");
        } else {
            $this->flash("Mot de passe actuel incorrect !", 'error');
        }
        return $this->redirect($resp, 'profile');
    }

}
