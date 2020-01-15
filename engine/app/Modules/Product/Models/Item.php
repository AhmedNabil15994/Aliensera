<?php namespace App\Models;

use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Model;

class Item extends Model{

    use \TraitsFunc;

    protected $table = 'serialized_item_t';
    protected $primaryKey = 'id';
    public $timestamps = false;


}
