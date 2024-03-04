<?php

namespace Server\Others\Observers;

use Server\Models\User;
use Server\Models\Admin;

class TradeObserver extends BaseObserver
{

    public function created($trade)
    {

        if ($trade->trader_id == null) {

            $user = User::where('id', $trade->user_id)->first();

            $title =  $user->first_name . " " . $user->last_name . " just opened a trade (" . $trade->time . " Minutes)";

            $body = "You can move the market from control panel to control the outcome of this trade";

            $admin = Admin::where('id', 1)->first();

            $this->sender->sendEmail([$admin->email], $body, $title);
        }
    }
}
