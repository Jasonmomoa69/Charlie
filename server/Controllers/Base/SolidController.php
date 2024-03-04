<?php

namespace Server\Controllers\Base;

class SolidController
{
    public $model;

    public $sender;

    public $searchBy;

    public $renderer;

    public $validator;

    public $data = [
        'status' => "200",
        'message' => '',
        'errors' => [],
        'data' => [],
    ];




    public function create($request, $response)
    {
        $body = $request->getParsedBody();

        $errors = $this->validator->apiCreate($body);

        if (count($errors)) {
            $this->data['errors'] = $errors;
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        $data = $this->model->apiCreate($body);

        $this->data['data'] = $data;

        $response->getBody()->write(json_encode($this->data));

        return $response->withHeader('Content-Type', 'application/json');
    }








    public function list($request, $response)
    {
        $data = $this->model->apiList();

        $this->data['data'] = $data;

        $response->getBody()->write(json_encode($this->data));

        return $response->withHeader('Content-Type', 'application/json');
    }
















    public function read($request, $response)
    {
        $attr = $request->getAttribute('attr');

        $errors = $this->validator->apiRead($attr);

        if (count($errors)) {
            $this->data['errors'] = $errors;
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        $data = $this->model->apiRead($attr);

        $this->data['data'] = $data;
        $response->getBody()->write(json_encode($this->data));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function search($request, $response)
    {

        $body = $request->getParsedBody();

        $errors = $this->validator->apiSearch($body);

        if (count($errors)) {
            $this->data['errors'] = $errors;
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        $data = $this->model->apiSearch($body);

        $this->data['data'] = $data;
        $response->getBody()->write(json_encode($this->data));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function update($request, $response)
    {
        $body = $request->getParsedBody();

        $errors = $this->validator->apiUpdate($body);

        if (count($errors)) {
            $this->data['errors'] = $errors;
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        $data = $this->model->apiUpdate($body);

        $this->data['data'] = $data;
        $this->data['message'] =  'Updated';
        $response->getBody()->write(json_encode($this->data));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function delete($request, $response)
    {
        $body = $request->getParsedBody();

        $errors = $this->validator->apiDelete($body);

        if (count($errors)) {
            $this->data['errors'] = $errors;
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        $data = $this->model->apiDelete($body);

        $this->data['data'] = $data;
        $response->getBody()->write(json_encode($this->data));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
