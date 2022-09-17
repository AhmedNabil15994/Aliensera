<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reminder extends Model{

    use \TraitsFunc;

    protected $table = 'reminders';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = ['course_id','student_id','lesson_id','notes','reminder_date','status','finished','finished_date','created_at','updated_at'];


    public function Lesson(){
        return $this->belongsTo('App\Models\Lesson','lesson_id','id');
    }

    public function Course(){
        return $this->belongsTo('App\Models\Course','course_id','id');
    }

    static function getOne($id){
        $source = self::where('id', $id)->where('student_id',USER_ID)->first();

        return $source ? $source : null;
    }

    static function dataList($lesson_id=null) {
        $input = \Input::all();
        $source = self::where('student_id',USER_ID);
        if($lesson_id != null){
            $source->where('lesson_id',$lesson_id);
        }
        if (isset($input['course_id']) && !empty($input['course_id'])) {
            $source->where('course_id', $input['course_id']);
        } 
        if (isset($input['lesson_id']) && !empty($input['lesson_id'])) {
            $source->where('lesson_id', $input['lesson_id']);
        } 
        if (isset($input['status']) && !empty($input['status'])) {
            $source->where('status', $input['status']);
        } 
        if (isset($input['finished']) && !empty($input['finished'])) {
            $source->where('finished', $input['finished']);
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
        $data = new  \stdClass();
        $data->id = $source->id;
        $data->lesson_id = $source->lesson_id;
        $data->course_id = $source->course_id;
        $data->lesson = $source->Lesson != null ? $source->Lesson->title : '';
        $data->course = $source->Course != null ? $source->Course->title : '';
        $data->instructor = $source->Course != null ? $source->Course->Instructor->name : '';
        $data->student_id = $source->student_id;
        $data->status = $source->status;
        $data->statusText = self::getStatusText($source->status);
        $data->notes = $source->notes;
        $data->reminder_date = $source->reminder_date;
        $data->finished = $source->finished == 1 ? 'Yes' : 'No';
        $data->finished_date = $source->finished_date;
        $data->created_at = $source->created_at;
        $data->updated_at = $source->updated_at;
        return $data;
    }

    static function getStatusText($status){
        $text = '';
        if($status == 0){
            $text = 'Auto Created';
        }else if($status == 1){
            $text = 'Edited By Student';
        }else if($status == 2){
            $text = 'Reminder Sent To Student';
        }
        return $text;
    }
}
