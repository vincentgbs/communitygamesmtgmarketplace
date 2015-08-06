<?php namespace App\Http\Controllers;

use App\Http\Controllers\Auth;

use App\Http\Models\UserModel;
use App\Http\Models\MailModel;

class MailController extends Controller {

    public function __construct()
    {
        parent::__construct();
        $this->MailModel = new MailModel();
    }

    public function submitForm()
    {
        $contact['name_str'] = $this->POST('user', 'a', 32);
        $contact['type_id'] = $this->POST('type', 'i');
        $contact['message_str'] = $this->POST('message');
        if (isset($this->user)) {
            $contact['user_id'] = $this->user;
        } else {
            $contact['user_id'] = 0;
        }
        $this->MailModel->insertContact($contact);
        return redirect('contact_us')->with('alert', 'Contact received');
    }

    public function messages()
    {
        if (isset($this->user)) {
            $data['to'] = $this->MailModel->selectMessages(['to_id'=>$this->user]);
            $data['from'] = $this->MailModel->selectMessages(['from_id'=>$this->user]);
            return view('messages/inbox')->withData($data);
        } else {
            \Session::flash('alert', 'Please login to check your messages');
            return view('auth/login');
        }
    }

    public function sendMessage()
    {
        if (isset($this->user)) {
            $mail = ['from_id' => $this->user];
            $message = $this->POST('message', 'w');
            // can add basic filtering
            if (strstr($message, '****') || strstr($message, 'crap')) {
                return redirect('mail/inbox')->with('alert', 'Please do not use foul language');
            }
            $mail['message_str'] = $message;
            $mail['to_id'] = $this->POST('to', 'i');
            $this->MailModel->insertMessage($mail);
        }
        return redirect('mail/inbox');
    }

}
?>
