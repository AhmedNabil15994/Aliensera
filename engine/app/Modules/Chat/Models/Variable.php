<?php namespace App\Models;

use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Model;

class  Variable extends Model{

    use \TraitsFunc;

    protected $table = 'variables';
    protected $primaryKey = 'id';
    public $timestamps = false;

    static function getOne($id) {
        return self::NotDeleted()
            ->find($id);
    }

    static function variableList() {
        $input = \Input::all();

        $source = self::NotDeleted()
            ->where(function ($query) use ($input) {
                if (isset($input['key']) && !empty($input['key'])) {
                    $query->where('var_key', 'LIKE', '%' . $input['key'] . '%');
                }
            });

        if (isset($input['value']) && !empty($input['value'])) {
            $source->where('var_value', 'LIKE', '%' . $input['value'] . '%');
        }

        return self::getObj($source);
    }

    static function getObj($source) {
        $sourceArr = $source->paginate(PAGINATION);

        $list = [];
        foreach ($sourceArr as $key => $value) {
            $list[$key] = new \stdClass();
            $list[$key] = self::getData($value);
        }

        $data['data'] = $list;
        $data['pagination'] = \Helper::GeneratePagination($sourceArr);
        return $data;
    }

    static function getData($source) {
        $variableObj = new \stdClass();
        $variableObj->id = $source->id;
        $variableObj->key = $source->var_key;
        $variableObj->value = $source->var_value;

        return $variableObj;
    }

    static function getVar($key) {
        $variableObj = self::NotDeleted()->where('var_key',$key)->first();
        return $variableObj != null ? $variableObj->var_value : '';
    }

}
