<?php namespace App\Models;

use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Model;

class Country extends Model{

    use \TraitsFunc;

    protected $table = 'countries_t_new';
    protected $primaryKey = 'id';
    public $timestamps = false;


}
