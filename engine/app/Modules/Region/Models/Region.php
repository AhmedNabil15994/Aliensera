<?php namespace App\Models;

use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Model;

class Region extends Model{

    use \TraitsFunc;

    protected $table = 'regions_t';
    protected $primaryKey = 'id';
    public $timestamps = false;


}
