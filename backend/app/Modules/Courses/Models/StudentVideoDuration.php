<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentVideoDuration extends Model{

    use \TraitsFunc;

    protected $table = 'student_video_duration';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function Lesson(){
        return $this->belongsTo('App\Models\Lesson','lesson_id','id');
    }

    public function Course(){
        return $this->belongsTo('App\Models\Course','course_id','id');
    }

    public function Student(){
        return $this->hasMany('App\Models\User','student_id','id');
    }

    static function getOne($id){
        $source = self::NotDeleted()
            ->where('id', $id);

        return $source->first();
    }

    static function getTopSeenCourses($count){
        $source = self::NotDeleted();
        if(IS_ADMIN == false){
            $source->whereHas('Course',function($courseQuery){
                $courseQuery->where('instructor_id',USER_ID);
            });
        }
        $source = $source->groupBy('course_id')->orderByRaw('SUM(see_duration) DESC')->take($count)->get();
        return self::generateObj($source);
    }

    static function generateObj($source){
        $list = [];
        foreach($source as $key => $value) {
            $list[$key] = new \stdClass();
            $list[$key] = self::getData($value);
        }
        return $list;
    }
    
    static function getData($source) {
        $data = new  \stdClass();
        $data->id = $source->id;
        $data->course = $source->Course != null ? Course::getData($source->Course) : '';
        $data->count = self::where('course_id',$source->course_id)->groupBy('course_id')->count('student_id');
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

    static function getAllDuration($type = null){
       $source = self::NotDeleted()->whereHas('Course',function($courseQuery){
            $courseQuery->whereIn('status',[3,5]);
        });
        if($type == null){
            if(IS_ADMIN == false){
                $source->whereHas('Course',function($courseQuery){
                    $courseQuery->where('instructor_id',USER_ID);
                });
            }
        }
        return self::getDuration($source->sum('see_duration'));
    }

}
