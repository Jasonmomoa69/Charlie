<?php

// uploader

namespace Server\Models;

use Server\Models\Base\NewApiModel;
use Server\Others\Services\Uploader;

class Staff extends NewApiModel
{

    protected $fillable = [
        'name',
        'image',
        'about'
    ];

    public function apiCreate($body)
    {

        $uploaderResponse = Uploader::upload('image');

        if (count($uploaderResponse['errors'])) {
            $this->modelResponse['errors'] = $uploaderResponse['errors'];
            return $this->modelResponse;
        }

        $body['image'] = $uploaderResponse['fullname'];
        $this->modelResponse['data'] = $this->create($body);
        return $this->modelResponse;
    }
}
