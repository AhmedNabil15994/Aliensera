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
            
        return $source->first();
    }

    static function dataList($course_id=null) {
        $input = \Input::all();

        $source = self::NotDeleted()->where('status',1);

        if (isset($input['title']) && !empty($input['title'])) {
            $source->where('title', 'LIKE', '%' . $input['title'] . '%');
        } 

        if (isset($input['course_id']) && !empty($input['course_id'])) {
            $source->where('course_id', $input['course_id']);
        } 

        if (isset($course_id) && !empty($course_id) && $course_id != null) {
            $source->where('course_id', $course_id);
        } 

        $source->orderBy('sort','asc');
        return self::generateObj($source);
    }

    static function generateObj($source){
        $sourceArr = $source->get();

        $list = [];
        foreach($sourceArr as $key => $value) {
            $list[$key] = new \stdClass();
            $list[$key] = self::getData($value);
        }
        // $data['pagination'] = \Helper::GeneratePagination($sourceArr);
        $data['data'] = $list;

        return $data;
    }

    static function getUserScore($id){

        $allQuestion = LessonQuestion::NotDeleted()->where('lesson_id',$id)->count();
        $studentRight = StudentScore::where('lesson_id',$id)->where('student_id',USER_ID)->where('correct',1)->count();
        $studentWrong = StudentScore::where('lesson_id',$id)->where('student_id',USER_ID)->where('correct',0)->count();
        $score = round( ($studentRight / $allQuestion) * 100 ,2) .'%';

        $data = new \stdClass();
        $data->allQuestion = $allQuestion;
        $data->studentAnswers = StudentScore::where('lesson_id',$id)->where('student_id',USER_ID)->count();
        $data->studentRightAnswers = $studentRight;
        $data->studentWrongAnswers = $studentWrong;
        $data->score = $score;
        return $data;
    }

    static function getData($source) {
        $userCount = StudentScore::where('lesson_id',$source->id)->where('student_id',USER_ID)->count();
        $data = new  \stdClass();
        $data->id = $source->id;
        $data->title = $source->title;
        $data->course_id = $source->course_id;
        $data->course = $source->Course->title;
        $data->description = $source->description;
        $data->valid_until = $source->valid_until;
        $data->questions_sort = $source->questions_sort;
        $data->quiz_duration = $source->quiz_duration;
        $data->pass_quiz_to_view_next_lesson = $source->pass_quiz;
        $data->student_must_get = (int) \App\Models\Variable::getVar('STUDENT_PERCENTAGE_TO_VIEW_NEXT_LESSON');
        if($userCount > 0){
            $myData = self::getUserScore($source->id);
            $data->myScore = $myData;
            $data->last_quiz_score = str_replace('%', '', $myData->score);
        }else{
            $data->last_quiz_score = 0;
        }
        $data->status = $source->status;
        $data->free_videos =  LessonVideo::dataList($source->id,null,1);
        $data->videos =  LessonVideo::dataList($source->id);
        $data->questions =  LessonQuestion::dataList($source->id);
        
        return $data;
    }

}
