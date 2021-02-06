<?php namespace App\Models;

use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Model;
use App\Models\Profile;

class User extends Model{

    use \TraitsFunc;

    protected $table = 'users';
    protected $primaryKey = 'id';
    public $timestamps = false;

    function Profile(){
        return $this->hasOne('App\Models\Profile', 'user_id');
    }

    function Courses(){
        return $this->hasMany('App\Models\Course', 'instructor_id');
    }

    function InstructorRate(){
        return $this->hasMany('App\Models\InstructorRate', 'instructor_id');
    }

    function StudentCourse(){
        return $this->hasMany('App\Models\StudentCourse', 'instructor_id','id');
    }

    function StudentCourse2(){
        return $this->hasMany('App\Models\StudentCourse', 'student_id','id');
    }

    function StudentViewDuration(){
        return $this->hasMany('App\Models\StudentVideoDuration', 'student_id','id');
    }

    static function getPhotoPath($id, $photo) {
        return \ImagesHelper::GetImagePath('users', $id, $photo);
    }

    static function getLogoPath($id, $photo) {
        return \ImagesHelper::GetImagePath('logos', $id, $photo);
    }

    static function usersList() {
        $input = \Input::all();

        $source = self::orderBy('last_login', 'desc')->with('Profile')
            ->whereHas('Profile', function($queryProfile) use ($input) {
                if (isset($input['name']) && !empty($input['name'])) {
                    $queryProfile->where('first_name', 'LIKE', '%' . $input['name'] . '%')
                        ->orWhere('last_name', 'LIKE', '%' . $input['name'] . '%');
                }
                if (isset($input['group_id']) && $input['group_id'] != 0) {
                    $queryProfile->where('group_id', $input['group_id']);
                }
                if (isset($input['phone']) && $input['phone'] != 0) {
                    $queryProfile->where('phone', $input['phone']);
                }
            });

        if (isset($input['id']) && !empty($input['id'])) {
            $source->where('id',$input['id']);
        }

        if (isset($input['email']) && !empty($input['email'])) {
            $source->where('email', 'LIKE', '%' . $input['email'] . '%');
        }

        if (isset($input['course_id']) && !empty($input['course_id'])) {
            if (isset($input['group_id']) && $input['group_id'] != 0) {
                if($input['group_id'] == 3){ // Student
                    $source->whereHas('StudentCourse2',function($whereHas) use ($input) {
                        $whereHas->NotDeleted()->where('status',1)->where('course_id',$input['course_id']);
                    });
                }elseif($input['group_id'] == 2){ // Instructor
                    $source->whereHas('StudentCourse',function($whereHas) use ($input){
                        $whereHas->NotDeleted()->where('status',1)->where('course_id',$input['course_id']);
                    });
                }
            }else{
                $source->where(function($whereQuery) use ($input) {
                    $whereQuery->whereHas('StudentCourse',function($whereHas2) use ($input) {
                        $whereHas2->NotDeleted()->where('status',1)->where('course_id',$input['course_id']);
                    })->orWhereHas('StudentCourse2',function($whereHas) use ($input) {
                        $whereHas->NotDeleted()->where('status',1)->where('course_id',$input['course_id']);
                    });
                });
            }
            $source->with(['StudentViewDuration'=>function($withQuery) use ($input) {
                $withQuery->where('course_id',$input['course_id']);
            }]);
            
        }

        return self::generateObj($source);
    }

    static function getUsersByType($user_type,$active=null){
        if($active == true){
            return self::NotDeleted()->with('Profile')->whereHas('Profile',function($queryProfile) use ($user_type){
                $queryProfile->where('group_id',$user_type);
            })->orderBy('id','DESC')->get();
        }else{
            return self::NotDeleted()->where('is_active',1)->with('Profile')->whereHas('Profile',function($queryProfile) use ($user_type){
                $queryProfile->where('group_id',$user_type);
            })->orderBy('id','DESC')->get();
        }
        
    }

    static function getInstructorStudents($ids){
        return self::NotDeleted()->where('is_active',1)->whereIn('id',$ids)->with('Profile')->whereHas('Profile',function($queryProfile){
            $queryProfile->where('group_id',3);
        })->orderBy('id','DESC')->get();
    }

    static function generateObj($source){
        $sourceArr = $source->paginate(PAGINATION);

        $list = [];
        foreach($sourceArr as $key => $value) {
            $list[$key] = new \stdClass();
            $list[$key] = self::getData($value,'none');
        }

        $data['groups'] = Group::getList();
        $data['pagination'] = \Helper::GeneratePagination($sourceArr);
        $data['data'] = $list;

        return $data;
    }

    static function getDuration($duration){
        $result = '';
        if($duration > 3600){
            $hours = round(floor($duration / 3600));
            $minutes = round(floor(($duration % 3600) / 60));
            $result = $hours.' Hr '.$minutes.' Min';
        }elseif($duration > 60){
            $minutes = round(floor($duration / 60));
            $seconds = round($duration % 60);
            $result = $minutes.' Min '.$seconds.' Sec';
        }elseif($duration > 0 && $duration < 60){
            $result = round($duration).' Sec';
        }
        return $result;
    }

    static function selectImage($source){
        
        if($source->Profile->image != null){
            return self::getPhotoPath($source->id, $source->Profile->image);
        }else{
            if($source->facebook_img != null){
                return $source->facebook_img;
            }

            if($source->google_img != null){
                return $source->google_img;
            }

            if($source->google_img==null && $source->facebook_img ==null){
                return asset('/assets/images/avatar.png');
            }
        }
    }

    static function getTopInstructors($count){
        $source = StudentCourse::NotDeleted()->where('status',1)->groupBy('instructor_id')->selectRaw(\DB::raw('count(*) as student_course_count, instructor_id'))->orderBy('student_course_count','DESC')->take($count)->get();
        return self::generateUserDataBasedOnCount($source,'instructor');
    }

    static function getTopStudents($count){
        $source = StudentCourse::NotDeleted()->groupBy('student_id')->selectRaw(\DB::raw('count(*) as student_course2_count, student_id'))->orderBy('student_course2_count','DESC')->take($count)->get();
        return self::generateUserDataBasedOnCount($source,'student');
    }

    static function generateUserDataBasedOnCount($source,$type=null){
        $list = [];
        foreach($source as $key => $value) {
            $list[$key] = new \stdClass();
            $userObj = self::getOne($value->{$type.'_id'});
            $list[$key] = self::getData2($userObj,$type,$value->student_course2_count);
        }
        return (object) $list;
    }

    static function generateObj2($source,$type=null){
        $list = [];
        foreach($source as $key => $value) {
            $list[$key] = new \stdClass();
            $list[$key] = self::getData2($value,$type);
        }
        return (object) $list;
    }

    static function getData2($source,$type,$count=null) {
        $data = new  \stdClass();
        $data->id = $source->id;
        $data->name = $source->Profile != null ? ucwords($source->Profile->display_name) : '';
        $data->first_name = $source->Profile != null ? $source->Profile->first_name : '';
        $data->last_name = $source->Profile != null ? $source->Profile->last_name : '';
        $data->image = self::selectImage($source);
        $data->group = $source->Profile->Group != null ? $source->Profile->Group->title : '';
        $data->gender = $source->Profile != null ? $source->Profile->gender : '';
        $data->group_id = $source->Profile->group_id;
        $data->phone = $source->Profile != null ? $source->Profile->phone: '';
        $data->address = $source->Profile != null ? $source->Profile->address: '';
        $data->mac_address = $source->Profile != null ? $source->Profile->mac_address: '';
        $data->email = $source->email;
        $data->last_login = \Helper::formatDateForDisplay($source->last_login, true);
        $data->extra_rules = unserialize($source->Profile->extra_rules) != null || unserialize($source->Profile->extra_rules) != '' ? unserialize($source->Profile->extra_rules) : [];
        $data->active = $source->is_active == 1 ? "Yes" : "No";
        $data->is_active = $source->is_active;
        $data->deleted_by = $source->deleted_by;

        if($type == 'instructor'){
            $data->studentCount = $count;
            $data->courseCount = $source->Courses()->NotDeleted()->whereIn('status',[3,4])->count();
        }else{
            $data->courseCount = $count;
        }

        return $data;
    }

    static function getData($source,$viewDets=null) {
        $data = new  \stdClass();
        $data->id = $source->id;
        $data->name = $source->Profile != null ? ucwords($source->Profile->display_name) : '';
        $data->image = self::selectImage($source);
        $data->group_id = $source->Profile->group_id;
        $data->email = $source->email;
        $data->last_login = \Helper::formatDateForDisplay($source->last_login, true);
        $data->extra_rules = unserialize($source->Profile->extra_rules) != null || unserialize($source->Profile->extra_rules) != '' ? unserialize($source->Profile->extra_rules) : [];
        $data->active = $source->is_active == 1 ? "Yes" : "No";
        $data->is_active = $source->is_active;
        $data->logo =  $source->Profile->logo != null ? self::getLogoPath($source->id, $source->Profile->logo) : '';
        $data->deleted_by = $source->deleted_by;
        if($viewDets != null){
            $data->first_name = $source->Profile != null ? $source->Profile->first_name : '';
            $data->last_name = $source->Profile != null ? $source->Profile->last_name : '';
            $data->group = $source->Profile->Group != null ? $source->Profile->Group->title : '';
            $data->gender = $source->Profile != null ? $source->Profile->gender : '';
            $data->phone = $source->Profile != null ? $source->Profile->phone: '';
            $data->address = $source->Profile != null ? $source->Profile->address: '';
            $data->mac_address = $source->Profile != null ? $source->Profile->mac_address: '';
            $data->show_student_id = $source->Profile->show_student_id;
            if($data->group_id == 2){
                $data->rateCount = $source->InstructorRate != null ? $source->InstructorRate()->NotDeleted()->count() :0;
                $data->studentCount = $source->StudentCourse != null ? $source->StudentCourse()->NotDeleted()->groupBy('student_id')->count() :0;
                $data->courseCount = $source->StudentCourse != null ? $source->StudentCourse()->NotDeleted()->groupBy('course_id')->count() :0;
                $data->rateSum = $source->InstructorRate != null ? $source->InstructorRate()->NotDeleted()->sum('rate') :0;
                $data->totalRate = $data->rateCount!= 0 ? round(($data->rateSum / ( 5 * $data->rateCount)) * 5 ,1) : 0;
            }
            if($source->StudentViewDuration){
                $data->viewDuration = $source->StudentViewDuration->sum('see_duration') != 0 ? self::getDuration($source->StudentViewDuration->sum('see_duration')) : 0;
            }
        }
        return $data;
    }

    static function getOne($id) {
        return self::NotDeleted()
            ->with('Profile')
            ->where('id', $id)
            ->first();
    }

    static function getOneD($id) {
        return self::with('Profile')
            ->where('id', $id)
            ->first();
    }

    static function getLoginUser($email){
        $userObj = self::NotDeleted()
            ->with('Profile')
            ->where('email', $email)
            ->where('is_active', 1)
            ->first();

        if($userObj == null || $userObj->Profile->group_id == 3) {
            return false;
        }

        return $userObj;
    }

    static function checkUserPermissions($userObj) {
        $endPermissionUser = [];
        $endPermissionGroup = [];

        $groupObj = $userObj->Profile->Group;
        $profileObj = $userObj->Profile;
        $groupPermissions = $groupObj != null ? $groupObj->permissions : null;

        $userPermissionValue = unserialize($profileObj->extra_rules);
        $groupPermissionValue = unserialize($groupPermissions);

        if($userPermissionValue != false){
            $endPermissionUser = $userPermissionValue;
        }

        if($groupPermissionValue != false){
            $endPermissionGroup = $groupPermissionValue;
        }

        $permissions = (array) array_unique(array_merge($endPermissionUser, $endPermissionGroup));

        return $permissions;
    }

    static function userPermission(array $rule){

        if(IS_ADMIN == false) {
            return count(array_intersect($rule, PERMISSIONS)) > 0;
        }

        return true;
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

    static function createOneUser($group_id=null){
        $input = \Input::all();

        $userObj = new User();
        $userObj->email = $input['email'];
        $userObj->name = $input['first_name'].' '.$input['last_name'];
        $userObj->is_active = isset($input['active']) ? 1 : 0;
        $userObj->password = \Hash::make($input['password']);
        $userObj->save();

        self::saveProfile($userObj,$group_id);
        return $userObj->id;
    }

    static function saveProfile($userObj,$group_id=null) {
        $input = \Input::all();

        $profileObj = $userObj->Profile;

        if($profileObj == null){
            $profileObj = new Profile();
        }
        if(isset($input['permissions'])){
            $profileObj->extra_rules = serialize($input['permissions']);
        }
        $profileObj->user_id = $userObj->id;
        $profileObj->first_name = $input['first_name'];
        $profileObj->last_name = $input['last_name'];
        $profileObj->phone = $input['phone'];
        $profileObj->address = isset($input['address']) && !empty($input['address']) ? $input['address'] : '' ;
        $profileObj->gender = $input['gender'];
        $profileObj->display_name = $input['first_name'].' '.$input['last_name'];
        $profileObj->group_id = $group_id != null ? $group_id : $input['group_id'];
        $profileObj->save();
    }

}
