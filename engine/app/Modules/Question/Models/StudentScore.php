<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentScore extends Model{

    use \TraitsFunc;

    protected $table = 'student_scores';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function Lesson(){
        return $this->belongsTo('App\Models\Lesson','lesson_id','id');
    }

    public function Course(){
        return $this->belongsTo('App\Models\Course','course_id','id');
    }

    public function Question(){
        return $this->belongsTo('App\Models\LessonQuestion','question_id','id');
    }

    public function Instructor(){
        return $this->belongsTo('App\Models\User','instructor_id','id');
    }

    public function Student(){
        return $this->hasMany('App\Models\User','student_id','id');
    }

    static function getOne($id){
        return self::where('id', $id)->first();
    }

    static function dataList() {
        $input = \Input::all();
        $source = self::where('student_id',USER_ID);

        if (isset($input['lesson_id']) && !empty($input['lesson_id']) ) {
            $source->where('lesson_id', $input['lesson_id']);
        } 

        if (isset($input['course_id']) && !empty($input['course_id']) ) {
            $source->where('course_id', $input['course_id']);
        } 

        if (isset($input['question_id']) && !empty($input['question_id']) ) {
            $source->where('question_id', $input['question_id']);
        } 

        if (isset($input['correct']) && !empty($input['correct']) ) {
            $source->where('correct', $input['correct']);
        } 

        return self::generateObj($source);
    }

    static function generateObj($source){
        $sourceArr = $source->get();
        $list = [];
        foreach($sourceArr as $key => $value) {
            $list[$key] = new \stdClass();
            $list[$key] = self::getData($value);
        }

        return $list;
    }

    static function getData($source) {
        $data = LessonQuestion::getData($source->Question);
        $data->my_answer = $source->answer;
        $data->student_answer_id = $source->id;
        $data->instructor_id = $source->instructor_id;
        $data->instructor = $source->Instructor->name;
        $data->correct = $source->correct;
        
        return $data;
    }

}
