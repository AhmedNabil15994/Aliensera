<?php namespace App\Models;

use App\Models\ApiKeys;
use Illuminate\Database\Eloquent\Model;

class ApiAuth extends Model{

    protected $table = 'api_auth';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    static function logoutOtherSessions($userId, $apiKeys) {
        $authList = self::where('user_id', $userId)
            ->whereIn('api_id', $apiKeys)
            ->get();

        foreach($authList as $key => $value) {
            $value->auth_expire = 0;
            $value->save();
        }

        return true;
    }

    static function checkUserToken($token) {
        
        $apiKey = ApiKeys::checkApiKey()->id;

        $authCheck = self::where('auth_token', $token)
            ->where('api_id', $apiKey)
            ->where('auth_expire', 1)
            ->first();
        
        if($authCheck == null) {
            return null;
        }

        if(!defined('USER_ID')) {
            define('USER_ID', $authCheck->user_id);
        }

        $dataObj = User::getUser();
        if($dataObj == null) {
            return null;
        }

        $profileObj = $dataObj->Profile;

        if($profileObj == null){
            return null;
        }

        session(['token' => $dataObj->token]);
        session(['last_login' => $dataObj->last_login]);
        session(['user_id' => $authCheck->user_id]);
        session(['first_name' => $profileObj->first_name]);
        session(['last_name' => $profileObj->last_name]);
        session(['full_name' => $profileObj->display_name]);
        session(['email' => $dataObj->email]);
        session(['phone' => $dataObj->phone]);

        return ['user' => $dataObj, 'profile' => $profileObj, 'auth' => $authCheck, 'user_id'=>$authCheck->user_id];
    }
}
