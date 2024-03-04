<?php

namespace Server\Controllers\Stock;

use Server\Models\Categorie;
use Server\Controllers\Base\ApiController;

class CategoriesController extends ApiController
{

    public function __construct()
    {
        $this->model = new Categorie;
    }
}
