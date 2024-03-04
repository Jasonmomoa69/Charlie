<?php

namespace Server\Models;

class TradingBalanceDeposit
{

    public static function add(User $user, $amount)
    {

        $trading_balance_deposit = $user->trading_balance_deposit + $amount;

        $user->update([
            'trading_balance_deposit' => $trading_balance_deposit,
            'trading_balance_total' => $trading_balance_deposit + $user->trading_balance_profit
        ]);
    }


    public static function subtract(User $user, $amount)
    {
        $trading_balance_deposit = $user->trading_balance_deposit - $amount;

        $user->update([
            'trading_balance_deposit' => $trading_balance_deposit,
            'trading_balance_total' => $trading_balance_deposit + $user->trading_balance_profit
        ]);
    }
}
