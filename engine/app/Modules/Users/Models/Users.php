<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Users extends Model{

    use \TraitsFunc;

    protected $table = 'customer_login_data_t';
    protected $primaryKey = 'id';
    public $timestamps = false;

    function Profile(){
        return $this->hasOne('App\Models\Profile', 'id','customer_id');
    }

    static function getUser() {
        return self::NotDeleted()
            ->with('Profile')
            ->where('customer_id', USER_ID)
            ->first();
    }

    static function getLoginUser($username){
        $userObj = self::NotDeleted()
            ->where('username', $username)
            ->where('status', 1)
            ->first();

        if($userObj == null) {
            return false;
        }
        return $userObj;
    }

    static function getOne($id){
        return self::NotDeleted()
            ->with('Profile')
            ->where('id', $id)
            ->first();
    }


}
