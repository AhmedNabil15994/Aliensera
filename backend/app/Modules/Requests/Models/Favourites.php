<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favourites extends Model {

    protected $table = 'favourites';
    protected $primary_key = 'id';
    public $timestamps = false;
    
    use \TraitsFunc;

 }
