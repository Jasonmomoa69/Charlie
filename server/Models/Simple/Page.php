<?php

namespace Server\Models\Simple; 

use Server\Models\Base\ApiModel;

class Page extends ApiModel
{

    public $apiOrder = "desc";

    public $apiReadBy = "slug";

    public $apiOrderBy = "updated_at";

    protected $fillable = [
        'slug',
        'icon',
        'title',
        'image',
        'content',
        'sub_title'
    ];

    public function apiCreate($body)
    {
        $body["slug"] = strtolower(trim($body["title"]));
        $body["slug"] = str_replace(" ", "-", $body['slug']);
        return $this->create($body);
    }


}