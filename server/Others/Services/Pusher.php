<?php

namespace Server\Others\Services;

class Pusher
{

    public static function push(array $subscriptions, string $body, string $subject)
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

}
