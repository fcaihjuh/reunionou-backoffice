<?php

namespace reu\back1\app\controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

//Model
use \reu\back1\app\models\Comment;


//Error

class commentController {

    private $container;

    public function __construct(\Slim\Container $container){
        $this->container = $container;
    }


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

    public function getOneComment(Request $res, Response $resp, array $args): Response {

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

}


?>