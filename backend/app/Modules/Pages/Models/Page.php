<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model{

    use \TraitsFunc;

    protected $table = 'pages';
    protected $primaryKey = 'id';
    public $timestamps = false;

    static function getGroupByType($type) {
        $groupId = 3;

        if($type == 1) {
            $groupId = 3;
        }
        if($type == 2) {
            $groupId = 4;
        }
        if($type == 3) {
            $groupId = 5;
        }

        return $groupId;
    }

    static function generateGroup($source) {
        $groupObj = new \stdClass();
        $groupObj->id = $source->id;
        $groupObj->title = $source->title;
        $groupObj->content = $source->content;
        return $groupObj;
    }

    static function getOne($id){
        return self::NotDeleted()->where('id', $id)
            ->first();
    }

    static function groupsList() {
        $input = \Input::all();

        $source = self::NotDeleted()->where(function ($query) use ($input) {
                    if (isset($input['title']) && !empty($input['title'])) {
                        $query->where('title', 'LIKE', '%' . $input['title'] . '%');
                    } 
                });

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
        $data->title = $source->title;
        $data->content = $source->content;
        return $data;
    }

    static function checkGroupByTitle($title, $notId = false){
        $dataObj = self::NotDeleted()->where('title', $title);

        if ($notId != false) {
            $dataObj->whereNotIn('id', [$notId]);
        }

        return $dataObj->first();
    }

}
