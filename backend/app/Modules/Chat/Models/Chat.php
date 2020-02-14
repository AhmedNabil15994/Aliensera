<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model{

    use \TraitsFunc;

    protected $table = 'chat';
    protected $primaryKey = 'id';
    public $timestamps = false;

    static function dataList($chat_head_id=null,$orderBy='DESC') {
        $input = \Input::all();

        $source = self::NotDeleted()->orderBy('created_at',$orderBy);
        if($chat_head_id != null){
            $source->where('chat_head_id',$chat_head_id);
        }
        return self::generateObj($source);
    }

    static function generateObj($source){
        $sourceArr = $source->get();

        $list = [];
        foreach($sourceArr as $key => $value) {
            $list[$key] = new \stdClass();
            $list[$key] = self::getData($value);
        }
        return $list;
    }

    static function getData($source) {
        $data = new \stdClass();
        $data->id = $source->id;
        $data->message_type = $source->message_type;
        $data->message = $source->message;
        $data->width = $source->img_width;
        $data->height = $source->img_height;
        $data->read = $source->read;
        $data->created_by = $source->created_by;
        $data->created_at = \Carbon\Carbon::createFromTimeStamp(strtotime($source->created_at))->diffForHumans();
        return $data;
    }
}
