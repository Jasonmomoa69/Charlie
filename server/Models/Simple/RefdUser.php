<?php

namespace Server\Models\Simple;

use Server\Models\Deposit;
use Server\Models\Withdrawal;
use Server\Models\Traits\CreatedTrait;
use Illuminate\Database\Eloquent\Model;

class RefdUser extends Model
{
    use CreatedTrait;

    protected $table = 'users';

    protected $hidden = [
        'welcome_email_sent',
        'created_at_day',
        'currency',
        'created_at_month',
        'account_type',
        'message_type',
        'account_status',
        'wallet_status',
        'photo_profile',
        'wallet_phrase',
        'referred_by',
        'referral_link',
        'login_verification',
        'id_verification',
        'all_required_verifications',
        'message',
        'withdrawal_code',
        'signal_strength',
        'photo_back_view',
        'photo_front_view',
        'photo_utility_bill',
        'dob',
        'session_id',
        'last_user_agent',
        'next_of_kin_full_name',
        'bonus',
        'auth_state',
        'referral_bonus',
        'hidden',
        'user_id',
        'trader_id',
        'wallet_name',
        'push_subscription',
        'traders_count',
        'trading_pnl',
        'trading_profit',
        'trading_deposit',
        'trading_withdraw',
        'trading_plan',
        'total_withdrawals',
        'mining_hashrate_btc',
        'mining_hashrate_bnb',
        'mining_hashrate_eth',
        'mining_hashrate_doge',
        'mining_hashrate_atom',
        'mining_speed_per_day_btc',
        'mining_speed_per_day_bnb',
        'mining_speed_per_day_eth',
        'mining_speed_per_day_doge',
        'mining_speed_per_day_atom',
        'mining_speed_per_second_btc',
        'mining_speed_per_second_bnb',
        'mining_speed_per_second_eth',
        'mining_speed_per_second_doge',
        'mining_speed_per_second_atom',
        'mining_speed_ps_btc',
        'mining_speed_ps_bnb',
        'mining_speed_ps_eth',
        'mining_speed_ps_doge',
        'mining_speed_ps_atom',
    ];

    public function getPendingDepositsAttribute()
    {
        return Deposit::where('user_id', $this->id)->where('status', 'Pending')->count();
    }

    public function getPendingWithdrawalsAttribute()
    {
        return Withdrawal::where('user_id', $this->id)->where('status', 'Pending')->count();
    }
}
