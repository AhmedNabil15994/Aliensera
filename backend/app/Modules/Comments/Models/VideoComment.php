<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoComment extends Model{

    use \TraitsFunc;

    protected $table = 'video_comments';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function Creator(){
        return $this->belongsTo('App\Models\User','created_by','id');
    }

    public function Video(){
        return $this->belongsTo('App\Models\LessonVideo','video_id','id');
    }

    public function Course(){
        return $this->belongsTo('App\Models\Course','course_id','id');
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

    static function dataList($video_id=null,$main=null,$isMain=null,$creator=null,$pagination=null) {
        $input = \Input::all();
        $source = self::NotDeleted()->where('status',1);

        if (isset($video_id) && $video_id != null ) {
            $source->where('video_id', $video_id);
        } 
        if (isset($isMain) && $isMain != null ) {
            $source->where('reply_on', 0);
        } 
        if (isset($main) && $main != null ) {
            $source->where('reply_on', $main);
        } 
        if (isset($creator) && $creator != null ) {
            $source->where('created_by', $creator);
        } 

        if(isset($input['student_id']) && !empty($input['student_id'])){
            $source->where('created_by',$input['student_id']);
        }

        if(isset($input['course_id']) && !empty($input['course_id'])){
            $source->where('course_id',$input['course_id']);
        }

        if(IS_ADMIN == false){
            $source->whereHas('Course',function($courseQuery) {
                $courseQuery->where('instructor_id',USER_ID);
            });
        }

        $source->orderBy('id','DESC');
        return self::generateObj($source,$pagination);
    }

    static function generateObj($source,$pagination=null){
        if($pagination == null){
            $sourceArr = $source->get();
        }else{
            $sourceArr = $source->paginate(PAGINATION);
        }
        $list = [];
        foreach($sourceArr as $key => $value) {
            $list[$key] = new \stdClass();
            $list[$key] = self::getData($value);
        }
        if($pagination != null){
            $data['pagination'] = \Helper::GeneratePagination($sourceArr);
            $data['data'] = $list;
            return $data;
        }
        return (object) $list;
    }

    static function getCreator($created_by,$instructor_id){
        $result = '';
        if($created_by == $instructor_id){
            $result = '<span class="label bg-green online">Owner</span>';
        }
        return $result;
    }

    static function getData($source) {
        $data = new  \stdClass();
        $data->id = $source->id;
        $data->video_id = $source->video_id;
        $data->video_title = $source->Video != null ? $source->Video->title : '';
        $data->course = $source->Course != null ? $source->Course->title : '';
        $data->comment = $source->comment; 
        $data->status = $source->status;
        $data->reply_on = $source->reply_on;
        $data->replies = $source->reply_on == 0 ? self::dataList($source->video_id,$source->id) : [];
        $data->image = User::getData($source->Creator)->image;
        $data->creator = $source->Creator->name .' '. self::getCreator($source->created_by,$source->Course->instructor_id);
        $data->created_at = \Carbon\Carbon::createFromTimeStamp(strtotime($source->created_at))->diffForHumans();
        return $data;
    }

}
