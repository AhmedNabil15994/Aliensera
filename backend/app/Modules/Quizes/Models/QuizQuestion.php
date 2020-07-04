<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model{

    use \TraitsFunc;

    protected $table = 'quiz_questions';
    protected $primaryKey = 'id';
    public $timestamps = false;


    public function Quiz(){
        return $this->belongsTo('App\Models\Quiz','quiz_id','id');
    }

    static function getOne($id){
        $source = self::NotDeleted()
            ->where('id', $id);

        if(IS_ADMIN == false){
            $source->whereHas('Quiz',function($courseQuery) {
                $courseQuery->whereHas('Course',function($whereHas){
                    $whereHas->where('instructor_id',USER_ID);
                });
            });
        }
        return $source->first();
    }

    static function dataList($quiz_id){
        $input = \Input::all();
        $source = self::NotDeleted()->where('quiz_id',$quiz_id)->orderBy('id','asc');
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
        $data->quiz_id = $source->quiz_id;
        $data->number_of_answers = $source->number_of_answers;
        $data->question = $source->question;
        $data->answer_a = $source->answer_a . ($source->correct_answer == 'a' ? '<br> (Correct Answer)' : '');
        $data->answer_b = $source->answer_b . ($source->correct_answer == 'b' ? '<br> (Correct Answer)' : '');
        $data->answer_c = $source->answer_c != null ? $source->answer_c . ($source->correct_answer == 'c' ? '<br> (Correct Answer)' : '') : '-----';
        $data->answer_d = $source->answer_d != null ? $source->answer_d . ($source->correct_answer == 'd' ? '<br> (Correct Answer)' : '') : '-----';
        $data->answer_e = $source->answer_e != null ? $source->answer_e . ($source->correct_answer == 'e' ? '<br> (Correct Answer)' : '') : '-----';
        $data->correct_answer = $source->correct_answer;
        $data->correctAnswer = 'Answer '.ucfirst($source->correct_answer);
        return $data;
    }

}
