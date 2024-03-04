<?php

namespace Server\Others\Observers;

use Server\Models\User;
use Server\Models\Admin;

class WithdrawalObserver extends BaseObserver
{

    public function created($withdrawal)
    {
        $user = User::where("id", $withdrawal->user_id)->first();
        
        $title = 'Pending Withdrawal From '.$user->first_name. " ". $user->last_name;

        $body = 'New withdrawal request of ' . $withdrawal->amount . ' ' . $withdrawal->currency;

        $admin = Admin::where('id', 1)->first();

        $this->sender->sendEmail([$admin->email], $body, $title);


        $subject = "Pending Withdrawal";
        
        $data = "Your withdrawal request has been received (".$withdrawal->amount." ".$withdrawal->currency."). it will be processed within 24 hours"; 

        $this->sender->sendEmail([$user->email], $data, $subject);
    }
}