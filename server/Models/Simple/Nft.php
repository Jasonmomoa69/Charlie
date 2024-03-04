<?php

namespace Server\Models\Simple;

use Server\Models\Collection;
use Server\Models\Base\NewApiModel;
use Server\Others\Services\Uploader;
use Illuminate\Support\Collection as IlluminateCollection;

class Nft extends NewApiModel 
{

    protected $fillable = [
        'name',
        'price',
        'image',
        'nft_tag',
        'user_id',
        'token_id',
        'description',
        'token_address',
        'purchase_type',
        'collection_id',
        'external_image',
        'collection_name',
        'user_wallet_address'
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

    // public function relationships($row) {
    //     $row->collection = $row->collection;
    //     return $row;
    // }


    // public function getCollectionNameAttribute() 
    // {
    //     if ($this->collection) {
    //         return $this->collection->name;
    //     }
    // }

    // public function collection() {
    //     return $this->hasOne(Collection::class, "token_address", "token_address");
    // }

}