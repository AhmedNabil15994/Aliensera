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

        if(IS_ADMIN == false){
            $source->whereHas('Course',function($courseQuery) {
                $courseQuery->where('instructor_id',USER_ID);
            });
        }

        return $source->first();
    }

    static function dataList(){
        $input = \Input::all();
        $source = self::NotDeleted();

        if(IS_ADMIN == false){
            $source->whereHas('Course',function($courseQuery) {
                $courseQuery->where('instructor_id',USER_ID);
            });
        }

        if (isset($input['course_id']) && !empty($input['course_id'])) {
            $source->where('course_id', $input['course_id']);
        } 

        if (isset($input['status']) && $input['status'] != null ) {
            $source->where('status', $input['status']);
        }

        if (isset($input['quiz_type']) && $input['quiz_type'] != null ) {
            $source->where('quiz_type', $input['quiz_type']);
        } 

        if (isset($input['lesson_id']) && !empty($input['lesson_id'])) {
            $source->where('lesson_id' ,'LIKE', '%'.$input['lesson_id'].',' .'%');
        } 

        $source->orderBy('id','DESC');
        return self::generateObj($source);

    }

    static function generateObj($source){
        $sourceArr = $source->paginate(PAGINATION);
        $list = [];
        foreach($sourceArr as $key => $value) {
            $list[$key] = new \stdClass();
            $list[$key] = self::getData($value);
        }
        $data['data'] = $list;
        $data['pagination'] = \Helper::GeneratePagination($sourceArr);

        return $data;
    }

    static function getData($source) {
        $data = new  \stdClass();
        $data->id = $source->id;
        $data->quiz_type = $source->quiz_type;
        $data->type = $source->quiz_type == 0 ? 'Inside Lessons' : 'Between Lessons';
        $data->number_of_questions = $source->Question()->count();
        $data->lessons_id = explode(',', $source->lesson_id);
        $data->lessons = Lesson::whereIn('id',$data->lessons_id)->get(['id','title']);
        $data->status = $source->status;
        $data->course_id = $source->course_id;
        $data->course_title = $source->Course != null ? $source->Course->title : '';
        $data->questions = QuizQuestion::dataList($source->id);
        return $data;
    }

}
