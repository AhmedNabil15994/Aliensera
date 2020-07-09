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



    static function getOne($id){
        return self::NotDeleted()
            ->where('id', $id)
            ->first();
    }

    static function dataList() {
        $input = \Input::all();

        $source = self::NotDeleted();

        if (isset($input['code']) && !empty($input['code'])) {
            $source->where('code', $input['code']);
        }

        if (isset($input['instructor_id']) && !empty($input['instructor_id'])) {
            $source->where('instructor_id', $input['instructor_id']);
        }

        if (isset($input['course_id']) && !empty($input['course_id'])) {
            $source->where('course_id', $input['course_id']);
        }

        if (isset($input['student_id']) && !empty($input['student_id'])) {
            $source->where('student_id', $input['student_id']);
        } 

        if (isset($input['rate']) && !empty($input['rate'])) {
            $source->where('rate', $input['rate']);
        } 

        if(!IS_ADMIN){
            $source->where('instructor_id',USER_ID);
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

        $data['pagination'] = \Helper::GeneratePagination($sourceArr);
        $data['data'] = $list;

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
        return $data;
    }

}
