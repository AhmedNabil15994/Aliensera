<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model{

    use \TraitsFunc;

    protected $table = 'accounts';
    protected $primaryKey = 'id';
    public $timestamps = false;

    static function getOne($id){
        return self::NotDeleted()
            ->where('id', $id)
            ->first();
    }

    static function dataList() {
        $input = \Input::all();

        $source = self::NotDeleted()->where(function ($query) use ($input) {
                    if (isset($input['name']) && !empty($input['name'])) {
                        $query->where('name', 'LIKE', '%' . $input['name'] . '%');
                    } 
                    if (isset($input['app_id']) && !empty($input['app_id'])) {
                        $query->where('app_id', 'LIKE', '%' . $input['app_id'] . '%');
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
        $data->name = $source->name;
        $data->app_id = $source->app_id;
        $data->client_id = $source->client_id;
        $data->client_secret = $source->client_secret;
        $data->access_token = $source->access_token;
        return $data;
    }

}
