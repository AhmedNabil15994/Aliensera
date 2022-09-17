<?php

namespace App\Console\Commands;

use App\Models\Reminder;
use App\Models\Devices;
use Illuminate\Console\Command;

class SendReminderHourly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'A Crone Job To Send Lesson Reminders For Student Automatically';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $now = date('Y-m-d H:i:s');
        $reminders = Reminder::with('Lesson')->where('status',1)->where('finished_date',null)->where('reminder_date','<=',$now)->get();
        foreach($reminders as $reminder){
            $msg = 'Reminder Notification For Lesson '.$reminder->Lesson->title;

            $token = Devices::getDevicesBy([$reminder->student_id]);
            $token = reset($token);

            $fireBase = new \FireBase();
            $metaData = ['title' => "Reminder Alert !!!,Click To Go To Lesson", 'body' => $msg,];
            $myData = ['type' => 11 , 'id' => $reminder->lesson_id];
            $fireBase->send_android_notification($token,$metaData,$myData);

            $reminder->status = 2;
            $reminder->save();
        }
        return 1;
    }
}
