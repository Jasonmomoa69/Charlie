<?php

namespace Server\Others\Observers;

use Server\Models\Admin;
use Server\Models\Trader;

class TraderUserObserver extends BaseObserver
{

    public function created($copy)
    {

        $trader = Trader::where('id', $copy->trader_id)->first();

        $email = "New Copy Request For " . $trader->name;

        $admin = Admin::where('id', 1)->first();

        $this->sender->sendEmail([$admin->email], $email, $email);
    }
}
