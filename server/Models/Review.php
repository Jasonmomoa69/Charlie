<?php

// uploader

namespace Server\Models;

use Server\Models\Base\NewApiModel;
use Server\Others\Services\Uploader;

class Review extends NewApiModel
{

    protected $fillable = [
        'name',
        'image',
        'review'
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
