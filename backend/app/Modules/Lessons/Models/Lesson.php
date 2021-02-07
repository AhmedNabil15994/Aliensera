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
            ->where('id', $id);
            
        if(IS_ADMIN == false){
            $source->whereHas('Course',function($courseQuery) {
                $courseQuery->where('instructor_id',USER_ID);
            });
        }
        return $source->first();
    }

    static function dataList($course_id=null,$paginate=false,$withDets=null) {
        $input = \Input::all();

        $source = self::NotDeleted();

        if (isset($input['title']) && !empty($input['title'])) {
            $source->where('title', 'LIKE', '%' . $input['title'] . '%');
        } 

        if (isset($input['course_id']) && !empty($input['course_id'])) {
            $source->where('course_id', $input['course_id']);
        } 

        if (isset($input['status']) && $input['status'] != null) {
            $source->where('status', $input['status']);
        } 

        if (isset($course_id) && !empty($course_id) && $course_id != null) {
            $source->where('course_id', $course_id);
        } 

        if(IS_ADMIN == false){
            $source->whereHas('Course',function($courseQuery) {
                $courseQuery->where('instructor_id',USER_ID);
            });
        }

        $source->orderBy('sort','ASC');
        return self::generateObj($source,$paginate,$withDets);
    }

    static function generateObj($source,$paginate,$withDets){
        if($paginate == true){
            $sourceArr = $source->get();
        }else{
            $sourceArr = $source->paginate(PAGINATION);
        }

        $list = [];
        foreach($sourceArr as $key => $value) {
            $list[$key] = new \stdClass();
            $list[$key] = self::getData($value,$withDets);
        }

        if($paginate != true){
            $data['pagination'] = \Helper::GeneratePagination($sourceArr);
        }
        $data['data'] = $list;

        return $data;
    }

    static function getData($source,$withDets=null) {
        $data = new  \stdClass();
        $data->id = $source->id;
        $data->title = $source->title;
        $data->course_id = $source->course_id;
        $data->questions_sort = $source->questions_sort;
        $data->quiz_duration = $source->quiz_duration;
        $data->pass_quiz = $source->pass_quiz;
        $data->course = $source->Course->title;
        $data->description = $source->description;
        $data->valid_until = $source->valid_until;
        $data->active_at = $source->active_at; 
        $data->status = $source->status;
        $data->sort = $source->sort;
        if($withDets != null){
            $data->studentScores = StudentScore::getByLesson($source->id);
            $data->videos = LessonVideo::dataList($source->id);
            $data->course_status = $source->Course->status;
            $data->questions = LessonQuestion::dataList($source->id);
        }
        return $data;
    }

    static function getCount(){
        if(IS_ADMIN){
            $count = self::NotDeleted()->where('status',2)->count();
        }else{
            $count = self::NotDeleted()->whereHas('Course',function($courseQuery){
                $courseQuery->where('instructor_id',USER_ID)->where('status',1);
            })->where('status',2)->count();
        }
        return $count;
    }

}
