<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentVideoDuration extends Model{

    use \TraitsFunc;

    protected $table = 'student_video_duration';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function Lesson(){
        return $this->belongsTo('App\Models\Lesson','lesson_id','id');
    }

    public function Course(){
        return $this->belongsTo('App\Models\Course','course_id','id');
    }

    public function Student(){
        return $this->hasMany('App\Models\User','student_id','id');
    }

    static function getOne($id){
        $source = self::NotDeleted()
            ->where('id', $id);

        return $source->first();
    }

}
