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
        $data->file_url = $source->file_url;
        $data->width = $source->img_width;
        $data->height = $source->img_height;
        $data->read = $source->read;
        $data->created_by = $source->created_by;
        $data->created_at = \Helper::formatDateForDisplay(date('Y-m-d H:i:s',$source->created_at),true);
        return $data;
    }

    static function getUnreadCount($sender_id,$receiver_id,$chat_head_id){
        $created_by = '';
        if($sender_id == USER_ID){
            $created_by = $receiver_id;
        }else{
            $created_by = $sender_id;
        }

        $count = Chat::NotDeleted()->where('read',0)->where('chat_head_id',$chat_head_id)->where('created_by',$created_by)->count();
        return $count;
    }
}
