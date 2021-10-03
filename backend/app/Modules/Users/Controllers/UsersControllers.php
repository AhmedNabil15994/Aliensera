<?php namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use App\Models\Group;
use App\Models\Course;
use App\Models\CourseFeedback;
use App\Models\InstructorRate;
use App\Models\ApiAuth;
use App\Models\StudentCourse;
use App\Models\StudentScore;
use App\Models\StudentRequest;
use App\Models\VideoComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;

class UsersControllers extends Controller {

    use \TraitsFunc;

    protected function validateInputs(){
        $input = Input::all();

        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'gender' => 'required',
            'email' => 'required|email',
        ];

        $message = [
            'first_name.required' => "Sorry First Name Required",
            'last_name.required' => "Sorry Last Name Required",
            'phone.required' => "Sorry Phone Required",
            'gender.required' => "Sorry Gender Required",
            'email.required' => "Sorry Email Required",
            'email.format' => "Please Check Email format",
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    protected function validateProfile(){
        $input = Input::all();

        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'gender' => 'required',
        ];

        $message = [
            'first_name.required' => "Sorry First Name Required",
            'last_name.required' => "Sorry Last Name Required",
            'phone.required' => "Sorry Phone Required",
            'gender.required' => "Sorry Gender Required",
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    protected function validatePassword(){
        $input = Input::all();

        $rules = [
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6',
        ];

        $message = [
            'password.required' => "Sorry New Password Required",
            'password.min' => "Sorry New Password Must Be At Least 6 Characters",
            'password.confirmed' => "Sorry Passwords Don't Match",
            'password_confirmation.required' => "Sorry Password Confirmation Required",
            'password_confirmation.min' => "Sorry Password Confirmation Must Be At Least 6 Characters",
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    public function index() {
        $usersList = User::usersList(null);
        $usersList['courses'] = Course::NotDeleted()->orderBy('id','DESC')->get();
        return view('Users.Views.index')
            ->with('data', (Object) $usersList);
    }

    public function edit($id) {
        $id = (int) $id;

        $userObj = User::NotDeleted()
            ->with('Profile')
            ->whereHas('Profile', function() {})
            ->find($id);

        if($userObj == null || $id == 1) {
            return Redirect('404');
        }

        if (GROUP_ID != 1 && $userObj->group_id == 1) {
            \Session::flash('error', "Sorry you cant edit this user");
            return redirect()->back();
        }

        $data['groups'] = Group::getList();
        $data['permissions'] = array_diff(array_unique(config('permissions')), ['general','doLogin','login','logout']);
        $data['data'] = User::getData($userObj,true);
        return view('Users.Views.edit')->with('data', (object) $data);
    }

    public function view($id) {
        $id = (int) $id;

        if(IS_ADMIN == false){
            $instructorUser = StudentCourse::where('instructor_id',USER_ID)->where('student_id',$id)->first();
            if($instructorUser == null) {
                return Redirect('404');
            }
        }

        $userObj = User::NotDeleted()
            ->with('Profile')
            ->whereHas('Profile', function() {})
            ->find($id);

        if($userObj == null || $id == 1) {
            return Redirect('404');
        }

        if (GROUP_ID != 1 && $userObj->group_id == 1) {
            \Session::flash('error', "Sorry you cant edit this user");
            return redirect()->back();
        }

        $data['data'] = User::getData($userObj,true);
        $data['comments'] = VideoComment::dataList(null,null,null,$userObj->id);
        $profileObj = $userObj->Profile;

        if($profileObj->group_id == 2){
            $data['courses'] = Course::dataList($userObj->id,null,true,null,null,'users')['data'];
            $data['rates'] = InstructorRate::dataList($userObj->id);
        }elseif($profileObj->group_id == 3){
            $data['courses'] = Course::dataList(null,$userObj->id,true,null,null,'users')['data'];
            $data['reviews'] = CourseFeedback::dataList(null,$userObj->id);
            $data['rates'] = InstructorRate::dataList(null,$userObj->id);
            $data['scores'] = StudentScore::dataList($id);
            $data['requests'] = StudentRequest::dataList(null,$id)['data'];
            $data['sessions'] = ApiAuth::where('user_id',$id)->orderBy('id','desc')->get()->take(5);
        }
        return view('Users.Views.view')->with('data', (object) $data);
    }

    public function unsetDevices($id) {
        $id = (int) $id;

        if(IS_ADMIN == false){
            $instructorUser = StudentCourse::where('instructor_id',USER_ID)->where('student_id',$id)->first();
            if($instructorUser == null) {
                return Redirect('404');
            }
        }

        $userObj = User::NotDeleted()
            ->with('Profile')
            ->whereHas('Profile', function() {})
            ->find($id);

        if($userObj == null || $id == 1) {
            return Redirect('404');
        }

        if (GROUP_ID != 1 && $userObj->group_id == 1) {
            \Session::flash('error', "Sorry you cant edit this user");
            return redirect()->back();
        }

        $profileObj = $userObj->Profile;
        $profileObj->mac_address = null;
        $profileObj->save();

        \Session::flash('success', "Alert! Update Successfully");
        return \Redirect::back()->withInput();
    }

    public function update($id) {
        $id = (int) $id;

        $userObj = User::getOne($id);
        if($userObj == null  || $id == 1) {
            return Redirect('404');
        }

        $profileObj = $userObj->Profile;

        if (GROUP_ID != 1 && $profileObj->group_id == 1) {
            \Session::flash('error', "Sorry you cant update this user");
            return redirect()->back();
        }

        $validate = $this->validateInputs();
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }

        $input = \Input::all();

        if(User::checkUserByEmail($input['email'], $id) != null){
            \Session::flash('error', "Sorry This email exist");
            return redirect()->back();
        }

        $userObj->email = $input['email'];

        if (isset($input['password'])) {
            $userObj->password = \Hash::make($input['password']);
        }

        if(isset($input['permissions'])){
            $profileObj->extra_rules = serialize($input['permissions']);
            $profileObj->save();
        }else{
            $profileObj->extra_rules = null;
            $profileObj->save();
        }
        $userObj->name = $input['first_name'].' '.$input['last_name'];
        $userObj->is_active = isset($input['active']) ? 1 : 0;
        $userObj->save();

        User::saveProfile($userObj);
       
        \Session::flash('success', "Alert! Update Successfully");
        return \Redirect::back()->withInput();
    }

    public function add() {
        $groupsList = Group::getList();
        $data['groups'] = $groupsList;
        $data['permissions'] = array_diff(array_unique(config('permissions')), ['general','doLogin','login','logout']);
        return view('Users.Views.add')->with('data', (object) $data);
    }

    public function create() {

        $validate = $this->validateInputs();
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back()->withInput();
        }

        $input = \Input::all();

        if (!isset($input['password'])) {
            \Session::flash('error', "Sorry Password Required");
            return \Redirect::back()->withInput();
        }

        if(User::checkUserByPhone($input['phone']) != null){
            \Session::flash('error', "Sorry This phone exist");
            return redirect()->back()->withInput();
        }

        if(User::checkUserByEmail($input['email']) != null){
            \Session::flash('error', "Sorry This email exist");
            return redirect()->back()->withInput();
        }

        $userId = User::createOneUser();

        \Session::flash('success', "Alert! Create Successfully");
        return redirect()->to('users/edit/' . $userId);
    }

    public function delete($id) {
        $id = (int) $id;
        $userObj = User::getOne($id);
        return \Helper::globalDelete($userObj);
    }

    public function restore($id) {
        $id = (int) $id;
        $userObj = User::getOneD($id);
        return \Helper::globalRestore($userObj);
    }

    public function getProfile(){
        $usersList = User::getData(User::getOne(USER_ID),true);
        return view('Users.Views.profile')
            ->with('data', (Object) $usersList);
    }

    public function updateProfile(Request $request){
        $id = (int) USER_ID;

        $userObj = User::getOne($id);
        if($userObj == null  || $id == 1) {
            return Redirect('404');
        }

        $profileObj = $userObj->Profile;
        if($profileObj == null) {
            return Redirect('404');
        }
        
        $input = \Input::all();

        $validate = $this->validateProfile();
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back()->withInput();
        }

        $checkByPhone = User::checkUserByPhone($input['phone'],USER_ID);
        if($checkByPhone){
            \Session::flash('error', "Sorry Phone Used Before.");
            return redirect()->back()->withInput();
        }
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $fileName = \ImagesHelper::UploadImage('users', $image, USER_ID);
            if($image == false || $fileName == false){
                \Session::flash('error', "Upload Image Failed !!");
                return redirect()->back();
            }            
            $profileObj->image = $fileName;
            $profileObj->save();
        }

        if(isset($input['password'])){
            $validate = $this->validatePassword();
            if($validate->fails()){
                \Session::flash('error', $validate->messages()->first());
                return redirect()->back()->withInput();
            }
            $userObj->password = \Hash::make($input['password']);
            $userObj->save();
        }

        $display_name = $input['first_name'].' '.$input['last_name'];
        $userObj->name = $display_name;
        $userObj->save();

        $profileObj->first_name = $input['first_name'];
        $profileObj->last_name = $input['last_name'];
        $profileObj->display_name = $display_name;
        $profileObj->phone = $input['phone'];
        $profileObj->gender = $input['gender'];
        $profileObj->show_student_id = isset($input['show']) ? 1 : 0;
        $profileObj->address = $input['address'];
        $profileObj->save();

        if ($request->hasFile('logo')) {
            $files = $request->file('logo');
            $fileName = \ImagesHelper::UploadImage('logos', $files, $id);
            if($fileName == false){
                \Session::flash('error', "Upload images Failed");
                return \Redirect::back()->withInput();
            }

            $profileObj->logo = $fileName;
            $profileObj->save();
        }

        \Session::flash('success', "Alert! Update Successfully");
        return \Redirect::back()->withInput();
    }
}
