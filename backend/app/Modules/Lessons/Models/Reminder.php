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
        $source = self::NotDeleted()
            ->where('id', $id);

        return $source->first();
    }

    static function dataList($lesson_id=null) {
        $input = \Input::all();
        $source = self::NotDeleted();

        if(IS_ADMIN == false){
            $source->whereHas('Course',function($courseQuery) {
                $courseQuery->where('instructor_id',USER_ID);
            });
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

        return (object) $list;
    }

    static function getData($source) {
        $data = new  \stdClass();
        $data->id = $source->id;
        $data->lesson_id = $source->lesson_id;
        $data->course_id = $source->course_id;
        $data->student_id = $source->student_id;
        $data->status = $source->status;
        $data->notes = $source->notes;
        $data->reminder_date = $source->reminder_date;
        $data->finished = $source->finished;
        $data->finished_date = $source->finished_date;
        $data->created_at = $source->created_at;
        $data->updated_at = $source->updated_at;
        return $data;
    }

}
