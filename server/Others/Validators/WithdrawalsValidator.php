<?php

namespace Server\Others\Validators;

use Server\Models\Nft;
use Server\Models\User;
use Server\Models\Setting;

class WithdrawalsValidator extends ApiValidator
{

    public function apiCreate($body)
    {


        if (!isset($body['from'])) {
            return ["from is required"];
        }

        if (!isset($body['user_id'])) {
            return ["user id is required"];
        }

        if (!isset($body['nft_id']) && !isset($body['amount'])) {
            return ["amount is required"];
        }

        $user = User::where('id', $body['user_id'])->first();

        if (!$user) {
            return ["user not found"];
        }

        $settings = Setting::where('id', 1)->first();

        if ($settings->withdrawal_code != "disabled") {

            if (!isset($body['withdrawal_code'])) {
                return [$settings->withdrawal_code_label . " is required"];
            }

            if ($body['withdrawal_code'] != $user->withdrawal_code) {
                return ["invalid " . $settings->withdrawal_code_label . " contact " . $settings->contact_email . " for code recovery"];
            }
        }


        if ($user->account_type == "Demo") {
            return ['please note you can not withdraw from a demo account'];
        }


        $from = $body['from'];

        $balance = $user->{$from};

        if ($body['amount'] > $balance) {
            return ['insufficient funds'];
        }

        return [];
    }
}
