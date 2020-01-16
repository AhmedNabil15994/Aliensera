<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model{

    use \TraitsFunc;

    protected $table = 'lessons';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function Course(){
        return $this->belongsTo('App\Models\Course','course_id','id');
    }
    public function Videos(){
        return $this->hasMany('App\Models\LessonVideo','lesson_id','id');
    }
    public function ActiveVideos(){
        return $this->Videos()->NotDeleted();
    }
    public function Questions(){
        return $this->hasMany('App\Models\LessonQuestion','id','lesson_id');
    }

    static function getOne($id){
        $source = self::NotDeleted()
            ->where('id', $id)
            ->where('status', 1);

        if(IS_ADMIN == false){
            $source->whereHas('Course',function($courseQuery) {
                $courseQuery->where('instructor_id',USER_ID);
            });
        }
        return $source->first();
    }

    static function dataList($course_id=null) {
        $input = \Input::all();

        $source = self::NotDeleted();

        if (isset($input['title']) && !empty($input['title'])) {
            $source->where('title', 'LIKE', '%' . $input['title'] . '%');
        } 

        if (isset($input['course_id']) && !empty($input['course_id'])) {
            $source->where('course_id', $input['course_id']);
        } 

        if (isset($course_id) && !empty($course_id) && $course_id != null) {
            $source->where('course_id', $course_id);
        } 

        if(IS_ADMIN == false){
            $source->whereHas('Course',function($courseQuery) {
                $courseQuery->where('instructor_id',USER_ID);
            });
        }

        return self::generateObj($source);
    }

    static function generateObj($source){
        $sourceArr = $source->paginate(PAGINATION);

        $list = [];
        foreach($sourceArr as $key => $value) {
            $list[$key] = new \stdClass();
            $list[$key] = self::getData($value);
        }

        $data['pagination'] = \Helper::GeneratePagination($sourceArr);
        $data['data'] = $list;

        return $data;
    }

    static function getData($source) {
        $data = new  \stdClass();
        $data->id = $source->id;
        $data->title = $source->title;
        $data->course_id = $source->course_id;
        $data->course = $source->Course->title;
        $data->description = $source->description;
        $data->videos = LessonVideo::dataList($source->id);
        $data->questions = LessonQuestion::dataList($source->id);
        $data->status = $source->status;
        return $data;
    }

}
