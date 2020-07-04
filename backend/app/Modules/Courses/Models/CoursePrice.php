<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoursePrice extends Model{

    use \TraitsFunc;

    protected $table = 'course_price';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function Course(){
        return $this->belongsTo('App\Models\Course','course_id','id');
    }

    public function Instructor(){
        return $this->belongsTo('App\Models\User','instructor_id','id');
    }

    static function getOne($id){
        $source = self::NotDeleted()
            ->where('id', $id);

        return $source->first();
    }

    static function getData($source) {
        $data = new  \stdClass();
        $data->id = $source->id;
        $data->course_id = $source->course_id;
        $data->instructor_id = $source->instructor_id;
        $data->start_date = $source->start_date;
        $data->end_date = $source->end_date;
        $data->course_duration = $source->course_duration;
        $data->upload_space = $source->upload_space;
        $data->upload_cost = $source->upload_cost;
        $data->approval_number = $source->approval_number;
        $data->approval_cost = $source->approval_cost;
        $data->updated_start_date = $source->updated_start_date != null && in_array($source->Course->status, [3,5]) ? $source->updated_start_date : $source->start_date ;
        $data->updated_end_date = $source->updated_end_date != null && in_array($source->Course->status, [3,5]) ? $source->updated_end_date : $source->end_date;
        $data->updated_course_duration = $source->updated_course_duration != null && in_array($source->Course->status, [3,5]) ? abs($source->updated_course_duration - $source->course_duration)  : $source->course_duration;
        $data->updated_upload_space = $source->updated_upload_space != null && in_array($source->Course->status, [3,5]) ? abs($source->updated_upload_space - $source->upload_space) : $source->upload_space ;
        $data->updated_upload_cost = $source->updated_upload_cost != null && in_array($source->Course->status, [3,5]) ? abs($source->updated_upload_cost - $source->upload_cost) : $source->upload_cost ;
        $data->updated_approval_number = $source->updated_approval_number != null && in_array($source->Course->status, [3,5]) ? abs($source->updated_approval_number - $source->approval_number) : $source->approval_number ;
        $data->updated_approval_cost = $source->updated_approval_cost != null && in_array($source->Course->status, [3,5]) ? abs($source->updated_approval_cost - $source->approval_cost) : $source->approval_cost ;

        $data->created_at = \Carbon\Carbon::createFromTimeStamp(strtotime($source->created_at))->diffForHumans();
        return $data;
    }
}
