<?php namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use App\Models\Group;
use App\Models\Course;
use App\Models\CourseFeedback;
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
            'email' => 'required|email',
        ];

        $message = [
            'first_name.required' => "Sorry first_name Required",
            'last_name.required' => "Sorry last_name Required",
            'phone.required' => "Sorry phone Required",
            'email.required' => "Sorry email Required",
            'email.format' => "Please check email format",
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    public function index() {
        $usersList = User::usersList();
        return view('Users.Views.index')
            ->with('data', (Object) $usersList);
    }

    public function edit($id) {
        $id = (int) $id;

        $userObj = User::NotDeleted()
            ->with('Profile')
            ->whereHas('Profile', function() {})
            ->find($id);

        if($userObj == null) {
            return Redirect('404');
        }

        if (GROUP_ID != 1 && $userObj->group_id == 1) {
            \Session::flash('error', "Sorry you cant edit this user");
            return redirect()->back();
        }

        $profileObj = $userObj->Profile;

        $dataObj = new \stdClass();
        $dataObj->id = $userObj->id;
        $dataObj->email = $userObj->email;
        $dataObj->active = $userObj->is_active;
        $dataObj->phone = $profileObj->phone;
        $dataObj->address = $profileObj->address;
        $dataObj->first_name = $profileObj->first_name;
        $dataObj->display_name = $profileObj->display_name;
        $dataObj->last_name = $profileObj->last_name;
        $dataObj->group_id = $profileObj->group_id;
        $dataObj->extra_rules = unserialize($profileObj->extra_rules) != null || unserialize($profileObj->extra_rules) != '' ? unserialize($profileObj->extra_rules) : [];
        
        $data['groups'] = Group::getList();
        $data['permissions'] = array_diff(array_unique(config('permissions')), ['general','doLogin','login','logout']);
        $data['data'] = $dataObj;
        return view('Users.Views.edit')->with('data', (object) $data);
    }

    public function view($id) {
        $id = (int) $id;

        $userObj = User::NotDeleted()
            ->with('Profile')
            ->whereHas('Profile', function() {})
            ->find($id);

        if($userObj == null) {
            return Redirect('404');
        }

        if (GROUP_ID != 1 && $userObj->group_id == 1) {
            \Session::flash('error', "Sorry you cant edit this user");
            return redirect()->back();
        }

        $profileObj = $userObj->Profile;

        $dataObj = new \stdClass();
        $dataObj->id = $userObj->id;
        $dataObj->email = $userObj->email;
        $dataObj->image = $profileObj->image != null ? User::getPhotoPath($userObj->id, $profileObj->image) : '';
        $dataObj->active = $userObj->is_active;
        $dataObj->phone = $profileObj->phone;
        $dataObj->address = $profileObj->address;
        $dataObj->first_name = $profileObj->first_name;
        $dataObj->display_name = $profileObj->display_name;
        $dataObj->last_name = $profileObj->last_name;
        $dataObj->group_id = $profileObj->group_id;
        $dataObj->gender = $profileObj->gender == 1 ? 'Male' : 'Female';
        $dataObj->group = $profileObj->Group->title;
        $dataObj->extra_rules = unserialize($profileObj->extra_rules) != null || unserialize($profileObj->extra_rules) != '' ? unserialize($profileObj->extra_rules) : [];
        
        $data['data'] = $dataObj;
        $data['comments'] = VideoComment::dataList(null,null,null,$userObj->id);
        if($profileObj->group_id == 2){
            $data['courses'] = Course::dataList($userObj->id)['data'];
        }elseif($profileObj->group_id == 3){
            $data['courses'] = Course::dataList()['data'];
            $data['reviews'] = CourseFeedback::dataList(null,$userObj->id);
        }
        return view('Users.Views.view')->with('data', (object) $data);
    }

    public function update($id) {
        $id = (int) $id;

        $userObj = User::getOne($id);

        if($userObj == null) {
            return Redirect('404');
        }

        if (GROUP_ID != 1 && $userObj->group_id == 1) {
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
        $userObj->group_id = $input['group_id'];

        if (isset($input['password'])) {
            $userObj->password = \Hash::make($input['password']);
        }

        if(isset($input['permissions'])){
            $userObj->extra_rules = serialize($input['permissions']);
        }

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
            return redirect()->back();
        }

        $input = \Input::all();

        if (!isset($input['password'])) {
            \Session::flash('error', "Sorry Password Required");
            return \Redirect::back()->withInput();
        }

        if(User::checkUserByPhone($input['phone']) != null){
            \Session::flash('error', "Sorry This phone exist");
            return redirect()->back();
        }

        if(User::checkUserByEmail($input['email']) != null){
            \Session::flash('error', "Sorry This email exist");
            return redirect()->back();
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


}
