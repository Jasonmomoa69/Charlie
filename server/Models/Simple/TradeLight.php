<?php

namespace Server\Models\Simple;

use Illuminate\Database\Eloquent\Model;

class TradeLight extends Model {

    protected $table = 'trades';

    protected $hidden = [
        'type',
        'time',
        'chart',
        'value',
        'status',
        'symbol',
        'amount',
        'market',
        'lot_size',
        'leverage',
        'class_name',
        'created_at',
        'updated_at',
        'user_name',
        'trader_name',
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
    ];
}