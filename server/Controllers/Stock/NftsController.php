<?php

namespace Server\Controllers\Stock;

use Server\Models\Simple\Nft;
use Server\Others\Validators\ApiValidator;
use Server\Controllers\Base\ApiController;

class NftsController extends ApiController
{

    public function __construct()
    {
        $this->model = new Nft;
    }



}