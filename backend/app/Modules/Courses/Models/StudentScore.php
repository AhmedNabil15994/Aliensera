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

    public function Instructor(){
        return $this->belongsTo('App\Models\User','instructor_id','id');
    }

    public function Question(){
        return $this->belongsTo('App\Models\LessonQuestion','question_id','id');
    }

    public function Student(){
        return $this->belongsTo('App\Models\User','student_id','id');
    }

    static function getOne($id){
        $source = self::NotDeleted()
            ->where('id', $id);

        return $source->first();
    }

    static function dataList($student_id=null) {
        $input = \Input::all();
        $source = self::where('id','!=',0);

        if (isset($student_id) && $student_id != null ) {
            $source->where('student_id', $student_id);
        }

        if(IS_ADMIN == false){
            $source->where('instructor_id',USER_ID);
        }

        $source->orderBy('id','DESC');
        return self::generateObj($source);
    }

    static function getByLesson($lesson_id){
        $source = self::where('lesson_id',$lesson_id);
        $source->orderBy('id','DESC');
        return self::generateObj2($source);
    }

    static function generateObj2($source){
        $sourceArr = $source->groupBy('student_id')->get();
        $list = [];
        foreach($sourceArr as $key => $value) {
            $list[$key] = new \stdClass();
            $list[$key] = self::getData2($value);
        }
        
        return (object) $list;
    }

    static function generateObj($source){
        $sourceArr2 = $source->get();
        $sourceArr = $source->groupBy('course_id')->get();
        $list = [];
        foreach($sourceArr as $key => $value) {
            $list[$key] = new \stdClass();
            $list[$key] = self::getData($value);
        }
        
        $allQuestion = $sourceArr2->count();
        $studentRight = $sourceArr2->where('correct',1)->count();
        $studentWrong = $sourceArr2->where('correct',0)->count();
        $score = round( ($studentRight / $allQuestion) * 100 ,2) .'%';
        
        $scoreObj = new \stdClass();
        $scoreObj->allQuestion = $allQuestion;
        $scoreObj->studentRightAnswers = $studentRight;
        $scoreObj->studentWrongAnswers = $studentWrong;
        $scoreObj->score = $score;

        $data['scores'] = $list;
        $data['total'] = $scoreObj;
        return (object) $data;
    }

    static function getLessons($student_id,$course_id){
        $data = [];
        $source = self::where('student_id',$student_id)->where('course_id',$course_id)->get();
        foreach ($source as $key => $value) {
            $dataObj = new \stdClass();
            $dataObj->question_id = $value->question_id;
            $dataObj->question = $value->Question->question;
            $dataObj->correct = $value->correct;
            $dataObj->answer = $value->answer;
            $dataObj->lesson_id = $value->lesson_id;
            $dataObj->lesson = $value->Lesson != null ? $value->Lesson->title : '';
            $data[$key] = $dataObj;
        }
        return $data;
    }

    static function getData($source) {
        $allQuestion = self::where('student_id',$source->student_id)->where('course_id',$source->course_id)->count();
        $studentRight = self::where('student_id',$source->student_id)->where('course_id',$source->course_id)->where('correct',1)->count();
        $studentWrong = self::where('student_id',$source->student_id)->where('course_id',$source->course_id)->where('correct',0)->count();
        $score = round( ($studentRight / $allQuestion) * 100 ,2) .'%';

        $data = new  \stdClass();
        $data->id = $source->id;
        $data->course_id = $source->course_id;
        $data->course = $source->Course != null ? $source->Course->title : '';
        $data->lessons = self::getLessons($source->student_id,$source->course_id);
        $data->instructor_id = $source->instructor_id;
        $data->all = $allQuestion;
        $data->right = $studentRight;
        $data->wrong = $studentWrong;
        $data->score = $score;
        $data->instructor = $source->Instructor != null ? $source->Instructor->name : '';
        $data->student_id = $source->student_id;
        $data->student = $source->Student != null ? $source->Student->name : '';
        $data->created_at = \Carbon\Carbon::createFromTimeStamp(strtotime($source->created_at))->diffForHumans();
        return $data;
    }

    static function getData2($source) {
        $allQuestion = self::where('student_id',$source->student_id)->where('lesson_id',$source->lesson_id)->count();
        $studentRight = self::where('student_id',$source->student_id)->where('lesson_id',$source->lesson_id)->where('correct',1)->count();
        $studentWrong = self::where('student_id',$source->student_id)->where('lesson_id',$source->lesson_id)->where('correct',0)->count();
        $score = round( ($studentRight / $allQuestion) * 100 ,2) .'%';

        $data = new  \stdClass();
        $data->id = $source->id;
        $data->course_id = $source->course_id;
        $data->course = $source->Course != null ? $source->Course->title : '';
        $data->instructor_id = $source->instructor_id;
        $data->instructor = $source->Instructor != null ? $source->Instructor->name : '';
        $data->student_id = $source->student_id;
        $data->student = $source->Student != null ? $source->Student->name : '';
        $data->all = $allQuestion;
        $data->right = $studentRight;
        $data->wrong = $studentWrong;
        $data->score = $score;
        $data->created_at = \Carbon\Carbon::createFromTimeStamp(strtotime($source->created_at))->diffForHumans();
        return $data;
    }
}
