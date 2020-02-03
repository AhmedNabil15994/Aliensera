<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class University extends Model {

    protected $table = 'universities';
    protected $primary_key = 'id';
    public $timestamps = false;
    
    use \TraitsFunc;

 	
 	static function getOne($id) {
        return self::NotDeleted()->where('status',1)
            ->where('id',$id)->first();
    }

    static function dataList() {
        $input = \Input::all();
        $source = self::NotDeleted()->where('status',1);
        
        if (isset($input['title']) && $input['title'] != '') {
            $source->where('title', 'LIKE', '%' . $input['title'] . '%')
                    ->orWhere('description', 'LIKE', '%' . $input['title'] . '%');
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
        
        $universityObj = new \stdClass();
        $universityObj->id = $source->id;
        $universityObj->title = $source->title;
        $universityObj->description = $source->description;
        $universityObj->status = $source->status;
        return $universityObj;
    }


 }
