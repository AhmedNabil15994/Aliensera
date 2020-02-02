<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model{

    use \TraitsFunc;

    protected $table = 'courses';
    protected $primaryKey = 'id';
    public $timestamps = false;

    function Instructor(){
        return $this->belongsTo('App\Models\User', 'instructor_id');
    }

    function University(){
        return $this->belongsTo('App\Models\University', 'university_id');
    }

    function Faculty(){
        return $this->belongsTo('App\Models\Faculty', 'faculty_id');
    }

    function Field(){
        return $this->belongsTo('App\Models\Field', 'field_id');
    }

    function Feedback(){
        return $this->hasMany('App\Models\CourseFeedback', 'course_id','id');
    }

    function StudentCourse(){
        return $this->hasMany('App\Models\StudentCourse', 'course_id','id');
    }

    function Lesson(){
        return $this->hasMany('App\Models\Lesson', 'course_id','id');
    }

    function Video(){
        return $this->hasMany('App\Models\LessonVideo', 'course_id','id');
    }

    function Comment(){
        return $this->hasMany('App\Models\VideoComment', 'course_id','id');
    }

    static function getPhotoPath($id, $photo) {
        return \ImagesHelper::GetImagePath('courses', $id, $photo);
    }

    static function getOne($id){
        $source = self::NotDeleted()
            ->where('id', $id);
        
        if(IS_ADMIN == false){
            $source->where('instructor_id',USER_ID);
        }

        return $source->first();
    }

    static function getOneD($id) {
        return self::where('id', $id)
            ->first();
    }

    static function dataList($instructor_id=null,$student_id=null) {
        $input = \Input::all();

        $source = self::with('Feedback');

        if (isset($input['title']) && !empty($input['title'])) {
            $source->where('title', 'LIKE', '%' . $input['title'] . '%');
        } 
        if (isset($input['instructor_id']) && !empty($input['instructor_id'])) {
            $source->where('instructor_id', $input['instructor_id']);
        } 
        if (isset($input['status']) && !empty($input['status'])) {
            $source->where('status', $input['status']);
        } 
        if (isset($input['course_type']) && !empty($input['course_type'])) {
            $source->where('course_type', $input['course_type']);
        } 
        if (isset($input['university_id']) && !empty($input['university_id'])) {
            $source->where('university_id', $input['university_id']);
        } 
        if (isset($input['faculty_id']) && !empty($input['faculty_id'])) {
            $source->where('faculty_id', $input['faculty_id']);
        } 
        if (isset($input['field_id']) && !empty($input['field_id'])) {
            $source->where('field_id', $input['field_id']);
        } 

        if ($instructor_id != null) {
            $source->where('instructor_id', $instructor_id);
        } 

        if ($student_id != null) {
            $source->whereHas('StudentCourse',function($query) use ($student_id){
                $query->NotDeleted()->where('student_id',$student_id)->where('status',1);
            });
        } 

        if(IS_ADMIN == false){
            $source->where('instructor_id',USER_ID);
        }

        return self::generateObj($source);
    }

    static function generateObj($source){
        $sourceArr = $source->paginate(PAGINATION);

        $list = [];
        foreach($sourceArr as $key => $value) {
            $list[$key] = new \stdClass();
            if(date('Y-m-d') > $value->valid_until){
                $value->status = 4;
                $value->save();
            }
            $list[$key] = self::getData($value);
        }

        $data['pagination'] = \Helper::GeneratePagination($sourceArr);
        $data['data'] = $list;

        return $data;
    }

    static function getStatus($status){
        $statusLabel = '';
        if($status == 1){
            $statusLabel = "<span class='btn btn-warning btn-xs'>Instructor Sent Request</span>";
        }elseif($status == 2){
            $statusLabel = "<span class='btn btn-danger btn-xs'>Request Refused</span>";
        }elseif($status == 3){
            $statusLabel = "<span class='btn btn-success btn-xs'>Active</span>";
        }elseif($status == 4){
            $statusLabel = "<span class='btn btn-dark btn-xs'>Expired</span>";
        }
        return $statusLabel;
    }

    static function getDuration($duration){
        $result = '';
        if($duration > 3600){
            $hours = round($duration / 60);
            $minutes = $duration % 60;
            $result = $hours.' Hr '.$minutes.' Min';
        }elseif($duration > 60){
            $minutes = round($duration / 60);
            $seconds = $duration % 60;
            $result = $minutes.' Min '.$seconds.' Sec';
        }elseif($duration > 0 && $duration < 60){
            $result = $duration.' Sec';
        }
        return $result;
    }

    static function getData($source) {
        $data = new  \stdClass();
        $data->id = $source->id;
        $data->title = $source->title;
        $data->description = $source->description;
        $data->status = $source->status;
        $data->statusLabel = self::getStatus($source->status);
        $data->course_type = $source->course_type;
        $data->courseType = $source->course_type == 1 ? 'General' : 'University';
        $data->university_id = $source->university_id;
        $data->university = $source->University != null ? $source->University->title : '';
        $data->faculty = $source->Faculty != null ? $source->Faculty->title : '';
        $data->faculty_id = $source->faculty_id;
        $data->field = $source->Field != null ? $source->Field->title : '';
        $data->field_id = $source->field_id;
        $data->price = $source->price;
        $data->year = $source->year;
        $data->valid_until = $source->valid_until;
        $data->lessons = $source->Lesson != null ? Lesson::dataList($source->id)['data'] : [];
        $data->commentsCount = $source->Comment != null ? $source->Comment()->NotDeleted()->count() : 0;
        $data->lessonsCount = $source->Lesson != null ? $source->Lesson()->NotDeleted()->count() : 0;
        $data->videosCount = $source->Video != null ? $source->Video()->NotDeleted()->count() : 0;
        $data->allTime = $source->Video != null ? self::getDuration($source->Video()->NotDeleted()->sum('duration')) : 0;
        $data->rateCount = $source->Feedback != null ? $source->Feedback()->NotDeleted()->count() :0;
        $data->rateSum = $source->Feedback != null ? $source->Feedback()->NotDeleted()->sum('rate') :0;
        $data->totalRate = $data->rateCount!= 0 ? round(($data->rateSum / ( 5 * $data->rateCount)) * 5 ,1) : 0;
        $data->feedback = $source->Feedback != null ? CourseFeedback::dataList($source->id) : [];
        $data->image = $source->image != null ? self::getPhotoPath($source->id, $source->image) : '';
        $data->instructor_id = $source->instructor_id;
        $data->instructor = $source->instructor != null ? $source->instructor->name : '';
        $data->created_at = \Helper::formatDateForDisplay($source->created_at);
        $data->deleted_by = $source->deleted_by;
        return $data;
    }

    static function getCount(){
        if(IS_ADMIN){
            $count = self::NotDeleted()->where('status',1)->count();
        }else{
            $count = self::NotDeleted()->where('instructor_id',USER_ID)->where('status',1)->count();
        }
        return $count;
    }

}
