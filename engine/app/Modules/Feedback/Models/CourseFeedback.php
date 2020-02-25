<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseFeedback extends Model{

    use \TraitsFunc;

    protected $table = 'course_feedback';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function Creator(){
        return $this->belongsTo('App\Models\User','created_by','id');
    }

    public function Course(){
        return $this->belongsTo('App\Models\Course','course_id','id');
    }

    static function getOne($id){
        $source = self::NotDeleted()
            ->where('id', $id);
            
        return $source->first();
    }

    static function dataList($course_id=null,$creator=null) {
        $input = \Input::all();
        $source = self::NotDeleted()->where('status',1);

        if (isset($course_id) && $course_id != null ) {
            $source->where('course_id', $course_id);
        } 
        if (isset($creator) && $creator != null ) {
            $source->where('created_by', $creator);
        } 

        $source->orderBy('id','DESC');
        return self::generateObj($source->get());
    }

    static function getTopRatedCourses($count){
        $source = self::NotDeleted();
        $source = $source->where('status',1)->withCount('Course')->orderBy('course_count', 'desc')->groupBy('course_id')->get($count);
        return self::generateObj($source,'myCourses');
    }

    static function generateObj($source,$type=null){
        $sourceArr = $source;
        $list = [];
        foreach($sourceArr as $key => $value) {
            $list[$key] = new \stdClass();
            if($type == 'myCourses'){
                $list[$key] = Course::getData($value->Course);
            }else{
                $list[$key] = self::getData($value);
            }
        }
        return $list;
    }

    static function getTotals($course_id){
        $allFeedbacks = self::NotDeleted()->where('course_id',$course_id)->where('status',1)->count(); 
        $star1 = self::NotDeleted()->where('course_id',$course_id)->where('status',1)->where('rate',1)->count();
        $star2 = self::NotDeleted()->where('course_id',$course_id)->where('status',1)->where('rate',2)->count();
        $star3 = self::NotDeleted()->where('course_id',$course_id)->where('status',1)->where('rate',3)->count();
        $star4 = self::NotDeleted()->where('course_id',$course_id)->where('status',1)->where('rate',4)->count();
        $star5 = self::NotDeleted()->where('course_id',$course_id)->where('status',1)->where('rate',5)->count();
        $data = new  \stdClass();
        $data->star1 =  $allFeedbacks > 0 ? round( ($star1 / $allFeedbacks) * 100 ,2) . '%' : 0;
        $data->star2 =  $allFeedbacks > 0 ? round( ($star2 / $allFeedbacks) * 100 ,2) . '%' : 0;
        $data->star3 =  $allFeedbacks > 0 ? round( ($star3 / $allFeedbacks) * 100 ,2) . '%' : 0;
        $data->star4 =  $allFeedbacks > 0 ? round( ($star4 / $allFeedbacks) * 100 ,2) . '%' : 0;
        $data->star5 =  $allFeedbacks > 0 ? round( ($star5 / $allFeedbacks) * 100 ,2) . '%' : 0;
        return $data;
    }

    static function getData($source) {
        $data = new  \stdClass();
        $data->id = $source->id;
        $data->course_id = $source->course_id;
        $data->course_title = $source->Course != null ? $source->Course->title : '';
        $data->content = $source->content; 
        $data->status = $source->status;
        $data->rate = $source->rate;
        $data->totals = self::getTotals($source->course_id);
        $data->image = User::getData($source->Creator)->image;
        $data->creator = $source->Creator->name;
        $data->created_at = \Carbon\Carbon::createFromTimeStamp(strtotime($source->created_at))->diffForHumans();
        return $data;
    }

}
