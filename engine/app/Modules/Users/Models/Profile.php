<?php namespace App\Models;

use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model{

    use \TraitsFunc;

    protected $table = 'customers_t';
    protected $primaryKey = 'id';
    public $timestamps = false;

    function Country() {
        return $this->belongsTo('App\Models\Country', 'country_id');
    }

    function Region() {
        return $this->belongsTo('App\Models\Region', 'region_id');
    }

    static function getProfile(){
        return self::where('customer_id', USER_ID)
            ->first();
    }
}
