<?php

namespace Server\Controllers\Stock;

use Server\Models\Review;
use Server\Others\Validators\ApiValidator;
use Server\Controllers\Base\ApiController;

class ReviewsController extends ApiController
{

    public function __construct()
    {
        $this->model = new Review;
    }

}