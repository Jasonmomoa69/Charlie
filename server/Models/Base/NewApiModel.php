<?php

namespace Server\Models\Base;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class NewApiModel extends Model
{


    public $apiReadBy = "id";

    public $apiPerPage = 100;

    public $apiOrder = "desc";

    public $apiOrderBy = "id";

    public $apiSearchBy = "id";


    public $modelResponse = [
        'status' => "200",
        'message' => '',
        'errors' => [],
        'data' => [],
    ];



    public function apiCreate($body)
    {
        $created = $this->create($body);

        $this->modelResponse['data'] = $created;

        return $this->modelResponse;
    }




    public function apiSearch($body)
    {

        $paginator = $this->where($this->apiSearchBy, 'LIKE', "%{$body['search']}%")->paginate($this->apiPerPage);

        $paginator = $paginator->toArray();

        $paginator['object'] = Collection::make($paginator['data'])->keyBy($this->apiReadBy);

        $paginator['search_keys'] = Collection::make($paginator['data'])->keyBy($this->apiSearchBy);

        $this->modelResponse['data'] = $paginator;

        return $this->modelResponse;
    }








    public function apiList()
    {

        $paginator = $this->orderBy($this->apiOrderBy, $this->apiOrder)->paginate($this->apiPerPage);

        $data = $this->getListShape($paginator);

        $this->modelResponse['data'] = $data;

        return $this->modelResponse;
    }


    public function apiRead($attr)
    {
        $data = $this->where($this->apiReadBy, $attr)->first();

        $data = $this->relationships($data);

        $this->modelResponse['data'] = $data;

        return $this->modelResponse;
    }





















    public function apiUpdate($body)
    {

        if (!isset($body['id'])) {
            $this->modelResponse['errors'] = ['id is required'];
            return $this->modelResponse;
        }


        $data = $this->where("id", $body['id'])->first();

        $data->update($body);

        $data = $this->where('id', $body['id'])->first();

        $this->modelResponse['data'] = $data;

        $this->modelResponse['message'] = "Updated";

        return $this->modelResponse;
    }



    public function apiDelete($body)
    {

        if (!isset($body['id'])) {

            $this->modelResponse['errors'] = ['id is required'];

            return $this->modelResponse;
        }


        $this->where("id", $body["id"])->delete();

        $paginator = $this->orderBy($this->apiOrderBy, $this->apiOrder)->paginate($this->apiPerPage);

        $data = $this->getListShape($paginator);

        $this->modelResponse['data'] = $data;

        return $this->modelResponse;
    }


    public function getListShape($paginator)
    {
        $paginator = $paginator->toArray();

        $paginator['object'] = Collection::make($paginator['data'])->keyBy($this->apiReadBy);

        $paginator['search_keys'] = Collection::make($paginator['data'])->keyBy($this->apiSearchBy);

        return $paginator;
    }


}
