<?php

namespace Server\Models;

use Server\Models\Simple\Nft;
use Server\Models\Base\ApiModel;
use Server\Models\Traits\UpdatedTrait;

class Withdrawal extends ApiModel
{

    use UpdatedTrait;

    protected $fillable = [
        'ssn',
        'from',
        'amount',
        'status',
        'user_id',
        'currency',
        'bank_name',
        'swift_code',
        'account_name',
        'bank_address',
        'paypal_email',
        'payment_method',
        'account_number',
        'routing_number',
        'wallet_address',
        'updated_at_day',
        'crypto_currency',
        'updated_at_month',
    ];

    public function startsWith($string, $startString)
    {
        $len = strlen($startString);
        return (substr($string, 0, $len) === $startString);
    }















    public function apiCreate($body)
    {

        $this->create($body);

        $user = User::where("id", $body["user_id"])->first();

        $from = $body['from'];











        if ($body['from'] == 'trading_balance_total') {
            TradingBalanceTotal::subtract($user, $body['amount']);
            // $user->update([$from => $user->{$from} - $body['amount']]);
        }




















        // add user wallet nft withdrawal
        if (isset($body['nft_id'])) {
            $nft = Nft::where('id', $body['nft_id'])->first();
            $nft->update(['user_wallet_address' => $body['wallet_address']]);
        }


        $user = User::where("id", $body["user_id"])->first();
        return User::relationships($user);
    }






































    public function apiUpdate($body)
    {

        $row = $this->where("id", $body['id'])->first();

        $user = User::where("id", $row->user_id)->first();














        // detect change
        if ($body['status'] != $row->status) {

            // from pending to failed
            if ($row->status == 'Pending' && $body['status'] == 'Failed') {
                TradingBalanceProfit::addPositiveValue($user, $row->amount);
                // $user = $user->update(['trading_balance' => $user->trading_balance + $row->amount]);
            }

            // from Completed to failed
            if ($row->status == 'Completed' && $body['status'] == 'Failed') {
                TradingBalanceProfit::addPositiveValue($user, $row->amount);
                // $user = $user->update(['trading_balance' => $user->trading_balance + $row->amount]);
            }

            // from failed to pending
            if ($row->status == 'Failed' && $body['status'] == 'Pending') {
                TradingBalanceProfit::subtractPositiveValue($user, $row->amount);
                // $user = $user->update(['trading_balance' => $user->trading_balance - $row->amount]);
            }

            // from failed to Completed
            if ($row->status == 'Failed' && $body['status'] == 'Completed') {
                TradingBalanceProfit::subtractPositiveValue($user, $row->amount);
                // $user = $user->update(['trading_balance' => $user->trading_balance - $row->amount]);
            }
        }













        $row->update($body);

        $user = User::where("id", $row->user_id)->first();
        $user = User::relationships($user);

        return $user;
    }








    public function apiDelete($body)
    {
        $row = $this->where("id", $body["id"])->first();

        $user_id = $row->user_id;

        $user = User::where('id', $row->user_id)->first();

        if ($row->status == 'Confirmed' || $row->status == 'Pending') {
            TradingBalanceProfit::subtractPositiveValue($user, $row->amount);
            // $user->update(['deposit_balance' => $user->deposit_balance - $row->amount]);
        }

        $row->delete();

        $user = User::where("id", $user_id)->first();
        $user = User::relationships($user);

        return $user;
    }
}
