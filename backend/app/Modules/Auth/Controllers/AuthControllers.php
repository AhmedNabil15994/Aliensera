<?php namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ApiAuth;
use Illuminate\Support\Facades\Hash;

class AuthControllers extends Controller {

    use \TraitsFunc;

    public function login() {
        if(\Session::has('user_id')){
            return redirect('/');
        }
        return view('login');
    }

	public function doLogin() {

        $input = \Input::all();

        $rules = array(
            'email' => 'required',
            'password' => 'required',
        );

        $message = array(
            'email.required' => "Sorry Email Required",
            'password.required' => "Sorry Password Required",
        );

        $validate = \Validator::make($input, $rules,$message);

        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect('/login');
        }

        $email = $input['email'];
        $userObj = User::getLoginUser($email);
        
        if ($userObj == null) {
            \Session::flash('error', "Email not found or inactive");
            return redirect('/login');
        }

        $checkPassword = Hash::check($input['password'], $userObj->password);

        if ($checkPassword == null) {
            \Session::flash('error', "Wrong password");
            return redirect('/login');  
        }

        $dateTime = DATE_TIME;
        $userObj->last_login = $dateTime;
        $userObj->save();

        $profile = $userObj->Profile;
        $group = $profile->Group;

        $isAdmin = in_array($profile->group_id, [1, 2]) ? true : false;

        $dataObj = new \stdClass();
        $dataObj->email = $userObj->email;
        $dataObj->first_name = $profile->first_name;
        $dataObj->last_name = $profile->last_name;
        $dataObj->full_name = $profile->display_name;
        $dataObj->last_login = $userObj->last_login;
        $dataObj->group_id = (int) $profile->group_id;

        session(['group_id' => $dataObj->group_id]);
        session(['last_login' => $dataObj->last_login]);
        session(['user_id' => $userObj->id]);
        session(['first_name' => $dataObj->first_name]);
        session(['last_name' => $dataObj->last_name]);
        session(['full_name' => $dataObj->full_name]);
        session(['email' => $dataObj->email]);
        session(['is_admin' => $isAdmin]);
        session(['group_name' => $profile->Group->title]);

        \Session::flash('success', "Welcome To AlienSera " . $dataObj->first_name);
        return redirect('/');
	}

	public function logout() {
        \Auth::logout();
        session()->flush();

        \Session::flash('success', "See you soon ;)");
        return redirect('/login');
	}

    public function register(){
        $input = \Input::all();
        $rules = array(
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|min:6',
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'gender' => 'required',
        );

        $message = array(
            'email.required' => "Sorry Email Required",
            'email.required' => "Sorry This Email Already Exists",
            'password.required' => "Sorry Password Required",
            'password.min' => "Sorry Password Must Be At Least 6 Characters",
            'password_confirmation.required' => "Sorry Password Confirmation Required",
            'password_confirmation.min' => "Sorry Passwords Don't Match",
            'first_name.required' => "Sorry First Name Required",
            'last_name.required' => "Sorry Last Name Required",
            'phone.required' => "Sorry Phone Required",
            'gender.required' => "Sorry Gender Required",

        );

        $validate = \Validator::make($input, $rules,$message);

        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect('/login#signup');
        }

        if($input['password'] != $input['password_confirmation']){
            \Session::flash('error', "Sorry Passwords Don't Match");
            return redirect('/login#signup');
        }

        $userId = User::createOneUser(2);

        $userObj = User::getOne($userId);

        $dateTime = DATE_TIME;
        $userObj->last_login = $dateTime;
        $userObj->is_active = 1;
        $userObj->save();

        $profile = $userObj->Profile;
        $group = $profile->Group;

        $isAdmin = in_array($profile->group_id, [1, 2]) ? true : false;

        $dataObj = new \stdClass();
        $dataObj->email = $userObj->email;
        $dataObj->first_name = $profile->first_name;
        $dataObj->last_name = $profile->last_name;
        $dataObj->full_name = $profile->display_name;
        $dataObj->last_login = $userObj->last_login;
        $dataObj->group_id = (int) $profile->group_id;

        session(['group_id' => $dataObj->group_id]);
        session(['last_login' => $dataObj->last_login]);
        session(['user_id' => $userObj->id]);
        session(['first_name' => $dataObj->first_name]);
        session(['last_name' => $dataObj->last_name]);
        session(['full_name' => $dataObj->full_name]);
        session(['email' => $dataObj->email]);
        session(['is_admin' => $isAdmin]);
        session(['group_name' => $profile->Group->title]);

        \Session::flash('success', "Welcome To AlienSera " . $dataObj->first_name);
        return redirect('/');
    }

}
