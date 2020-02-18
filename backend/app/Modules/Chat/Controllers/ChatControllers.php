<?php namespace App\Http\Controllers;

use App\Models\ChatHead;
use App\Models\Chat;
use App\Models\User;
use App\Models\Variable;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Pusher\Pusher;

class ChatControllers extends Controller {

    use \TraitsFunc;

    public function index() {
    	$dataList['data'] = User::getData(User::getOne(USER_ID));
        $dataList['messages'] = ChatHead::dataList();
        return view('Chat.Views.chat')->with('data', (Object) $dataList);
    }

    public function getOne($id) {
        $chatHeadObj = ChatHead::getOne($id);
        if($chatHeadObj == null){
            return \TraitsFunc::ErrorMessage("This Chat Not Found", 400);
        }
        $chatHeadObj->Chats()->where('created_by','!=',USER_ID)->where('read',0)->update(['read'=>1]);
        $statusObj['messages'] = ChatHead::getData($chatHeadObj,'ASC');
        $statusObj['status'] = \TraitsFunc::SuccessResponse("Load Data Success");
        return \Response::json((object) $statusObj);
    }

    public function chatWith($id){

        $userObj = User::NotDeleted()
            ->with('Profile')
            ->whereHas('Profile', function() {})
            ->find($id);

        if($userObj == null) {
            \Session::flash('error', "Sorry you cant edit this user");
            return redirect()->back();
        }

        $chatHeadObj = ChatHead::where(function($whereQuery) use ($id){
            $whereQuery->where('sender_id',USER_ID)->where('receiver_id',$id);
        })->orWhere(function($orWhereQuery) use ($id){
            $orWhereQuery->where('receiver_id',USER_ID)->where('sender_id',$id);
        })->first();

        if($chatHeadObj == null){
            $chatHeadObj = new ChatHead;
            $chatHeadObj->sender_id = USER_ID;
            $chatHeadObj->receiver_id = $id;
            $chatHeadObj->created_at = time();
            $chatHeadObj->save();
        }

        $messageObj = new Chat;
        $messageObj->chat_head_id = $chatHeadObj->id;
        $messageObj->message_type = 0;
        $messageObj->message = 'Hello';
        $messageObj->file_url = null;
        $messageObj->img_width = 0;
        $messageObj->img_height = 0;
        $messageObj->read = 0;
        $messageObj->created_at = time();
        $messageObj->created_by = USER_ID;
        $messageObj->save();

        $senderObj = User::getData(User::getOne(USER_ID));
        $receiverObj = User::getData($userObj);

        $messageObj->sender_image = $senderObj->image;
        $messageObj->sender_name = $senderObj->name;
        $messageObj->receiver_image = $receiverObj->image;
        $messageObj->receiver_name = $receiverObj->name;
        $messageObj->time = \Helper::formatDateForDisplay(date('Y-m-d H:i:s',$messageObj->created_at),true);

        $PUSHER_APP_ID = Variable::getVar('PUSHER_APP_ID');
        $PUSHER_APP_KEY = Variable::getVar('PUSHER_APP_KEY');
        $PUSHER_APP_SECRET = Variable::getVar('PUSHER_APP_SECRET');
        $CLUSTER = Variable::getVar('CLUSTER');
        
        $options = [
            'cluster' => $CLUSTER,
            'useTLS' => true,
        ];

        $pusher = new Pusher(
            $PUSHER_APP_KEY,
            $PUSHER_APP_SECRET,
            $PUSHER_APP_ID,
            $options
        );

        if($chatHeadObj->sender_id == USER_ID){
            $data= ['from'=>USER_ID,'to'=>$chatHeadObj->receiver_id,'msg'=>$messageObj];
            $pusher->trigger('receiver-'.$chatHeadObj->receiver_id,'my-event',$data);
        }elseif($chatHeadObj->receiver_id == USER_ID){
            $data= ['from'=>$chatHeadObj->receiver_id,'to'=>USER_ID,'msg'=>$messageObj];
            $pusher->trigger('receiver-'.$chatHeadObj->sender_id,'my-event',$data);
        }

        return redirect()->to('/messages');
    }

    public function newMessage($id){
        $chatHeadObj = ChatHead::getOne($id);
        if($chatHeadObj == null){
            return \TraitsFunc::ErrorMessage("This Chat Not Found", 400);
        }

        $input = \Input::all();

        $file_url = null;
        if($input['message_type'] != 0){
            $file_url = $input['file_url'];
        }   

        $messageObj = new Chat;
        $messageObj->chat_head_id = $id;
        $messageObj->message_type = $input['message_type'];
        $messageObj->message = $input['message'];
        $messageObj->file_url = $file_url;
        $messageObj->img_width = $input['img_width'];
        $messageObj->img_height = $input['img_height'];
        $messageObj->read = 0;
        $messageObj->created_at = time();
        $messageObj->created_by = USER_ID;
        $messageObj->save();

        $senderObj = User::getData(User::getOne(USER_ID));
        $receiverObj = User::getData(User::getOne($chatHeadObj->receiver_id));

        Chat::where('chat_head_id',$id)->where('created_by','!=',USER_ID)->where('read',0)->update(['read'=>1]);

        $messageObj->sender_image = $senderObj->image;
        $messageObj->sender_name = $senderObj->name;
        $messageObj->receiver_image = $receiverObj->image;
        $messageObj->receiver_name = $receiverObj->name;
        $messageObj->time = \Helper::formatDateForDisplay(date('Y-m-d H:i:s',$messageObj->created_at),true);

        $PUSHER_APP_ID = Variable::getVar('PUSHER_APP_ID');
        $PUSHER_APP_KEY = Variable::getVar('PUSHER_APP_KEY');
        $PUSHER_APP_SECRET = Variable::getVar('PUSHER_APP_SECRET');
        $CLUSTER = Variable::getVar('CLUSTER');
        
        $options = [
            'cluster' => $CLUSTER,
            'useTLS' => true,
        ];

        $pusher = new Pusher(
            $PUSHER_APP_KEY,
            $PUSHER_APP_SECRET,
            $PUSHER_APP_ID,
            $options
        );

        if($chatHeadObj->sender_id == USER_ID){
            $data= ['from'=>USER_ID,'to'=>$chatHeadObj->receiver_id,'msg'=>$messageObj];
            $pusher->trigger('receiver-'.$chatHeadObj->receiver_id,'my-event',$data);
        }elseif($chatHeadObj->receiver_id == USER_ID){
            $data= ['from'=>$chatHeadObj->receiver_id,'to'=>USER_ID,'msg'=>$messageObj];
            $pusher->trigger('receiver-'.$chatHeadObj->sender_id,'my-event',$data);
        }

        
        $statusObj['messages'] = $messageObj;
        $statusObj['status'] = \TraitsFunc::SuccessResponse("Load Data Success");
        return \Response::json((object) $statusObj);
    }

    public function uploadAttachment(Request $request){
        if ($request->hasFile('attachment')) {
            $image = $request->file('attachment');
            $fileName = \ImagesHelper::UploadChatAttachment('chat', $image);
            if($image == false || $fileName == false){
                return 'error';
            }
            $url = asset('uploads/chat/'.$fileName[0]);

            $imageW = -1;
            $imageH = -1;
            if($fileName[2] == 'image'){
                $info = getimagesize($url);
                $imageW = $info[0];
                $imageH = $info[1];
            }
            return [$fileName[1],$url,$fileName[2],$imageW,$imageH];
        }
    }

}
