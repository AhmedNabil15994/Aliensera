<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faculty extends Model {

    protected $table = 'faculties';
    protected $primary_key = 'id';
    public $timestamps = false;
    
    use \TraitsFunc;

    function University() {
        return self::belongsTo('App\Models\University', 'university_id', 'id');
    }

 	static function getOne($id) {
 		return self::NotDeleted()->where('status',1)
            ->find($id);
 	}

    static function dataList() {
        $input = \Input::all();
        
        $source = self::NotDeleted()->where('status',1);

        if (isset($input['title']) && $input['title'] != '') {
            $source->where('title', 'LIKE', '%' . $input['title'] . '%')
                    ->orWhere('description', 'LIKE', '%' . $input['title'] . '%');
        }
        if (isset($input['university_id']) && $input['university_id'] != '') {
            $source->where('university_id', $input['university_id']);
        }
        return self::generateObj($source);
    }

    static function generateObj($sourceArr) {
        $list = [];
        $sourceArr = $sourceArr->get();
        foreach ($sourceArr as $key => $value) {
            $list[$key] = new \stdClass();
            $list[$key] = self::getData($value);
        }
        return $list;
    }

    static function getData($source,$view=null) {
        $input = \Input::all();
        $facultyObj = new \stdClass();
        $facultyObj->id = $source->id;
        $facultyObj->title = $source->title;
        $facultyObj->description = $source->description;
        $facultyObj->status = $source->status;
        $facultyObj->years = $source->number_of_years;
        $facultyObj->university = $source->University->title;
        $facultyObj->university_id = $source->university_id;
        return $facultyObj;
    }

 }
