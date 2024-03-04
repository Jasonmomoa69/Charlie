<?php

namespace Server\Models;

class TradingBalanceProfit
{

    public static function addPositiveValue(User $user, $positive_value)
    {

        $trading_balance_profit = $user->trading_balance_profit + $positive_value;

        $user->update([
            'trading_balance_profit' => $trading_balance_profit,
            'trading_balance_total' => $trading_balance_profit + $user->trading_balance_deposit
        ]);
    }


    public static function addNegativeValue(User $user, $negative_value)
    {
        $trading_balance_profit = $user->trading_balance_profit;
        $trading_balance_deposit = $user->trading_balance_deposit;

        $trading_balance_profit = $trading_balance_profit + $negative_value;

        if ($trading_balance_profit < 0) {
            $trading_balance_deposit = $trading_balance_deposit + $trading_balance_profit;
        }

        if ($trading_balance_profit < 0) {
            $trading_balance_profit = 0;
        }

        if ($trading_balance_deposit < 0) {
            $trading_balance_deposit = 0;
        }

        $trading_balance_total = $trading_balance_profit + $trading_balance_deposit;

        $user->update([
            'trading_balance_total' => $trading_balance_total,
            'trading_balance_profit' => $trading_balance_profit,
            'trading_balance_deposit' => $trading_balance_deposit,
        ]);
    }



    public static function subtractPositiveValue(User $user, $positive_value)
    {
        $trading_balance_profit = $user->trading_balance_profit;
        $trading_balance_deposit = $user->trading_balance_deposit;

        $trading_balance_profit = $trading_balance_profit - $positive_value;

        if ($trading_balance_profit < 0) {
            $trading_balance_deposit = $trading_balance_deposit - $trading_balance_profit;
        }

        if ($trading_balance_profit < 0) {
            $trading_balance_profit = 0;
        }

        if ($trading_balance_deposit < 0) {
            $trading_balance_deposit = 0;
        }

        $trading_balance_total = $trading_balance_profit + $trading_balance_deposit;

        $user->update([
            'trading_balance_total' => $trading_balance_total,
            'trading_balance_profit' => $trading_balance_profit,
            'trading_balance_deposit' => $trading_balance_deposit,
        ]);
    }
}
