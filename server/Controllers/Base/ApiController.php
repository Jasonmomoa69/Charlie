<?php

namespace Server\Controllers\Base;

class ApiController
{


    public $model;


    public function list($request, $response)
    {
        $modelResponse = $this->model->apiList();

        $response->getBody()->write(json_encode($modelResponse));

        return $response->withHeader('Content-Type', 'application/json');
    }


    public function read($request, $response)
    {

        $attr = $request->getAttribute('attr');

        $modelResponse = $this->model->apiRead($attr);

        $response->getBody()->write(json_encode($modelResponse));

        return $response->withHeader('Content-Type', 'application/json');
    }















    public function create($request, $response)
    {
        $body = $request->getParsedBody();

        $modelResponse = $this->model->apiCreate($body);

        $response->getBody()->write(json_encode($modelResponse));

        return $response->withHeader('Content-Type', 'application/json');
    }


    public function update($request, $response)
    {
        $body = $request->getParsedBody();

        $modelResponse = $this->model->apiUpdate($body);

        $response->getBody()->write(json_encode($modelResponse));

        return $response->withHeader('Content-Type', 'application/json');
    }


    public function delete($request, $response)
    {
        $body = $request->getParsedBody();

        $modelResponse = $this->model->apiDelete($body);

        $response->getBody()->write(json_encode($modelResponse));

        return $response->withHeader('Content-Type', 'application/json');
    }


    public function search($request, $response)
    {

        $body = $request->getParsedBody();

        $modelResponse = $this->model->apiSearch($body);

        $response->getBody()->write(json_encode($modelResponse));

        return $response->withHeader('Content-Type', 'application/json');
    }

}
