<?php

namespace Server\Models\Simple;

use Illuminate\Database\Eloquent\Model;

class TraderLight extends Model
{
    protected $table = 'traders';

    protected $hidden = [
        'bio',
        'price',
        'handle',
        'win_rate',
        'wins_offset',
        'description',
        'profit_share',
        'losses_offset',
        'copiers_offset',
        'followers_offset',

        'referral_link',
        'type',
        'email',
        'photo',

        'total_wins',
        'email',
        'wins_count',
        'losses_count',
        'win_percent',
        'total_win_percent',
        'total_copiers',
        'trades_count',
        'copiers_count',
        'total_trades',
        'total_losses',

        'pending_requests',

        'created_at',
        'updated_at',
    ];
}
