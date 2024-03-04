<?php

namespace Server\Models\Simple;

use Server\Models\Base\ApiModel;

class Userwallet extends ApiModel
{
    protected $fillable = [
        'name',
        'user_id',
        'address',
    ];


    public function apiCreate($body)
    {
        $exists = $this->where('user_id', $body['user_id'])->where('address', $body['address'])->exists();
        if (!$exists) { return $this->create($body);}
    }


}