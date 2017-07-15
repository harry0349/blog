<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Jobs\SendReminderEmail;

class MailController extends Controller
{
    //发送提醒邮件
    public function sendReminderEmail(Request $request,$id){
        $user = User::findOrFail($id);
        dd($user);
        $this->dispatch(new SendReminderEmail($user));
    }
}
