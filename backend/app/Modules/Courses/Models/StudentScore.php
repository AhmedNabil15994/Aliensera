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

    public function Corrects(){
        return $this->where('correct',1);
    }

    static function getOne($id){
        $source = self::NotDeleted()
            ->where('id', $id);

        return $source->first();
    }

    static function dataList($student_id=null,$type=null) {
        $input = \Input::all();
        $source = self::where('id','!=',0);

        if (isset($student_id) && $student_id != null ) {
            $source->where('student_id', $student_id);
        }

        if(IS_ADMIN == false && $type == null){
            $source->where('instructor_id',USER_ID);
        }

        if(IS_ADMIN == false && $type == 1){
            $student_id = null;
            if (isset($input['course_id']) && $input['course_id'] != null ) {
                $source->where('course_id', $input['course_id']);
            }
            if (isset($input['student_id']) && $input['student_id'] != null ) {
                $student_id = $input['student_id'];
                $source->where('student_id', $input['student_id']);
            }
            $source->where('instructor_id',USER_ID)->groupBy('lesson_id')->orderBy('id','DESC');
            return self::generateObjWithPagination($source,$student_id);
        }

        $source->orderBy('course_id','DESC');
        return self::generateObj($source);
    }

    static function generateObjWithPagination($source,$student_id=null){
        $sourceArr = $source->paginate(PAGINATION);

        $list = [];
        foreach($sourceArr as $key => $value) {
            $list[$key] = new \stdClass();
            $list[$key] = self::getDataForPagination($value,$student_id);
        }

        $data['pagination'] = \Helper::GeneratePagination($sourceArr);
        $data['data'] = $list;

        return $data;
    }

    static function getDataForPagination($source,$student_id=null) {
        $allQuestions = LessonQuestion::NotDeleted()->where('lesson_id',$source->lesson_id)->count();
        $students = [];
        $rights = 0;
        $wrongs = 0;
        $alls = 0;

        if($student_id != null){
            $collection = self::where('lesson_id',$source->lesson_id)->where('student_id',$student_id)->groupBy('student_id')->get();
        }else{
            $collection = self::where('lesson_id',$source->lesson_id)->groupBy('student_id')->get();
        }
        foreach ($collection as $key => $value) {
            $allQuestion = self::where('lesson_id',$source->lesson_id)->where('student_id',$value->student_id)->count();
            $studentRight = self::where('lesson_id',$source->lesson_id)->where('student_id',$value->student_id)->where('correct',1)->count();
            $studentWrong = self::where('lesson_id',$source->lesson_id)->where('student_id',$value->student_id)->where('correct',0)->count();
            $studentScore = round( ($studentRight / $allQuestion) * 100 ,2) .'%';
            $score = new  \stdClass();
            $score->all = $allQuestion;
            $score->right = $studentRight;
            $score->wrong = $studentWrong;
            $score->score = $studentScore;
            $score->student_id = $value->student_id;
            $score->student = $value->Student != null ? $value->Student->name : '';
            $students[$key] = $score;
            $rights+= $studentRight;
            $wrongs+= $studentWrong;
            $alls+= $allQuestion;
        }

        $data = new  \stdClass();
        $data->id = $source->id;
        $data->students = (object) $students;
        $data->course_id = $source->course_id;
        $data->course = $source->Course != null ? $source->Course->title : '';
        $data->lesson_id = $source->lesson_id;
        $data->lesson = $source->Lesson != null ? $source->Lesson->title : '';
        $data->instructor_id = $source->instructor_id;
        $data->instructor = $source->Instructor != null ? $source->Instructor->name : '';
        $data->all = $allQuestions;
        $data->right = $rights;
        $data->wrong = $wrongs;
        $data->score = round( ($rights / $alls) * 100 ,2) .'%';
        $data->created_at = \Carbon\Carbon::createFromTimeStamp(strtotime($source->created_at))->diffForHumans();
        return $data;
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
        $sourceArr = $source->groupBy('lesson_id')->get();
        $list = [];
        foreach($sourceArr as $key => $value) {
            $list[$key] = new \stdClass();
            $list[$key] = self::getData($value);
        }
        
        $allQuestion = $sourceArr2->count();
        $studentRight = $sourceArr2->where('correct',1)->count();
        $studentWrong = $sourceArr2->where('correct',0)->count();
        $score = $allQuestion > 0 ? round( ($studentRight / $allQuestion) * 100 ,2) .'%' : '0%';
        
        $scoreObj = new \stdClass();
        $scoreObj->allQuestion = $allQuestion;
        $scoreObj->studentRightAnswers = $studentRight;
        $scoreObj->studentWrongAnswers = $studentWrong;
        $scoreObj->score = $score;

        $data['scores'] = $list;
        $data['total'] = $scoreObj;
        return (object) $data;
    }

    static function getLessonsWithRank($student_id,$course_id,$lesson_id){
        $data = [];
        $source = self::where('student_id',$student_id)->where('course_id',$course_id)->where('lesson_id',$lesson_id)->get();
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

        $scores = [];

        $lessonQuestion = LessonQuestion::NotDeleted()->where('lesson_id',$lesson_id)->count();
        $studentCorrect = self::where('student_id',$student_id)->where('lesson_id',$lesson_id)->sum('correct');
        $studentFinalScore = round( ($studentCorrect / $lessonQuestion) * 100 ,2);
        $scores[0] = $studentFinalScore;

        $otherStudentCorrect = self::where('lesson_id',$lesson_id)->where('student_id','!=',$student_id)->groupBy('student_id','lesson_id')->orderBy('id','asc')->get()->pluck('student_id');

        foreach ($otherStudentCorrect as $value) {
            $correct = self::where('student_id',$value)->where('lesson_id',$lesson_id)->sum('correct');
            $result = round( ($correct / $lessonQuestion) * 100 ,2);
            $scores[] = $result; 
        }

        $socres = usort($scores, function ($a, $b) {
            return $b <=> $a;
        });

        $scores = array_values($scores);
        $scores = array_unique($scores);
        $scores = array_values($scores);

        $rank = array_keys($scores,$studentFinalScore)[0] + 1  . '/'. count($scores);
        
        return [$data,$rank];
    }

    static function getData($source) {
        $allQuestion = self::where('student_id',$source->student_id)->where('course_id',$source->course_id)->groupBy('lesson_id')->count();
        $studentRight = self::where('student_id',$source->student_id)->where('course_id',$source->course_id)->groupBy('lesson_id')->where('correct',1)->count();
        $studentWrong = self::where('student_id',$source->student_id)->where('course_id',$source->course_id)->groupBy('lesson_id')->where('correct',0)->count();
        $score = round( ($studentRight / $allQuestion) * 100 ,2) .'%';
        $lessonQuizs = self::getLessonsWithRank($source->student_id,$source->course_id,$source->lesson_id);

        $data = new  \stdClass();
        $data->id = $source->id;
        $data->course_id = $source->course_id;
        $data->course = $source->Course != null ? $source->Course->title : '';
        $data->lessons = $lessonQuizs[0];
        $data->rank = $lessonQuizs[1];
        $data->lesson_id = $source->lesson_id;
        $data->lesson = $source->Lesson != null ? $source->Lesson->title : '';
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
        $data->lesson_id = $source->lesson_id;
        $data->lesson = $source->Lesson != null ? $source->Lesson->title : '';
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
