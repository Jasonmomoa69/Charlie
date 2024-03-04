<?php

namespace Server\Others\Observers;

use Server\Others\Sender;

class BaseObserver
{
    public $sender;

    public function __construct()
    {
        $this->sender = new Sender;

    }
}