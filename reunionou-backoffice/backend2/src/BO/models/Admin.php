<?php
namespace reu\backoffice\BO\models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model {

    protected $table = 'admin';
    protected $primaryKey = 'id';
    public $timestamps = false;

}