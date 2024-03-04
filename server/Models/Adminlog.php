<?php

namespace Server\Models;

use Illuminate\Database\Eloquent\Model;

class Adminlog extends Model
{

    protected $fillable = [
        'date',
        'session_id',
        'ip_address',
        'user_agent',
    ];

}