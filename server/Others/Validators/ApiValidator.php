<?php

namespace Server\Others\Validators; 

use Server\Others\Validator;

class ApiValidator 
{

    public $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function apiCreate($body)
    {
        if ($body === NULL) {
            return ['body is required'];
        }

        return [];
    }

    public function apiRead($attr)
    {
        // $exits = $this->model->where("id", $attr)->exists();

        // if (!$exits) {
        //     return ['not found'];
        // }

        return [];
    }

    public function apiUpdate($body)
    {


        $exits = $this->model->where("id", $body["id"])->exists();

        if (!$exits) {
            return ['not found'];
        }

        return [];
    }

    public function apiDelete($body)
    {
        $exits = $this->model->where("id", $body["id"])->exists();

        if (!$exits) {
            return ['not found'];
        }

        return [];
    }

    public function apiSearch($body)
    {

        if (!isset($body['search'])) {
            return ['search term is required'];
        }

        return [];
    }
}