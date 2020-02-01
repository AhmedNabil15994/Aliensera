<?php namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Profile;
use App\Models\University;
use App\Models\Faculty;
use App\Models\ApiKeys;
use App\Models\ApiAuth;
use App\Models\Devices;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {

    use \TraitsFunc;

	public function login() {
        try{
            $_SERVER['HTTP_DEVICEKEY'];
        }catch(\Exception $e ){
            return \TraitsFunc::ErrorMessage("Please check device key", 400);
        }

        $input = \Input::all();

        $rules = [
            'email' => 'required|email',
            'password' => 'required',
            'mac_address' => 'required',
        ];

        $message = [
            'email.required' => "Sorry Email Required",
            'email.email' => "Sorry Email Must Be Email Type",
            'password.required' => "Sorry Password Required",
            'mac_address.required' => "Sorry Mac Address Required",
        ];

        $validate = \Validator::make($input, $rules, $message);

        if($validate->fails()){
            return \TraitsFunc::ErrorMessage($validate->messages()->first(), 400);
        }

        $email = $input['email'];
        $userObj = User::getLoginUser($email);
        if ($userObj == null) {
            return \TraitsFunc::ErrorMessage("Sorry this email not found, Or your email not active", 400);
        }

        $checkPassword = Hash::check($input['password'], $userObj->password);
        if ($checkPassword == null) {
            return \TraitsFunc::ErrorMessage("Sorry Password wrong, Please try again", 400);
        }

        $profileObj = $userObj->Profile;
        if($profileObj->mac_address == null){
            $profileObj->mac_address = $input['mac_address'];
            $profileObj->save();
        }else{
            if($profileObj->mac_address != $input['mac_address']){
                return \TraitsFunc::ErrorMessage("Sorry You Can't Login From This Device.", 400);
            }
        }

        $dataObj = self::LoginAction($userObj);

         //Check token
        $checkAuth = ApiAuth::checkUserToken($dataObj->token);
       
        if($checkAuth == null){
            \Auth::logout();
            session()->flush();
 
            return \TraitsFunc::ErrorMessage("Error, Please contact with admin to recheck user data!", 401);
        }

        Devices::applyNewDevice($checkAuth['auth']->id);

        $statusObj['data'] = new \stdClass();
        $statusObj['data'] = $dataObj;
        $statusObj['status'] = \TraitsFunc::SuccessResponse("Login Success");
		return \Response::json((object) $statusObj);
	}

	static function LoginAction($userObj) {

        $dateTime = DATE_TIME;
        $apiKeyId = ApiKeys::checkApiKey()->id;

        //ApiAuth::logoutOtherSessions($userObj->id, $apiKeyId);

        $ApiAuth = new ApiAuth();
        $ApiAuth->auth_token = md5(uniqid(rand(), true));
        $ApiAuth->auth_expire = 1;
        $ApiAuth->api_id = $apiKeyId;
        $ApiAuth->user_id = $userObj->id;
        $ApiAuth->created_at = $dateTime;
        $ApiAuth->save();

        $userObj->last_login = $dateTime;
        $userObj->save();

        $token_value = $ApiAuth->auth_token;
        $profile = $userObj->Profile;

        $dataObj = new \stdClass();
        $dataObj->email = $userObj->email;
        $dataObj->phone = $profile->phone;
        $dataObj->first_name = $profile->first_name;
        $dataObj->last_name = $profile->last_name;
        $dataObj->full_name = $profile->display_name;
        $dataObj->last_login = $userObj->last_login;
        $dataObj->token = $token_value;
        $dataObj->auth_id = $ApiAuth->id;
        $dataObj->university_id = (int) $profile->university_id;
        $dataObj->university = $profile->University ? $profile->University->title : null;
        $dataObj->faculty_id = (int) $profile->faculty_id;
        $dataObj->faculty = $profile->Faculty ? $profile->Faculty->title : null;
        $dataObj->year = $profile->year;

        return $dataObj;
    }

	public function logout() {
		$authObj = ApiAuth::checkUserToken(APP_TOKEN);
    
        if($authObj == null){
            return \TraitsFunc::ErrorMessage("Invalid Process, Please try again later", 400);
        }

        $authObj['auth']->auth_expire = 0;
        $authObj['auth']->save();

        \Auth::logout();
        session()->flush();

        $statusObj['status'] = new \stdClass();
        $statusObj['status'] = \TraitsFunc::SuccessResponse("Logout Success, You can now login again!");
		return \Response::json((object) $statusObj);
	}

    public function register() {

        if (!isset($_SERVER['HTTP_DEVICEKEY'])) {
            return \TraitsFunc::ErrorMessage("Please check device key", 400);
        }

        $dateTime = DATE_TIME;
        $input = \Input::all();

        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'phone' => 'required',
            'university_id' => 'required',
            'faculty_id' => 'required',
            'gender' => 'required',
            'year'  => 'required|gt:0',
        ];

        $message = [
            'name.required' => "Sorry Name Required",
            'email.required' => "Sorry Email Required",
            'email.email' => "Sorry Email Must Be Email Type",
            'password.required' => "Sorry Password Required",
            'phone.required' => "Sorry Phone Required",
            'university_id.required' => "Sorry University Required",
            'faculty_id.required' => "Sorry Faculty Required",
            'gender.required' => "Sorry Gender Required",
            'year.required' => "Sorry Year Required",
        ];

        $validate = \Validator::make($input, $rules, $message);

        if($validate->fails()){
            return \TraitsFunc::ErrorMessage($validate->messages()->first(), 400);
        }
            
        $checkPhone = User::checkUserByPhone($input['phone']);
        if($checkPhone != null) {
            return \TraitsFunc::ErrorMessage("This phone exist, Please choose another phone!", 400);
        }

        $checkEmail = User::checkUserByEmail($input['email']);
        if($checkEmail != null) {
            return \TraitsFunc::ErrorMessage("This email exist, Please choose another email!", 400);
        }

        $universityObj = University::getOne($input['university_id']);
        if ($universityObj == null) {
            return \TraitsFunc::ErrorMessage("This University not found", 400);
        }

        $facultyObj = Faculty::getOne($input['faculty_id']);
        if ($facultyObj == null) {
            return \TraitsFunc::ErrorMessage("This Faculty not found", 400);
        }

        if ($input['year'] > 0 && $input['year'] > $facultyObj->number_of_years) {
            return \TraitsFunc::ErrorMessage("Year Must Be Less than or equal to ".$facultyObj->number_of_years, 400);
        }

        $userObj = new User();        
        $userObj->email = isset($input['email']) ? $input['email'] : null;            
        $userObj->name = $input['name'];
        $userObj->is_active = 1;
        $userObj->password = isset($input['password']) ? Hash::make($input['password']) : '';
        $userObj->last_login = $dateTime;
        $userObj->created_at = $dateTime;
        $userObj->save();

        $userObj->created_by = $userObj->id;
        $userObj->save();

        $name = explode(' ', $input['name'], 2);

        $profileObj = new Profile();
        $profileObj->user_id = $userObj->id;
        $profileObj->first_name = $name[0];
        $profileObj->last_name = isset($name[1]) ? $name[1]  : '';
        $profileObj->display_name = $input['name'];
        $profileObj->phone = $input['phone'];
        $profileObj->group_id = 3;
        $profileObj->gender = $input['gender'];
        $profileObj->university_id = $input['university_id'];
        $profileObj->faculty_id = $input['faculty_id'];
        $profileObj->year = $input['year'];
        $profileObj->save();

        $apiKeyId = ApiKeys::checkApiKey()->id;

        ApiAuth::logoutOtherSessions($userObj->id, $apiKeyId);

        $ApiAuth = new ApiAuth();
        $ApiAuth->auth_token = md5(uniqid(rand(), true));
        $ApiAuth->auth_expire = 1;
        $ApiAuth->api_id = $apiKeyId;
        $ApiAuth->user_id = $userObj->id;
        $ApiAuth->created_at = $dateTime;
        $ApiAuth->save();

        $token_value = $ApiAuth->auth_token;

        //Check token
        $checkAuth = ApiAuth::checkUserToken($token_value);
    
        if($checkAuth == null){
            \Auth::logout();
            session()->flush();

            return \TraitsFunc::ErrorMessage("Error, Please contact with admin to recheck user data!", 401);
        }

        Devices::applyNewDevice($ApiAuth->id);

        $dataObj = new \stdClass();
        $dataObj->email = $userObj->email;
        $dataObj->phone = $profileObj->phone;
        $dataObj->first_name = $profileObj->first_name;
        $dataObj->last_name = $profileObj->last_name;
        $dataObj->full_name = $profileObj->display_name;
        $dataObj->last_login = $userObj->last_login;
        $dataObj->token = $token_value;
        $dataObj->auth_id = $ApiAuth->id;
        $dataObj->university_id = (int) $profileObj->university_id;
        $dataObj->university = $profileObj->University ? $profileObj->University->title : null;
        $dataObj->faculty_id = (int) $profileObj->city_id;
        $dataObj->faculty = $profileObj->Faculty ? $profileObj->Faculty->title : null;
        $dataObj->year = $profileObj->year;
        
        $statusObj['data'] = new \stdClass();
        $statusObj['data'] = $dataObj;
        $statusObj['status'] = \TraitsFunc::SuccessResponse("Register Success");
		return \Response::json((object) $statusObj);
    }

    public function doResetPassword() {
        $input = \Input::all();

        $rules = [
            'password' => 'required|confirmed',
            'password_confirmation' => 'required'
        ];

        $message = [
            'password.required' => "Sorry Password Required",
            'password_confirmation.required' => "Sorry Password Confirmation Required",
        ];

        $validate = \Validator::make($input, $rules, $message);

        if($validate->fails()){
            return \TraitsFunc::ErrorMessage($validate->messages()->first(), 400);
        }

        $password = $input['password'];
        if(isset($input['email']) && $input['email'] != null){
            $userObj = Users::NotDeleted()->where('email', $input['email'])->first();
        }

        if ($userObj == null) {
            $statusObj['status'] = \TraitsFunc::SuccessResponse("Sorry please check your code again or it could expired");
            return \Response::json((object) $statusObj);
        }

        $userObj->password = Hash::make($password);
        $userObj->save();

        $statusObj['status'] = \TraitsFunc::SuccessResponse("Reset Password Success");
        return \Response::json((object) $statusObj);
    }
}
