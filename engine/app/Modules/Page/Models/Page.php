<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model{

    use \TraitsFunc;

    protected $table = 'pages';
    protected $primaryKey = 'id';
    public $timestamps = false;

    static function getOne($id){
        return self::NotDeleted()->where('id', $id)
            ->first();
    }

    static function getOneByTitle($title){
        return self::NotDeleted()->where('title', $title)
            ->first();
    }

    static function getData($source){
        $data = new \stdClass();
        $data->title = $source->title;
        $data->content = $source->content;
        return $data;
    }

}
