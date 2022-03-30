<?php

namespace reu\back1\app\controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use \reu\back1\app\models\Event;
use \reu\back1\app\models\Comment;
use \reu\back1\app\utils\Writer;

class CommentController {

    private $container;

    public function __construct(\Slim\Container $container){
        $this->container = $container;
    }

    public function getComments(Request $req, Response $resp, array $args): Response {

        $comments = Comment::where(['id' => $id, 'id_user' => $_SESSION['id']])->get();;

        $data = [
            "type" => "collection", 
            "count" => count($comments),
            "comments" => $comments,
        ];

        return Writer::json_output($resp, 200, $data);
    }

    public function getComment(Request $res, Response $resp, array $args): Response {

        $id = $args['id'];
        $owned = Comment::where(['id' => $id, 'id_user' => $_SESSION['id']])->count();

        if($owned){
            $comment = Comment::where('id_event', '=', $id)->orderBy('id', 'DESC')->get();
            $data = [
                "type" => "ressource",
                "comment" => $comment,
            ];
            return Writer::json_output($resp, 200, $data);
        }
        else{
            return Writer::json_error($resp, 403, 'Permission forbidden');
        }
    }

    public function getPublicComments(Request $res, Response $resp, array $args): Response {
        $token = $args['token'];
        $event_id = Event::where('token', $token)->first()->id;
        $comments = Comment::where('event_id', $event_id)->orderBy('id', 'DESC')->get();
        $data = [
            "type" => "ressource",
            "event" => $comments,
        ];
        return Writer::json_output($resp, 200, $data);
    }

    public function postComment(Request $req, Response $resp, array $args): Response{

        $commentData = $req->getParsedBody();

        if (!isset($commentData['content'])) {
            return Writer::json_error($resp, 400, "Le champ 'content' ne doit pas être vide et doit être valide");
        }
        if (!isset($commentData['event_id'])) {
            return Writer::json_error($resp, 400, "Le champ 'event_id' ne doit pas être vide et doit être valide");
        }

        try{
            Comment::insert(['content' => $commentData['content'], 'id_user' => $_SESSION['id'], 'id_event' => $commentData['event_id']]);

            $data = [
                'post'      => true,
                'id_user'   => $_SESSION['id'],
                'id_event'  =>  $commentData['event_id']
            ];

            return Writer::json_output($resp, 201, $data);
        }
        catch (\Exception $e) {
            return Writer::json_error($resp, 500, $e->getMessage());
        }
    }
}


?>