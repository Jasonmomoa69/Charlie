<?php

namespace Server\Others\Observers;

use Server\Models\Admin;
use Server\Models\Setting;
use Server\Others\Renderer;
use Server\Models\Simple\RefdUser;


class UserObserver extends BaseObserver
{

    public $renderer;



    public function __construct()
    {
        parent::__construct();
        $this->renderer = new Renderer;
    }


    public function updated($user)
    {

        $changes = $user->getChanges();

        if (isset($changes['photo_front_view']) && isset($changes['photo_front_view'])) {
            $admin = Admin::where('id', 1)->first();

            $content = "ID CARD UPLOADED OR DELETED - " . $user->first_name . " " . $user->last_name;

            $this->sender->sendEmail([$admin->email], $content, $content);
        }
    }






    public function created($user)
    {

        session_regenerate_id();

        $session_id = session_id();

        $data = [
            'user_id' => $user->id,
            'session_id' => $session_id,
            'date' => date("l jS \of F Y h:i:s A"),
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT']
        ];


        $_SESSION['user'] = $data;




        $settings = Setting::where('id', 1)->first();

        if ($settings->email_verification != "disabled") {
            $body = 'Your Verification Pin is ' . $user->pin;
            $title = 'Verification Pin';
            $this->sender->sendEmail([$user->email], $body, $title);
        }



        // will only send if no verification is required -- email, idcard, address
        if ($user->welcome_email_sent === 'No') {
            if ($user->all_required_verifications === "Completed") {
                if (strlen($settings->welcome_mail)) {
                    $sent = $this->sender->sendEmail([$user->email], $settings->welcome_mail, "Welcome");
                    if ($sent) {
                        $user->update(['welcome_email_sent' => 'Yes']);
                    }
                }
            }
        }




        if (getenv("NODE_ENV") == "production") {

            $row = RefdUser::where('id', $user->id)->first()->toArray();

            $data = $this->renderer->render('/assets/email/table.twig', ['row' => $row]);

            $title = "New User - " . $user->first_name . " " . $user->last_name;

            $admin = Admin::where('id', 1)->first();

            $this->sender->sendEmail([$admin->email], $data, $title);
        }
    }
}
