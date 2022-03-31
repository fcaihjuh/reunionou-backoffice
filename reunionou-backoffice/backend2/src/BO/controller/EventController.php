<?php

namespace reu\backoffice\BO\controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use \reu\backoffice\BO\models\Event;
use \reu\backoffice\BO\models\Comment;

class EventController extends Controller{

    
    public function deleteEvent(Request $req, Response $resp){
    
        $id = $req->getParam('id');
        $event = Event::where('id', '=', $id)->count();
    
        if($event)
        {
             Event::where('id', $id)->delete();
             Comment::where('id_event', $id)->delete();
    
             return 'success';    
        }
        else
        {
            return "Event $id not found";
        }
    }

}
