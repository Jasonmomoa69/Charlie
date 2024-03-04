<?php

namespace Server\Models;

use Server\Models\Base\NewApiModel;

class Direction extends NewApiModel
{

    protected $connection = 'cold_database';

    protected $fillable = [
        'name',
        'direction',
    ];
}
