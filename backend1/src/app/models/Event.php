<?php
    namespace reu\back1\app\models;
    
    class Event extends \Illuminate\Database\Eloquent\Model {

        protected $table      = 'event'; 
        protected $primaryKey = 'id';     
        public    $timestamps = false;    

    }