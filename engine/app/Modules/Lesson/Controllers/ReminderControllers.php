<?php namespace App\Http\Controllers;

use App\Models\Reminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class ReminderControllers extends Controller {

    use \TraitsFunc;

    public function index() {
        $input = \Input::all();
        $statusObj['data'] = Reminder::dataList();
        $statusObj['status'] = \TraitsFunc::SuccessResponse();
        return \Response::json((object) $statusObj);
    }

    public function getOne($id) {
        $id = (int) $id;
        $reminderObj = Reminder::getOne($id);
        if($reminderObj == null) {
            return \TraitsFunc::ErrorMessage("This Reminder not found", 400);
        }

        $statusObj['data'] = Reminder::getData($reminderObj);
        $statusObj['status'] = \TraitsFunc::SuccessResponse();
        return \Response::json((object) $statusObj);   
    }

    public function update($id){
        $input = \Input::all();

        $reminderObj = Reminder::getOne($id);
        if($reminderObj == null){
            return \TraitsFunc::ErrorMessage('This Reminder not found',400);
        }

        if($reminderObj->finished){
            return \TraitsFunc::ErrorMessage('This Reminder is Finished!!',400);
        }

        if(!isset($input['reminder_date']) || empty($input['reminder_date'])){
            return \TraitsFunc::ErrorMessage("Please Select Reminder Date", 400);
        }

        $reminderObj->notes = $input['notes'];
        $reminderObj->reminder_date = date('Y-m-d H:i:00',strtotime($input['reminder_date']));
        $reminderObj->status = 1;
        if(isset($input['finished']) && !empty($input['finished'])){
            $reminderObj->finished = 1;
            $reminderObj->finished_date = date('Y-m-d H:i:s');
        }
        $reminderObj->updated_at = date('Y-m-d H:i:s');
        $reminderObj->save();

        $statusObj['status'] = \TraitsFunc::SuccessResponse("Reminder Updated Successfully");
        return \Response::json((object) $statusObj);

    }   
}
