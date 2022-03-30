<?php
    namespace reu\back1\app\models;
    
    class Event extends \Illuminate\Database\Eloquent\Model {

        protected $table      = 'event'; 
        protected $primaryKey = 'id';     
        public    $timestamps = false;    

        public function author(){
            return $this->belongsTo('reu\back\models\User', 'id_user');
        }
    
        public function comment(){
            return $this->hasMany('reu\back\models\Comment', 'id_event');
        }

        

    }