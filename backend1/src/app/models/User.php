<?php
    namespace reu\back1\app\models;

    class User extends \Illuminate\Database\Eloquent\Model {

        protected $table      = 'user'; 
        protected $primaryKey = 'id';     
        public    $timestamps = false;    

    }