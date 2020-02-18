<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatHead extends Model{

    use \TraitsFunc;

    protected $table = 'chat_heads';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    function Sender(){
        return $this->belongsTo('App\Models\User', 'sender_id');
    }

    function Chats(){
        return $this->hasMany('App\Models\Chat', 'chat_head_id','id');
    }

    function Receiver(){
        return $this->belongsTo('App\Models\User', 'receiver_id');
    }

    static function getOne($id){
        $source = self::where(function($whereQuery){
            $whereQuery->where('sender_id',USER_ID)->orWhere('receiver_id',USER_ID);
        })->where('id',$id);
        return $source->first();
    }

    static function dataList() {
        $input = \Input::all();

        $source = self::where(function($whereQuery){
            $whereQuery->where('sender_id',USER_ID)->orWhere('receiver_id',USER_ID);
        })->orderBy('created_at','DESC');

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

    static function getData($source,$orderBy='DESC') {
        $data = new \stdClass();
        $data->id = $source->id;
        $data->sender_id = $source->sender_id;
        $data->sender = $source->Sender->name;
        $data->sender_image = User::getData($source->Sender)->image;
        $data->receiver_id = $source->receiver_id;
        $data->receiver = $source->Receiver->name;
        $data->receiver_image = User::getData($source->Receiver)->image;
        $data->unreadCount = Chat::getUnreadCount($source->sender_id,$source->receiver_id,$source->id);
        $data->messages = Chat::dataList($source->id,$orderBy);
        $data->created_at = \Helper::formatDateForDisplay(date('Y-m-d H:i:s',$source->created_at));
        return $data;
    }
}
