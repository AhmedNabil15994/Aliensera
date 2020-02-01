<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faculty extends Model{

    use \TraitsFunc;

    protected $table = 'faculties';
    protected $primaryKey = 'id';
    public $timestamps = false;

    function University(){
        return $this->belongsTo('App\Models\University', 'university_id');
    }

    static function getOne($id){
        return self::NotDeleted()
            ->where('id', $id)
            ->first();
    }

    static function getOneByUniversity($id,$university_id){
        return self::NotDeleted()->where('id',$id)->where('status',1)->where('university_id',$university_id)->first();
    }

    static function dataList() {
        $input = \Input::all();

        $source = self::NotDeleted();

        if (isset($input['title']) && !empty($input['title'])) {
            $source->where('title', 'LIKE', '%' . $input['title'] . '%');
        } 

        if (isset($input['university_id']) && !empty($input['university_id'])) {
            $source->where('university_id', $input['university_id']);
        } 

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
        $data->description = $source->description;
        $data->years = $source->number_of_years;
        $data->university_id = $source->university_id;
        $data->university = $source->University != null ? $source->University->title : '';
        $data->status = $source->status;
        return $data;
    }

}
