<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favourites extends Model {

    protected $table = 'favourites';
    protected $primary_key = 'id';
    public $timestamps = false;
    
    use \TraitsFunc;

    function User() {
        return self::hasOne('App\Models\User', 'id', 'student_id');
    }

    function Course() {
        return self::hasOne('App\Models\Course', 'id', 'course_id');
    }

    static function getOne($id){
        return self::NotDeleted()->whereHas('course',function($courseQuery){
            $courseQuer->whereIn('status',[3,5])->where(function($whereQuery){
                $whereQuery->where('valid_until',null)->orWhere('valid_until','>=',date('Y-m-d'));
            });
        })->where('course_id',$id)->where('student_id',USER_ID)->first();
    }

    static function favouriteList() {
        $source = self::NotDeleted()->whereHas('course',function($courseQuery){
            $courseQuer->whereIn('status',[3,5])->where(function($whereQuery){
                $whereQuery->where('valid_until',null)->orWhere('valid_until','>=',date('Y-m-d'));
            });
        })->where('student_id',USER_ID);
        
        return self::getFavouriteObj($source);
    }

    static function getFavouriteObj($sourceArr) {
        $list = [];
        $sourceArr = $sourceArr->get();
        foreach ($sourceArr as $key => $value) {
            $dataObj = self::getData($value);
            if($dataObj != null){
                $list[] = $dataObj;
            }
        }
        $data['data'] = $list;
        return $data;
    }

    static function getData($source) {
        return Course::getData($source->Course);
    }

    static function checkFav($course_id,$student_id){
        $source = self::NotDeleted()
                    ->where('student_id',$student_id)
                    ->where('course_id',$course_id)
                    ->first();
        if($source != null){
            return 1;
        }else{
            return 0;
        }
    }

 }
