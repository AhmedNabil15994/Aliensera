<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model{

    use \TraitsFunc;

    protected $table = 'independent_quiz';
    protected $primaryKey = 'id';
    public $timestamps = false;


    public function Course(){
        return $this->belongsTo('App\Models\Course','course_id','id');
    }

    public function Question(){
        return $this->hasMany('App\Models\QuizQuestion','quiz_id','id');
    }

    static function getOne($id){
        $source = self::NotDeleted()
            ->where('id', $id);

        return $source->first();
    }

    static function dataList($course_id=null,$lesson_id=null){
        $input = \Input::all();
        $source = self::NotDeleted();

        if ($course_id != null) {
            $source->where('course_id', $course_id);
        } 

        if ($lesson_id != null) {
            $source->where('lesson_id' ,'LIKE', '%'.$lesson_id.',' .'%')->orWhere('lesson_id' ,'LIKE', '%' .','.$lesson_id .'%');
        } 

        $source->where('status',1)->orderBy('id','DESC');
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
        $data->quiz_type = $source->quiz_type;
        $data->type = $source->quiz_type == 0 ? 'Inside Lessons' : 'Between Lessons';
        $data->number_of_questions = $source->Question()->count();
        $data->lessons = Lesson::whereIn('id',explode(',', $source->lesson_id))->get(['id','title']);
        $data->status = $source->status;
        $data->course_id = $source->course_id;
        $data->course_title = $source->Course != null ? $source->Course->title : '';
        $data->questions = QuizQuestion::dataList($source->id);
        return $data;
    }

}
