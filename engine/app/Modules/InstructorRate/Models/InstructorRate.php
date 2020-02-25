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

    public function Course(){
        return $this->belongsTo('App\Models\Course','course_id','id');
    }

    static function getOne($id){
        $source = self::NotDeleted()
            ->where('id', $id);
            
        return $source->first();
    }
}
