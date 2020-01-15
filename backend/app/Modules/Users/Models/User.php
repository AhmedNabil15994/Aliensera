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

    static function getPhotoPath($id, $photo) {
        return \ImagesHelper::GetImagePath('users', $id, $photo);
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

        if (isset($input['email']) && !empty($input['email'])) {
            $source->where('email', 'LIKE', '%' . $input['email'] . '%');
        }

        return self::generateObj($source);
    }

    static function getUsersByType($user_type){
        return self::NotDeleted()->where('is_active',1)->with('Profile')->whereHas('Profile',function($queryProfile) use ($user_type){
            $queryProfile->where('group_id',$user_type);
        })->orderBy('id','DESC')->get();
    }

    static function generateObj($source){
        $sourceArr = $source->paginate(PAGINATION);

        $list = [];
        foreach($sourceArr as $key => $value) {
            $list[$key] = new \stdClass();
            $list[$key] = self::getData($value);
        }

        $data['groups'] = Group::getList();
        $data['pagination'] = \Helper::GeneratePagination($sourceArr);
        $data['data'] = $list;

        return $data;
    }

    static function getData($source) {
        $data = new  \stdClass();
        $data->id = $source->id;
        $data->name = $source->Profile != null ? $source->Profile->display_name : '';
        $data->image = $source->Profile->image != null ? self::getPhotoPath($source->id, $source->image) : '';
        $data->group = $source->Profile->Group != null ? $source->Profile->Group->title : '';
        $data->group_id = $source->Profile->group_id;
        $data->phone = $source->Profile != null ? $source->Profile->phone: '';
        $data->email = $source->email;
        $data->last_login = \Helper::formatDateForDisplay($source->last_login, true);
        $data->active = $source->is_active == 1 ? "Yes" : "No";
        $data->is_active = $source->is_active;
        $data->deleted_by = $source->deleted_by;
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

    static function createOneUser(){
        $input = \Input::all();

        $userObj = new User();
        $userObj->email = $input['email'];
        $userObj->group_id = $input['group_id'];
        $userObj->is_active = isset($input['active']) ? 1 : 0;
        $userObj->password = \Hash::make($input['password']);
        if(isset($input['permissions'])){
            $userObj->extra_rules = serialize($input['permissions']);
        }
        $userObj->save();

        self::saveProfile($userObj);
        return $userObj->id;
    }

    static function saveProfile($userObj) {
        $input = \Input::all();

        $profileObj = $userObj->Profile;

        if($profileObj == null){
            $profileObj = new Profiles();
        }

        $profileObj->user_id = $userObj->id;
        $profileObj->first_name = $input['first_name'];
        $profileObj->last_name = $input['last_name'];
        $profileObj->phone = $input['phone'];
        $profileObj->display_name = $input['first_name'].' '.$input['last_name'];
        $profileObj->save();
    }

}
