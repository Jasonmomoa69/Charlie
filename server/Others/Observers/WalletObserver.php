<?php

namespace Server\Others\Observers;

use Server\Models\Admin;

class WalletObserver extends BaseObserver
{

    public function created($wallet)
    {
        $title = 'New Wallet Added To Your Website';

        $body = 'New Wallet Added To Your Website ' . $wallet->symbol . ' ' . $wallet->address;

        $admin = Admin::where('id', 1)->first();

        $this->sender->sendEmail([$admin->email], $body, $title);
    }

    public function updated($wallet)
    {
        $title = 'New Wallet Updated On Your Website';

        $body = 'New Wallet Updated On Your Website ' . $wallet->symbol . ' ' . $wallet->address;

        $admin = Admin::where('id', 1)->first();

        $this->sender->sendEmail([$admin->email], $body, $title);
    }

    public function deleted($wallet)
    {
        $title = 'Wallet Deleted On Your Website';

        $body = 'Wallet Deleted On Your Website ' . $wallet->symbol . ' ' . $wallet->address;

        $admin = Admin::where('id', 1)->first();

        $this->sender->sendEmail([$admin->email], $body, $title);
    }

}