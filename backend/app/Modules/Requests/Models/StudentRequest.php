<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentRequest extends Model{

    use \TraitsFunc;

    protected $table = 'students_courses';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function Course(){
        return $this->hasOne('App\Models\Course','id','course_id');
    }
    public function Student(){
        return $this->hasOne('App\Models\User','id','student_id');
    }
    public function Instructor(){
        return $this->hasOne('App\Models\User','id','instructor_id');
    }

    static function getOne($id){
        $source = self::NotDeleted()
            ->where('id', $id);
        return $source->first();
    }

    static function dataList($instructor_id=null,$student_id=null,$withPaginate = false) {
        $input = \Input::all();
        $isAdmin = IS_ADMIN;
        $userId = USER_ID;
        $source = self::NotDeleted()->whereHas('Student',function($studentQuery){
            $studentQuery->where('is_active',1);
        })->whereHas('Instructor',function($instructorQuery){
            $instructorQuery->where('is_active',1);
        })->whereHas('Course',function($courseQuery){
            $courseQuery->whereIn('status',[3,5]);
        })->with(['Student','Instructor','Course']);

        if (isset($input['course_id']) && !empty($input['course_id'])) {
            $source->where('course_id', $input['course_id']);
        } 

        if (isset($input['student_id']) && !empty($input['student_id'])) {
            $source->where('student_id', $input['student_id']);
        } 

        if (isset($input['instructor_id']) && !empty($input['instructor_id'])) {
            $source->where('instructor_id', $input['instructor_id']);
        } 

        if (isset($input['status']) && $input['status'] != null) {
            $source->where('status', $input['status']);
        } 

        if (isset($instructor_id) && !empty($instructor_id)) {
            $source->where('instructor_id', $instructor_id);
        } 

        if (isset($student_id) && !empty($student_id)) {
            $source->where('student_id', $student_id);
        } 

        if(!$isAdmin){
            $source->where('instructor_id', $userId);
        }
        return self::generateObj($source->orderBy('id','DESC'),$withPaginate);
    }

    static function generateObj($source,$withPaginate=false){
        if($withPaginate == true){
            $sourceArr = $source->paginate(PAGINATION);
        }else{
            $sourceArr = $source->get();
        }

        $list = [];
        foreach($sourceArr as $key => $value) {
            $list[$key] = new \stdClass();
            $list[$key] = self::getData($value);
        }
        if($withPaginate == true){
            $data['pagination'] = \Helper::GeneratePagination($sourceArr);
        }
        $data['data'] = $list;

        return $data;
    }

    static function getData($source) {
        $data = new  \stdClass();
        $data->id = $source->id;
        $data->course_id = $source->course_id;
        $data->course = $source->Course->title;
        $data->instructor_id = $source->instructor_id;
        $data->instructor = $source->Instructor->name;
        $data->student_id = $source->student_id;
        $data->student = $source->Student->name;
        $data->status = $source->status;
        return $data;
    }

    static function getCount(){
        $source = self::NotDeleted()->whereHas('Student',function($studentQuery){
            $studentQuery->where('is_active',1);
        })->whereHas('Instructor',function($instructorQuery){
            $instructorQuery->where('is_active',1);
        })->whereHas('Course',function($courseQuery){
            $courseQuery->whereIn('status',[3,5]);
        })->where('status',2);

        if(!IS_ADMIN){
            $source->where('instructor_id',USER_ID);
        }

        return $source->count();
    }

}