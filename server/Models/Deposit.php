<?php

namespace Server\Models;

use Server\Others\Sender;
use Server\Models\Simple\Nft;
use Server\Models\Base\ApiModel;
use Server\Models\Traits\UpdatedTrait;
use Illuminate\Database\Eloquent\SoftDeletes;


class Deposit extends ApiModel
{
    use SoftDeletes;
    use UpdatedTrait;

    protected $fillable = [
        'to',
        'tag',
        'link',
        'code',
        'proof',
        'nft_id',
        'symbol',
        'amount',
        'status',
        'network',
        'comment',
        'user_id',
        'currency',
        'timestamp',
        'wallet_id',
        'payment_method',
        'updated_at_day',
        'wallet_address',
        'investment_term',
        'crypto_currency',
        'updated_at_month',
        'amount_in_crypto',
        'created_by_admin',
        'transaction_hash',
    ];

    public function getCommentAttribute($row)
    {

        if ($row) {
            return $row;
        }

        if ($this->to == 1) {
            return 'Trading';
        }

        if ($this->to == 2) {
            return 'Bitcoin Mining';
        }

        if ($this->to == 3) {
            return 'Ethereum Mining';
        }

        if ($this->to == 4) {
            return 'Dogecoin Mining';
        }

        if ($this->to == 5) {
            return 'Binance Coin Mining';
        }

        if ($this->to == 6) {
            return 'Cosmos Mining';
        }
    }











    public function apiCreate($body)
    {

        if (isset($body['timestamp'])) {
            $body['timestamp'] = strval($body['timestamp']);
        }


        $row = $this->create($body);
        $user = User::where("id", $row->user_id)->first();


        if (isset($body['status'])) {
            if ($body['status'] == 'Confirmed') {
                TradingBalanceDeposit::add($user, $body['amount']);
            }
        }

        $user = User::where("id", $row->user_id)->first();
        $user = User::relationships($user);

        return $user;
    }

    public function apiUpdate($body)
    {

        $deposit = $this->where("id", $body['id'])->first();

        $user = User::where("id", $deposit->user_id)->first();

        // check for change
        if ($body['status'] != $deposit->status && $deposit->to == 1) {

            // from pending to confirmed
            if ($deposit->status == 'Pending' && $body['status'] == 'Confirmed') {
                TradingBalanceDeposit::add($user, $deposit->amount);
                // $user->update(['deposit_balance' => $user->deposit_balance + $deposit->amount]);
            }

            // from pending to failed

            // from confirmed to pending
            if ($deposit->status == 'Confirmed' && $body['status'] == 'Pending') {
                TradingBalanceDeposit::subtract($user, $deposit->amount);
                // $user->update(['deposit_balance' => $user->deposit_balance - $deposit->amount]);
            }

            // from confirmed to failed
            if ($deposit->status == 'Confirmed' && $body['status'] == 'Failed') {
                TradingBalanceDeposit::subtract($user, $deposit->amount);
                // $user->update(['deposit_balance' => $user->deposit_balance - $deposit->amount]);
            }

            // from failed to pending

            // from failed to confirmed
            if ($deposit->status == 'Failed' && $body['status'] == 'Confirmed') {
                TradingBalanceDeposit::add($user, $deposit->amount);
                // $user->update(['deposit_balance' => $user->deposit_balance + $deposit->amount]);
            }
        }


        if ($deposit->nft_id != NULL) {
            $nft = Nft::where('id', $deposit->nft_id)->first();
            $nft->update(['user_id' => $deposit->user_id]);
        }


        // send payment recieved confirmation
        if ($deposit->status == 'Pending' && $body['status'] == 'Confirmed') {
            $sender = new Sender();

            $subject = "Deposit Confirmed";

            $data = "Your deposit of " . $deposit->amount . " " . $user->currency . " has been confirmed";

            $sender->sendEmail([$user->email], $data, $subject);
        }






        $deposit->update($body);

        $user = User::where("id", $deposit->user_id)->first();
        $user = User::relationships($user);

        return $user;
    }

    public function apiDelete($body)
    {
        $row = $this->where("id", $body["id"])->first();
        $user_id = $row->user_id;

        $user = User::where('id', $row->user_id)->first();

        if ($row->status == 'Confirmed') {
            TradingBalanceDeposit::subtract($user, $row->amount);
            // $user->update(['deposit_balance' => $user->deposit_balance - $row->amount]);
        }

        $row->delete();

        $user = User::where("id", $user_id)->first();
        $user = User::relationships($user);

        return $user;
    }
}
