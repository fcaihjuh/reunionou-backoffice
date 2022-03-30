<?php
namespace reu\backoffice\BO\models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    
    protected $table = 'event';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [];

}