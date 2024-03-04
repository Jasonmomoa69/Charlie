<?php

namespace Server\Models;

use Server\Models\Base\ApiModel;
use Server\Models\Traits\UpdatedTrait;




class Trade extends ApiModel
{
    use UpdatedTrait;

    protected $fillable = [
        'type',
        'time',
        'chart',
        'value',
        'profit',
        'status',
        'symbol',
        'amount',
        'market',
        'user_id',
        'lot_size',
        'leverage',
        'timestamp',
        'trader_id',
        'lot_value',
        'stop_loss',
        'investment',
        'prediction',
        'multiplier',
        'auto_close',
        'take_profit',
        'php_timestamp',
        'opening_price',
        'closing_price',
        'updated_at_day',
        'updated_at_month',
        'final_closing_price'
    ];

    public function apiList()
    {

        $paginator = $this->where('closing_price', null)->paginate($this->apiPerPage);

        $paginator = $paginator->toArray();

        return $paginator;
    }

    public function apiCreate($body)
    {

        if (isset($body['timestamp'])) {
            $body['timestamp'] = strval($body['timestamp']);

            $body['php_timestamp'] = time(); // for php trader cron    
        }
 
        $this->create($body);







        $user = User::where("id", $body['user_id'])->first();

        $settings = Setting::where('id', 1)->first();

        // $deposit_balance = $user->deposit_balance - $settings->trading_fee;

        if ($settings->trading_fee > 0) {
            TradingBalanceDeposit::subtract($user, $settings->trading_fee);
        }

        // $user->update(['deposit_balance' => $deposit_balance]);






        $user = User::where("id", $body['user_id'])->first();

        $user = User::relationships($user);

        return $user;
    }







    public function apiUpdate($body)
    {

        // fetch trade from database
        $tradeFromDb = $this->where("id", $body['id'])->first();

        // closing price is set -- trade is closed
        if ($tradeFromDb->closing_price) {

            // trade is clossed but force update
            if (isset($body['force_update'])) {
                $tradeFromDb->update($body);
            }
        }

        // closing price is not set -- trade is open
        if ($tradeFromDb->closing_price == NULL) {



            // close trade now
            if (isset($body['closing_price'])) {

                // destiny
                $body['profit'] = $tradeFromDb->profit ?? $body['profit'];

                $body['closing_price'] = $tradeFromDb->final_closing_price ?? $body['closing_price'];

                $user = User::where("id", $tradeFromDb->user_id)->first();

                // handle loss
                if ($body["profit"] < 0) {
                    TradingBalanceProfit::addNegativeValue($user, $body["profit"]);
                }

                // handle profit
                if ($body['profit'] >= 0) {
                    TradingBalanceProfit::addPositiveValue($user, $body["profit"]);
                }
            }


            $tradeFromDb->update($body);
        }


        $user = User::where("id", $tradeFromDb->user_id)->first();

        $user = User::relationships($user);


        return $user;
    }





























    public function getUserNameAttribute()
    {

        if ($this->user_id) {
            $user = User::where('id', $this->user_id)->first();
            return $user->first_name . " " . $user->last_name;
        }
    }

    public function getTraderNameAttribute()
    {

        if ($this->trader_id) {
            $trader = Trader::where('id', $this->trader_id)->first();
            return $trader->name;
        }
    }
}
