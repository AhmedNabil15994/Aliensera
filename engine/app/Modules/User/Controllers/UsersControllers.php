<?php namespace App\Http\Controllers;


use App\Models\Profile;
use App\Models\User;
use App\Models\University;
use App\Models\Faculty;
use App\Models\ApiAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;

class UsersControllers extends Controller {

    use \TraitsFunc;

    public function getUserData() {
        $userObj = User::getOne(USER_ID);
        if ($userObj == null) {
            return \TraitsFunc::ErrorMessage("This User not found", 400);
        }
        $profileObj = $userObj->Profile;
        $dataObj = new \stdClass();
        $dataObj->username = $profileObj->username;
        $dataObj->user_id = USER_ID;
        $dataObj->name = $profileObj->display_name;
        $dataObj->phone = $profileObj->phone;
        $dataObj->email = $userObj->email;
        $dataObj->university_id = $profileObj->university_id != null ? $profileObj->university_id : '';
        $dataObj->university = $profileObj->University != null ? $profileObj->University->title : '';
        $dataObj->faculty_id = $profileObj->faculty_id != null ? $profileObj->faculty_id : '';
        $dataObj->faculty = $profileObj->Faculty != null ? $profileObj->Faculty->title : '';
        $dataObj->year = $profileObj->year != null ? $profileObj->year : '';
        $dataObj->gender = $profileObj->gender != null ? $profileObj->gender : '';
        $dataObj->image = User::getPhotoPath(USER_ID,$profileObj->image);

        $statusObj['data'] = $dataObj;
        $statusObj['status'] = \TraitsFunc::SuccessResponse();
        return \Response::json((object) $statusObj);
    }

    public function updateUserData(Request $request){
        $input = \Input::all();
        $userObj = User::getOne(USER_ID);
        if ($userObj == null) {
            return \TraitsFunc::ErrorMessage("This User not found", 400);
        }
        
        $profileObj = Profile::getProfile();

        if(isset($input['name']) && !empty($input['name'])){
            $name = explode(' ', $input['name'], 2);
            $profileObj->first_name = $name[0];
            $profileObj->last_name = isset($name[1]) ? $name[1]  : '';
            $profileObj->display_name = $input['name'];
            $profileObj->save();

            $userObj->name = $input['name'];
            $userObj->save();
        }

        if(isset($input['phone']) && !empty($input['phone'])){
            $checkPhone = User::checkUserByPhone($input['phone'],USER_ID);
            if($checkPhone != null) {
                return \TraitsFunc::ErrorMessage("This phone exist, Please choose another phone!", 400);
            }
            $profileObj->phone = $input['phone'];
            $profileObj->save();
        }

        if(isset($input['username']) && !empty($input['username'])){
            $checkUsername = User::getUserByUsername($input['username'],USER_ID);
            if($checkUsername != null) {
                return \TraitsFunc::ErrorMessage("This username exist, Please choose another username!", 400);
            }
            $profileObj->username = $input['username'];
            $profileObj->save();
        }


        if(isset($input['password']) && !empty($input['password'])){
            $userObj->password = Hash::make($input['password']);
            $userObj->save();
        }

        if(isset($input['gender']) && !empty($input['gender'])){
            $profileObj->gender = $input['gender'];
            $profileObj->save();
        }

        if(isset($input['address']) && !empty($input['address'])){
            $profileObj->address = $input['address'];
            $profileObj->save();
        }

        if(isset($input['university_id']) && !empty($input['university_id'])){
            $universityObj = University::getOne($input['university_id']);
            if ($universityObj == null) {
                return \TraitsFunc::ErrorMessage("This University not found", 400);
            }
            $profileObj->university_id = $input['university_id'];
            $profileObj->save();
        }
            
        if(isset($input['faculty_id']) && !empty($input['faculty_id'])){
            $facultyObj = Faculty::getOne($input['faculty_id']);
            if ($facultyObj == null) {
                return \TraitsFunc::ErrorMessage("This Faculty not found", 400);
            }

            $profileObj->faculty_id = $input['faculty_id'];
            $profileObj->save();
        }
            
        if(isset($input['year']) && !empty($input['year']) ){

            if($input['year'] == 0){
                return \TraitsFunc::ErrorMessage("Year Must Be Greater than 0", 400);
            }

            if ($input['year'] > 0 && isset($facultyObj) && $input['year'] > $facultyObj->number_of_years) {
                return \TraitsFunc::ErrorMessage("Year Must Be Less than or equal to ".$facultyObj->number_of_years, 400);
            }

            $profileObj->year = $input['year'];
            $profileObj->save();
        }    

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $fileName = \ImagesHelper::UploadImage('users', $image, USER_ID);
            if($image == false || $fileName == false){
                return \TraitsFunc::ErrorMessage("Upload Image Failed !!", 400);
            }            
            $profileObj->image = $fileName;
            $profileObj->save();
        }

        $statusObj['status'] = \TraitsFunc::SuccessResponse('Data Updated Successfully');
        return \Response::json((object) $statusObj);
    }

    public function getInstructors(){
        $input = \Input::all();
        $statusObj = User::getInstructors();
        $statusObj['status'] = \TraitsFunc::SuccessResponse();
        return \Response::json((object) $statusObj);
    }

    public function getOneInstructor($id){
        $input = \Input::all();
        $userObj = User::getOneInstructor($id);
        if($userObj == null){
            return \TraitsFunc::ErrorMessage("This Instructor not found", 400);
        }
        $statusObj['data'] = User::getInstructorData($userObj);
        $statusObj['status'] = \TraitsFunc::SuccessResponse();
        return \Response::json((object) $statusObj);
    }

    public function deactivate(){
        $userObj = User::getOne(USER_ID);
        if ($userObj == null) {
            return \TraitsFunc::ErrorMessage("This User not found", 400);
        }
        
        $userObj->is_active = 0 ;
        $userObj->save();

        $authObj = ApiAuth::checkUserToken(APP_TOKEN);
        if($authObj == null){
            return \TraitsFunc::ErrorMessage("Invalid Process, Please try again later", 400);
        }

        $authObj['auth']->auth_expire = 0;
        $authObj['auth']->save();

        \Auth::logout();
        session()->flush();

        $statusObj['data'] = $userObj;
        $statusObj['status'] = \TraitsFunc::SuccessResponse();
        return \Response::json((object) $statusObj);
    }
}
