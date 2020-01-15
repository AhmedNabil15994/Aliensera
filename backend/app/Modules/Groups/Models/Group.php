<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model{

    use \TraitsFunc;

    protected $table = 'groups';
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

    static function getList() {
        $dataArr = self::NotDeleted()->get();

        $list = [];
        foreach($dataArr as $key => $value) {
            $list[$key] = new \stdClass();
            $list[$key]->id = $value->id;
            $list[$key]->title = $value->title;
        }

        return $list;
    }

    static function generateGroup($source) {
        $groupObj = new \stdClass();
        $groupObj->id = $source->id;
        $groupObj->title = $source->title;

        $groupObj->created_by = $source->created_by;
        $groupObj->created_at = \Helper::formatDateForDisplay($source->created_at);
        $groupObj->updated_by = $source->updated_by;
        $groupObj->updated_at = \Helper::formatDateForDisplay($source->updated_at);
        return $groupObj;
    }

    static function getOne($id){
        return self::NotDeleted()
            ->where('id', $id)
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
        return $data;
    }

    static function checkGroupByTitle($title, $notId = false){
        $dataObj = self::NotDeleted()
            ->where('title', $title);

        if ($notId != false) {
            $dataObj->whereNotIn('id', [$notId]);
        }

        return $dataObj->first();
    }

}
