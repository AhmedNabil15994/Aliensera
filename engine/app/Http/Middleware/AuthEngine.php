<?php namespace App\Http\Middleware;

use App\Models\ApiAuth;
use App\Models\ApiKeys;
use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Session;

class AuthEngine
{

    public function handle($request, Closure $next){

        if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
            return \TraitsFunc::ErrorMessage("unauthorized", 401);
        }

        $apiAuthToken = $_SERVER['HTTP_AUTHORIZATION'];

        if ($request->segment(1) == null && $apiAuthToken != '') {
            return \TraitsFunc::ErrorMessage("unauthorized", 401);
        }

        if (in_array($request->segment(1), ['login', 'register'])) {
            return \TraitsFunc::ErrorMessage("unauthorized", 401);
        }

        //Check token
        $checkAuth = ApiAuth::checkUserToken($apiAuthToken);
        if($checkAuth == null){
            \Auth::logout();
            session()->flush();
            return \TraitsFunc::ErrorMessage("Session Expired, Please Login Again!", 401);
        }

        // Update logins date realtime
        $userObj = User::getOne(USER_ID);
        if ($userObj != null) {
            $userObj->last_login = DATE_TIME;
            $userObj->save();
        }

        define('APP_TOKEN', $apiAuthToken);
        return $next($request);
    }
}
