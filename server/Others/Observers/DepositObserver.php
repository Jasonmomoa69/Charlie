<?php

namespace Server\Others\Observers;

use Server\Models\User;
use Server\Models\Admin;

class DepositObserver extends BaseObserver
{

    public function created($deposit)
    {

        $user = User::where("id", $deposit->user_id)->first();

        $title = 'Pending Deposit From ' . $user->first_name . " " . $user->last_name;

        $body = $deposit->amount . ' ' . $deposit->currency . ' to ' .  $deposit->wallet_address;

        // $body = 'Please standby to confirm deposit of ' . $deposit->amount . ' ' . $deposit->currency . ' (' . $deposit->amount_in_crypto . ' ' . $deposit->crypto_currency . ')  by user with id = ' . $deposit->user_id . '  to ' . $deposit->wallet_address;

        $admin = Admin::where('id', 1)->first();

        $this->sender->sendEmail([$admin->email], $body, $title);
    }
}
