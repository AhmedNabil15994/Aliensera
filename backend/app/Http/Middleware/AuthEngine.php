<?php namespace App\Http\Middleware;

use App\Models\ApiAuth;
use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Session;

class AuthEngine
{

    public function handle($request, Closure $next){

        define('USER_ID', Session::get('user_id'));
        if ($request->segment(1) == null && !(USER_ID && USER_ID != '')) {
            \Auth::logout();
            session()->flush();

            \Session::flash('error', "Please Login First!");
            return Redirect('/login');
        }

        if (in_array($request->segment(1), ['login','register','reset'])) {
            if (in_array($request->segment(1), ['login','register','reset'])) {
                return $next($request);
            } else {

            }
        }

        if (!(USER_ID && USER_ID != '')) {
            \Auth::logout();
            session()->flush();

            \Session::flash('error', "Please Login First!");
            return Redirect('/login');
        }

        define('GROUP_ID', Session::get('group_id'));
        define('FIRST_NAME', Session::get('first_name'));
        define('LAST_NAME', Session::get('last_name'));
        define('FULL_NAME', Session::get('full_name'));
        define('GROUP_NAME', Session::get('group_name'));
        define('PAGINATION', \App\Models\Variable::getVar('PAGINATION'));
        // Update login date realtime
        $userObj = User::getOne(USER_ID);
        if ($userObj != null) {
            $userObj->last_login = DATE_TIME;
            $userObj->save();
        }

        $permissions = User::checkUserPermissions($userObj);        
        define('IS_ADMIN', $userObj->Profile->group_id == 1 ? true : false);
        define('PERMISSIONS', $permissions);

        return $next($request);
    }
}
