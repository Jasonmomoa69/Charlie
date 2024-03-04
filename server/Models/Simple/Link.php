<?php

namespace Server\Models\Simple;

use Server\Models\Base\ApiModel;

class Link extends ApiModel
{

    protected $connection = 'cold_database';

    protected $fillable = [
        'name',
        'link',
    ];

}