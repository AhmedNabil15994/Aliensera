<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessonQuestion extends Model{

    use \TraitsFunc;

    protected $table = 'lesson_questions';
    protected $primaryKey = 'id';
    public $timestamps = false;


    public function Lesson(){
        return $this->belongsTo('App\Models\Lesson','lesson_id','id');
    }

    static function getOne($id){
        return self::NotDeleted()
            ->where('id', $id)
            ->first();
    }

    static function dataList($lesson_id=null) {
        $input = \Input::all();
        $source = self::NotDeleted();

        if (isset($lesson_id) && !empty($lesson_id) ) {
            $source->where('lesson_id', $lesson_id);
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
        $data->question = $source->question;
        $data->answer_a = $source->answer_a . ($source->correct_answer == 'a' ? '<br> (Correct Answer)' : '');
        $data->answer_b = $source->answer_b . ($source->correct_answer == 'b' ? '<br> (Correct Answer)' : '');
        $data->answer_c = $source->answer_c . ($source->correct_answer == 'c' ? '<br> (Correct Answer)' : '');
        $data->answer_d = $source->answer_d . ($source->correct_answer == 'd' ? '<br> (Correct Answer)' : '');
        $data->correct_answer = $source->correct_answer;
        $data->correctAnswer = 'Answer '.ucfirst($source->correct_answer);
        return $data;
    }

}
