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

    function InstructorRate(){
        return $this->hasMany('App\Models\InstructorRate', 'instructor_id');
    }

    function StudentCourse(){
        return $this->hasMany('App\Models\StudentCourse', 'instructor_id');
    }

    function Course(){
        return $this->hasMany('App\Models\Course', 'instructor_id');
    }

    static function getPhotoPath($id, $photo) {
        return \ImagesHelper::GetImagePath('users', $id, $photo);
    }

    static function generateUsername($slug, $id) {
        $userObj = self::NotDeleted()
            ->with('Profile')
            ->find($id);

        if ($userObj == null) {
            return false;
        }

        $encode = rand('10000', '1000000');
        $str = $slug . '-' . $encode;

        $userCheck = Profile::where('username', $str)->first();
        if ($userCheck != null) {
            return self::generateUsername($slug, $id);
        }

        return $str;
    }

    static function getOne($id){
        return self::NotDeleted()
            ->with('Profile')
            ->where('id', $id)
            ->first();
    }

    static function getUserByUsername($username, $notId=false){
        $dataObj = self::NotDeleted()
            ->whereHas('Profile', function($profileQuery) use ($username){
                $profileQuery->where('username',$username);
            });

        if ($notId != false) {
            $dataObj->whereNotIn('id', [$notId]);
        }    

        return $dataObj->first();
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

    static function getLoginUserBySocialType($type,$id){
        $userObj = self::NotDeleted()
            ->with('Profile')
            ->where('is_active', 1)
            ->where(function($whereQuery) use ($type,$id){
                if($type == 1){
                    $whereQuery->where('facebook_id',$id);
                }elseif($type == 2){
                    $whereQuery->where('google_id',$id);
                }elseif($type == 3){
                    $whereQuery->where('twitter_id',$id);
                }
            })->first();

        if($userObj == null || $userObj->Profile->group_id != 3) {
            return false;
        }
        return $userObj;
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
        }
    }

    static function getData($source) {
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
        return $data;
    }

    static function getOneByType($user_type,$id){
        return self::NotDeleted()->where('is_active',1)->with('Profile')->whereHas('Profile',function($queryProfile) use ($user_type){
            $queryProfile->where('group_id',$user_type);
        })->where('id',$id)->orderBy('id','DESC')->first();
    }

    static function getInstructors(){
        $source = self::NotDeleted()->where('is_active',1)->with('Profile')->whereHas('Profile',function($queryProfile){
            $queryProfile->where('group_id',2);
        });
        return self::generateObj($source);
    }

    static function getOneInstructor($id){
        $source = self::NotDeleted()->where('id',$id)->where('is_active',1)->with('Profile')->whereHas('Profile',function($queryProfile){
            $queryProfile->where('group_id',2);
        })->first();
        return $source;
    }

    static function generateObj($sourceArr) {
        $list = [];
        $sourceArr = $sourceArr->paginate(PAGINATION);
        foreach ($sourceArr as $key => $value) {
            $list[$key] = new \stdClass();
            $list[$key] = self::getInstructorData($value);
        }
        $data['data'] = $list;
        $data['pagination'] = \Helper::GeneratePagination($sourceArr);
        return $data;
    }

    static function getInstructorData($source,$flag=null) {
        $data = new  \stdClass();
        $data->id = $source->id;
        $data->name = $source->Profile != null ? ucwords($source->Profile->display_name) : '';
        $data->first_name = $source->Profile != null ? $source->Profile->first_name : '';
        $data->last_name = $source->Profile != null ? $source->Profile->last_name : '';
        $data->image = self::getPhotoPath($source->id, $source->Profile->image);
        $data->gender = $source->Profile != null ? $source->Profile->gender : '';
        $data->phone = $source->Profile != null ? $source->Profile->phone: '';
        $data->address = $source->Profile != null ? $source->Profile->address: '';
        $data->email = $source->email;
        $data->last_login = \Helper::formatDateForDisplay($source->last_login, true);
        $data->myRate = InstructorRate::NotDeleted()->where('instructor_id',$source->id)->where('created_by',USER_ID)->first();
        if($source->Profile->group_id == 2){
            $data->rateCount = $source->InstructorRate != null ? $source->InstructorRate()->NotDeleted()->count() :0;
            $data->studentCount = $source->StudentCourse != null ? $source->StudentCourse()->NotDeleted()->where('status',1)->count() :0;
            $data->courseCount = $source->StudentCourse != null ? $source->StudentCourse()->NotDeleted()->where('status',1)->distinct()->count('course_id') :0;
            $data->rateSum = $source->InstructorRate != null ? $source->InstructorRate()->NotDeleted()->sum('rate') :0;
            $data->totalRate = $data->rateCount!= 0 ? round(($data->rateSum / ( 5 * $data->rateCount)) * 5 ,1) : 0;
            if($flag != 1){
                $data->topCourses = StudentCourse::getTopCourses(3,$source->id);
            }
        }
        return $data;
    }

}
