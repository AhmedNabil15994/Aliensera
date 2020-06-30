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

    function Discussion(){
        return $this->hasMany('App\Models\CourseDiscussion', 'course_id','id');
    }

    function StudentCourse(){
        return $this->hasMany('App\Models\StudentCourse', 'course_id','id');
    }

    function StudentDuration(){
        return $this->hasMany('App\Models\StudentVideoDuration', 'course_id','id');
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
            ->where('id', $id)->whereIn('status',[3,5])->first();
  
        return $source;
    }

    static function getRalated($field_id,$counter,$notId=null){
        $source = self::NotDeleted()->with('Feedback')->whereIn('status',[3,5])->where('field_id',$field_id)->where('id','!=',$notId)->take($counter);
        return self::generateObj($source);
    }

    static function dataList($type=null,$counter=null) {
        $input = \Input::all();

        $source = self::NotDeleted()->where('status',3)->with('Feedback')->where(function($whereQuery){
            $whereQuery->where('valid_until','!=',null)->orWhere('valid_until','>=',date('Y-m-d'));
        });

        if (isset($input['keyword']) && !empty($input['keyword'])) {
            $source->where('title', 'LIKE', '%' . $input['keyword'] . '%')
                    ->orWhere('description','LIKE','%'.$input['keyword'].'%');
        } 
        if (isset($input['instructor_id']) && !empty($input['instructor_id'])) {
            $source->where('instructor_id', $input['instructor_id']);
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
        if (isset($input['year']) && !empty($input['year'])) {
            $source->where('year', $input['year']);
        }  
        if (isset($input['valid_until']) && !empty($input['valid_until'])) {
            $source->where('valid_until' ,'>=', $input['valid_until']);
        }  

        if ( ( isset($input['price_from']) && !empty($input['price_from']) ) && ( isset($input['price_to']) && !empty($input['price_to']) ) ){
            $source->where('price','>=', $input['price_from'])->where('price','<=',$input['price_to']);
        }  

        if($type == null && isset($input['type']) && !empty($input['type'])){
            $type = $input['type'];
        }

        if ( isset($type) && $type != null) {
            $student_id = USER_ID;
            if($type == 1){
                $source->whereHas('StudentCourse',function($query) use ($student_id){
                    $query->NotDeleted()->where('student_id',$student_id)->where('status',1);
                });
            }elseif($type == 2){
                $source->whereHas('StudentCourse',function($query){
                    $query->NotDeleted()->where('status',1)->withCount('Course')->orderBy('course_count', 'desc')->groupBy('course_id');
                });
            }
            elseif($type == 3){
                $source->whereHas('Feedback',function($query){
                    $query->NotDeleted()->where('status',1)->withCount('Course')->orderBy('course_count', 'desc')->groupBy('course_id');
                });
            }
        }         

        return self::generateObj($source);
    }

    static function generateObj($source){
        $sourceArr = $source->paginate(PAGINATION);
        $list = [];
        foreach($sourceArr as $key => $value) {
            $list[$key] = new \stdClass();
            if($value->valid_until != null && date('Y-m-d') > $value->valid_until){
                $value->status = 4;
                $value->save();
            }
            $list[$key] = self::getData($value);
        }
        $data['data'] = $list;
        $data['pagination'] = \Helper::GeneratePagination($sourceArr);
        return $data;
    }

    static function getStatus($status){
        $statusLabel = '';
        if($status == 1){
            $statusLabel = "Instructor Sent Request";
        }elseif($status == 2){
            $statusLabel = "Request Refused";
        }elseif($status == 3 || $status == 5){
            $statusLabel = "Active";
        }elseif($status == 4){
            $statusLabel = "Expired";
        }
        return $statusLabel;
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
        $data->what_learn = $source->what_learn;
        $data->requirements = $source->requirements;
        $data->durationBySeconds = $source->Video != null ? $source->Video()->NotDeleted()->sum('duration') : 0;
        if($source->StudentDuration()->where('student_id',USER_ID) != null){
            $data->seeDuration = $source->StudentDuration()->where('student_id',USER_ID)->sum('see_duration');
        }
        $data->isOwned = StudentCourse::checkOwned($source->id,USER_ID);
        $data->isFavourite = Favourites::checkFav($source->id,USER_ID);
        $data->isInCart = Cart::checkCart($source->id,USER_ID);
        $data->valid_until = $source->valid_until;
        $data->studentCount = $source->StudentCourse != null ? $source->StudentCourse()->NotDeleted()->where('status',1)->count() : 0;
        $data->commentsCount = $source->Comment != null ? $source->Comment()->NotDeleted()->count() : 0;
        $data->lessonsCount = $source->Lesson != null ? $source->Lesson()->NotDeleted()->where('status',1)->count() : 0;
        $data->videosCount = $source->Video != null ? $source->Video()->NotDeleted()->count() : 0;
        $data->allTime = $source->Video != null ? self::getDuration($source->Video()->NotDeleted()->sum('duration')) : 0;
        $data->rateCount = $source->Feedback != null ? $source->Feedback()->NotDeleted()->count() :0;
        $data->rateSum = $source->Feedback != null ? $source->Feedback()->NotDeleted()->sum('rate') :0;
        $data->totalRate = $data->rateCount!= 0 ? round(($data->rateSum / ( 5 * $data->rateCount)) * 5 ,1) : 0;
        $data->free_videos =  LessonVideo::dataList(null,$source->id,1);
        $data->feedback = $source->Feedback != null ? CourseFeedback::dataList($source->id) : [];
        $data->discussion = $source->Discussion != null ? CourseDiscussion::dataList($source->id) : [];
        $data->image = $source->image != null ? self::getPhotoPath($source->id, $source->image) : '';
        $data->instructor = $source->Instructor != null ? User::getInstructorData($source->Instructor,1) : '';
        $data->created_at = \Helper::formatDateForDisplay($source->created_at);
        $data->lessons = $source->Lesson != null ? Lesson::dataList($source->id)['data'] : [];
        // if($type == 1){
        //     $controllerObj = new \App\Http\Controllers\CourseControllers();
        //     $data->certificate = $controllerObj->getCertificate($source->id)->original->link;   
        // }
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
