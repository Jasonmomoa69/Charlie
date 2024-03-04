<?php

namespace Server\Models\Simple;

use Illuminate\Database\Eloquent\Model;

class TraderUser extends Model {

    protected $table = 'trader_user';

    protected $fillable = [
        'status',
        'user_id',
        'trader_id',
    ];

}