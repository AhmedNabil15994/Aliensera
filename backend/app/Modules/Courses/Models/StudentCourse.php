<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentCourse extends Model{

    use \TraitsFunc;

    protected $table = 'students_courses';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function Instructor(){
        return $this->belongsTo('App\Models\User','instructor_id','id');
    }

    public function Student(){
        return $this->belongsTo('App\Models\User','student_id','id');
    }

    public function Course(){
        return $this->belongsTo('App\Models\Course','course_id','id');
    }

    static function getOne($id){
        $source = self::NotDeleted()->where('id', $id);
            
        if(IS_ADMIN == false){
            $source->where('instructor_id',USER_ID)->where('status',1);
        }
        return $source->first();
    }

    static function dataList($course_id=null,$creator=null,$student=null,$paginate=false) {
        $input = \Input::all();
        $source = self::NotDeleted()->where('status',1);

        if (isset($course_id) && $course_id != null ) {
            $source->where('course_id', $course_id);
        } 
        if (isset($creator) && $creator != null ) {
            $source->where('instructor_id', $creator);
        }
        if (isset($student) && $student != null ) {
            $source->where('student_id', $student);
        } 

        if(isset($input['student_id']) && !empty($input['student_id'])){
            $source->where('student_id', $input['student_id']);
        }

        if(isset($input['course_id']) && !empty($input['course_id'])){
            $source->where('course_id', $input['course_id']);
        }

        if(IS_ADMIN == false){
            $source->where('instructor_id', $creator);
        }

        $source->orderBy('id','DESC');
        if($paginate){
            return self::generateObjWithPagination($source);
        }
        return self::generateObj($source);
    }

    static function getTopStudents($count){
        $source = self::NotDeleted();
        if(IS_ADMIN == false){
            $source->where('instructor_id',USER_ID);
        }
        $source = $source->where('status',1)->withCount('Student')->orderBy('student_count', 'desc')->groupBy('student_id')->get($count);
        return self::generateObj2($source,'student');
    }

    static function getTopCourses($count){
        $source = self::NotDeleted();
        if(IS_ADMIN == false){
            $source->where('instructor_id',USER_ID);
        }
        $source = $source->where('status',1)->withCount('Course')->orderBy('course_count', 'desc')->groupBy('course_id')->get($count);
        return self::generateObj2($source,'course');
    }

    static function getTopInstructors($count){
        $source = self::NotDeleted()->where('status',1)->withCount('Instructor')->orderBy('instructor_count', 'desc')->groupBy('instructor_id')->get($count);
        return self::generateObj2($source,'instructor');
    }

    static function generateObj($source){
        $sourceArr = $source->get();
        $list = [];
        foreach($sourceArr as $key => $value) {
            $list[$key] = new \stdClass();
            $list[$key] = self::getData($value);
        }
        return (object) $list;
    }

    static function generateObjWithPagination($source){
        $sourceArr = $source->paginate(PAGINATION);
        $list = [];
        foreach($sourceArr as $key => $value) {
            $list[$key] = new \stdClass();
            $list[$key] = self::getData($value);
        }
        
        $data['pagination'] = \Helper::GeneratePagination($sourceArr);
        $data['data'] = $list;
        return (object) $data;
    }

    static function generateObj2($source,$type=null){
        $list = [];
        foreach($source as $key => $value) {
            $list[$key] = new \stdClass();
           if ($type == 'student') {
                $list[$key] = self::getStudentData($value);
            }elseif ($type == 'course') {
                $list[$key] = self::getCourseData($value);
            }elseif ($type == 'instructor') {
                $list[$key] = self::getInstructorData($value);
            }
        }
        return (object) $list;
    }

    static function getData($source) {
        $data = new  \stdClass();
        $data->id = $source->id;
        $data->course_id = $source->course_id;
        $data->student_id = $source->student_id;
        $data->instructor_id = $source->instructor_id;
        $data->course_title = $source->Course != null ? $source->Course->title : '';
        $data->myCourse = $source->Course != null ? Course::getData($source->Course) : [];
        $data->instructor = $source->Instructor != null ? $source->Instructor->name : '';
        $data->student = $source->Student != null ? $source->Student->name : '';
        $data->student_image = $source->Student != null ? User::getData($source->Student)->image : '';
        $data->status = $source->status;
        $data->creator = $source->Creator->name;
        $data->created_at = \Carbon\Carbon::createFromTimeStamp(strtotime($source->created_at))->diffForHumans();
        return $data;
    }

    static function getStudentData($source) {
        $data = new  \stdClass();
        $data->id = $source->id;
        $data->student = $source->Student != null ? User::getData($source->Student) : '';
        if(IS_ADMIN == false){
            $data->count = self::where('instructor_id',USER_ID)->where('student_id',$source->student_id)->where('status',1)->groupBy('student_id')->count('course_id');
        }else{
            $data->count = self::where('student_id',$source->student_id)->where('status',1)->groupBy('student_id')->count('course_id');
        }
        return $data;
    }

    static function getInstructorData($source) {
        $data = new  \stdClass();
        $data->id = $source->id;
        $data->instructor = $source->Instructor != null ? User::getData($source->Instructor) : '';
        $data->count = self::where('instructor_id',$source->instructor_id)->where('status',1)->groupBy('instructor_id')->count('course_id');
        $data->count2 = self::where('instructor_id',$source->instructor_id)->where('status',1)->groupBy('instructor_id')->count('student_id');
        return $data;
    }

    static function getCourseData($source) {
        $data = new  \stdClass();
        $data->id = $source->id;
        $data->course = $source->Course != null ? Course::getData($source->Course) : '';
        $data->count = self::where('course_id',$source->course_id)->where('status',1)->groupBy('course_id')->count('student_id');
        return $data;
    }

    static function getRevenue(){
        $source = self::NotDeleted()->where('paid',1)->whereHas('Course',function($courseQuery){
            $courseQuery->where('status',3);
        })->whereHas('Instructor',function($instructorQuery){
            $instructorQuery->where('is_active',1);
        })->whereHas('Student',function($studentQuery){
            $studentQuery->where('is_active',1);
        });
        if(IS_ADMIN == false){
            $source->where('instructor_id',USER_ID);
        }

        $dataList = $source->get();
        $total = 0;
        foreach ($dataList as $value) {
            $price = Course::getData($value->Course)->price;
            $count = self::NotDeleted()->where('paid',1)->where('course_id',$value->course_id)->count();
            $all = $price * $count;
            $total+= $all;
        }

        return round($total ,2);
    }

}
