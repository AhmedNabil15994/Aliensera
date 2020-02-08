<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstructorRate extends Model{

    use \TraitsFunc;

    protected $table = 'instructor_rates';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function Creator(){
        return $this->belongsTo('App\Models\User','created_by','id');
    }

    public function Instructor(){
        return $this->belongsTo('App\Models\User','instructor_id','id');
    }

    public function Course(){
        return $this->belongsTo('App\Models\Course','course_id','id');
    }

    static function getOne($id){
        $source = self::NotDeleted()
            ->where('id', $id);
            
        return $source->first();
    }

    static function dataList($instructor_id=null,$creator=null) {
        $input = \Input::all();
        $source = self::NotDeleted()->where('status',1);

        if (isset($instructor_id) && $instructor_id != null ) {
            $source->where('instructor_id', $instructor_id);
        } 
        if (isset($creator) && $creator != null ) {
            $source->where('created_by', $creator);
        } 

        if(IS_ADMIN == false){
            $source->where('instructor_id',USER_ID);
        }

        $source->orderBy('id','DESC');
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
        $data->rate = $source->rate;
        $data->image = User::getData($source->Creator)->image;
        $data->instructor_id = $source->instructor_id;
        $data->instructor = $source->Instructor->name;
        $data->student_id = $source->created_by;
        $data->creator = $source->Creator->name;
        $data->created_at = \Carbon\Carbon::createFromTimeStamp(strtotime($source->created_at))->diffForHumans();
        return $data;
    }

}
