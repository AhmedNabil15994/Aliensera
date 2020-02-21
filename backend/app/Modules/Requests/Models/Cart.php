<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model {

    protected $table = 'carts';
    protected $primary_key = 'id';
    public $timestamps = false;
    
    use \TraitsFunc;

 }
