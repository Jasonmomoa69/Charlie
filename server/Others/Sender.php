<?php

namespace Server\Others;

// use Twilio\Rest\Client;

use Server\Models\Setting;
use Minishlink\WebPush\WebPush;
use PHPMailer\PHPMailer\PHPMailer;
use Minishlink\WebPush\Subscription;



class Sender
{

    public function sendEmail($emails, $body, $subject, $from = NULL, $password = NULL) 
    {


        $settings = Setting::where('id', 1)->first();



        $mailer = new PHPMailer;
        $mailer->isHTML(true);
        $mailer->Subject = $subject;






        if ($settings->mail_driver == "mail") {
            $mailer->isMail();
        }


        $sender_email = $from ?? getenv("MAIL_USER");
        $sender_password = $password ??  getenv("MAIL_PASS");


        if ($settings->mail_driver == "smtp") {

            $mailer->isSMTP();
            $mailer->SMTPAuth = true;
            $mailer->SMTPSecure = "ssl";
            $mailer->Host = "smtp.titan.email";
            $mailer->Port = 465;
            $mailer->SMTPDebug = 0;

            $mailer->Username = $sender_email;
            $mailer->Password = $sender_password;
        }



        $mailer->setFrom($sender_email, getenv("NODE_NAME"));



        $body = nl2br($body);


        if ($settings->mail_template == 'html1') {
            $renderer = new Renderer();
            $body = $renderer->render('assets/email/html1.html', ['title' => $subject, 'body' => $body]);
        }


        if ($settings->mail_template == 'html2') {
            $renderer = new Renderer();
            $body = $renderer->render('assets/email/html2.html', ['title' => $subject, 'body' => $body]);
        }


        if ($settings->mail_template == 'html3') {
            $renderer = new Renderer();
            $body = $renderer->render('assets/email/html3.html', ['title' => $subject, 'body' => $body]);
        }


        if ($settings->mail_template == 'html4') {
            $renderer = new Renderer();
            $body = $renderer->render('assets/email/html4.html', ['title' => $subject, 'body' => $body]);
        }

        $mailer->Body = $body;

        try {

            foreach ($emails as $email) {
                $mailer->addAddress($email);
            }

            $sent = $mailer->send();

            if ($sent && $settings->mail_driver == "smtp") {
                $imap_folder = '{imap.titan.email:993/imap/ssl/novalidate-cert}Sent';
            
                $imap_connection = imap_open($imap_folder, $sender_email, $sender_password);
    
                imap_append($imap_connection, $imap_folder, 
                    "From: {$sender_email}\r\n"
                    . "To: {$emails[0]}\r\n"
                    . "Subject: {$subject}\r\n"
                    . "Content-Type: text/html\r\n"
                    . "\r\n"
                    . "{$body}\r\n");
            }

            return $sent;
        } catch (\Exception $errors) {
            return false;
        }
    }




    public function sendPush(array $subscriptions, string $body, string $subject)
    {

        $settings = Setting::where('id', 1)->first();

        $auth = [
            'VAPID' => [
                'subject' => 'mailto:' . getenv("MAIL_USER"),
                'publicKey' => $settings->public_vapid_key,
                'privateKey' => $settings->private_vapid_key,
            ],
        ];

        $notification = ['subject' => $subject, 'body' => $body];

        $webPush = new WebPush($auth);

        try {
            // add push subscriptions
            foreach ($subscriptions as $subscription) {
                $subscription = (array) json_decode($subscription);
                // var_dump($subscription);
                $subscription['keys'] = (array) $subscription['keys'];
                $subscription = Subscription::create($subscription);
                $webPush->sendOneNotification($subscription, json_encode($notification));
            }

            // send push subscriptons
            foreach ($webPush->flush() as $report) {
            }

            return true;
        } catch (\Exception $errors) {
            return false;
        }
    }




    // public function sendSms(array $numbers, string $body)
    // {
    //     $sid = $_ENV['TWILIO_SID'];
    //     $token = $_ENV['TWILIO_TOKEN']; 
    //     $from_number = $_ENV['TWILIO_NUMBER'];

    //     $client = new Client($sid, $token);

    //     foreach ($numbers as $number) {
    //         $client->messages->create($number, [
    //             'from' => $from_number,
    //             'body' => $body
    //             // 'statusCallback' => 'url'
    //         ]);
    //     }

    //     // all twillio messages get queued 
    //     // so no instant response on status
    //     // response is sent via callback url
    //     // so all messages return successful by default

    //     return true;
    // }
}
