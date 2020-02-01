<?php namespace App\Models;

use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model{

    use \TraitsFunc;

    protected $table = 'profiles';
    protected $primaryKey = 'id';
    public $timestamps = false;

    function University() {
        return $this->belongsTo('App\Models\University', 'university_id');
    }

    function Faculty() {
        return $this->belongsTo('App\Models\Faculty', 'faculty_id');
    }

    static function getProfile(){
        return self::where('user_id', USER_ID)
            ->first();
    }
}
