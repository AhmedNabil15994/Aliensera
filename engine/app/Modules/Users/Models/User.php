<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model{

    use \TraitsFunc;

    protected $table = 'users';
    protected $primaryKey = 'id';
    public $timestamps = false;

    function Profile(){
        return $this->hasOne('App\Models\Profile', 'user_id');
    }

    static function generateUsername($slug, $id) {
        $userObj = self::NotDeleted()
            ->find($id);

        if ($userObj == null) {
            return false;
        }

        $encode = rand('10000', '1000000');
        $str = $slug . '-' . $encode;

        $userCheck = self::NotDeleted()
            ->where('username', $str)
            ->first();

        if ($userCheck != null) {
            return self::generateUsername($slug, $id);
        }

        $userObj->username = $str;
        $userObj->save();

        return $userObj->username;
    }

    static function getOne($id){
        return self::NotDeleted()
            ->with('Profile')
            ->where('id', $id)
            ->first();
    }

    static function getUserByUsername($username){
        return self::NotDeleted()
            ->where('username', $username)
            ->first();
    }

    static function checkUserByEmail($email, $notId = false){
        $dataObj = self::NotDeleted()
            ->where('email', $email);

        if ($notId != false) {
            $dataObj->whereNotIn('id', [$notId]);
        }

        return $dataObj->first();
    }

    static function checkUserByPhone($phone, $notId = false){
        $dataObj = self::NotDeleted()
            ->whereHas('Profile', function($profileQuery) use($phone) {
                $profileQuery->where('phone',$phone);
            });

        if ($notId != false) {
            $notId = (array) $notId;
            $dataObj->whereNotIn('id', $notId);
        }

        return $dataObj->first();
    }

    static function getUser() {
        return self::NotDeleted()
            ->with('Profile')
            ->where('id', USER_ID)
            ->first();
    }

    static function getLoginUser($email){
        $userObj = self::NotDeleted()
            ->with('Profile')
            ->where('email', $email)
            ->where('is_active', 1)
            ->first();

        if($userObj == null || $userObj->Profile->group_id != 3) {
            return false;
        }
        return $userObj;
    }


}
