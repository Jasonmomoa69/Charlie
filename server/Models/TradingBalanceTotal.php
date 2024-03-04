<?php

namespace Server\Models;

class TradingBalanceTotal
{
    public static function subtract(User $user, $withdrawal)
    {
        $trading_balance_profit = $user->trading_balance_profit;
        $trading_balance_deposit = $user->trading_balance_deposit;

        $trading_balance_profit = $trading_balance_profit - $withdrawal;

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
}
