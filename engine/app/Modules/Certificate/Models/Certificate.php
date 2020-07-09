<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model{

    use \TraitsFunc;

    protected $table = 'certificates';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function Course(){
        return $this->belongsTo('App\Models\Course','course_id','id');
    }

    public function Instructor(){
        return $this->belongsTo('App\Models\User','instructor_id','id');
    }

    public function Student(){
        return $this->belongsTo('App\Models\User','student_id','id');
    }



    static function getOne($code){
        return self::NotDeleted()
            ->where('code', $code)
            ->where('student_id',USER_ID)
            ->first();
    }

    static function dataList() {
        $input = \Input::all();

        $source = self::NotDeleted()->where('student_id',USER_ID);

        if (isset($input['code']) && !empty($input['code'])) {
            $source->where('code', $input['code']);
        }

        if (isset($input['instructor_id']) && !empty($input['instructor_id'])) {
            $source->where('instructor_id', $input['instructor_id']);
        }

        if (isset($input['course_id']) && !empty($input['course_id'])) {
            $source->where('course_id', $input['course_id']);
        }

        if (isset($input['rate']) && !empty($input['rate'])) {
            $source->where('rate', $input['rate']);
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
        $data->code = $source->code;
        $data->rate = $source->rate;
        $data->instructor_id = $source->instructor_id;
        $data->instructor = $source->Instructor != null ? $source->Instructor->name : '';
        $data->student_id = $source->student_id;
        $data->student = $source->Student != null ? $source->Student->name : '';
        $data->course_id = $source->course_id;
        $data->course = $source->Course != null ? $source->Course->title : '';
        $data->created_at = $source->created_at;
        $data->creat_date = \Helper::formatDateForDisplay($source->created_at);
        return $data;
    }

}
