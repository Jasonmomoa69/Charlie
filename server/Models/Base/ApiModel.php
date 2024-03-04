<?php

namespace Server\Models\Base;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class ApiModel extends Model
{

    public $apiWith = [];

    public $apiReadBy = "id";

    public $apiPerPage = 100;

    public $apiOrder = "desc";

    public $apiOrderBy = "id";

    public $apiSearchBy = "id";



    // public function apiRead($attr)
    // {
    //     $data = $this->where('id', $attr)->first();
    //     $data = $this->relationships($data);
    //     return $data;
    // }

    public function apiRead($attr)
    {
        $data = $this->where($this->apiReadBy, $attr)->first();

        $data = $this->relationships($data);

        return $data;
    }










    public  function apiList()
    {

        $paginator = $this->orderBy($this->apiOrderBy, $this->apiOrder)->paginate($this->apiPerPage);

        $paginator = $paginator->toArray();

        $paginator['object'] = Collection::make($paginator['data'])->keyBy($this->apiReadBy);

        $paginator['search_keys'] = Collection::make($paginator['data'])->keyBy($this->apiSearchBy);

        return $paginator;
    }





    // public  function apiList()
    // {

    //     $paginator = $this->latest()->orderBy('id','ASC')->paginate($this->apiPerPage); 

    //     $paginator = $paginator->toArray();

    //     $paginator['object'] = Collection::make($paginator['data'])->keyBy($this->apiReadBy);

    //     $paginator['search_keys'] = Collection::make($paginator['data'])->keyBy($this->apiSearchBy);

    //     return $paginator;
    // } 



    public function apiUpdate($body)
    {
        $row = $this->where("id", $body['id'])->first();

        $row->update($body);

        $row = $this->where('id', $body['id'])->first();

        // $row = $this->relationships($row);

        return $row;
    }

    public function apiDelete($body)
    {
        $this->where("id", $body["id"])->delete();

        $paginator = $this->latest()->paginate($this->apiPerPage);

        $paginator = $paginator->toArray();

        $paginator['object'] = Collection::make($paginator['data'])->keyBy($this->apiReadBy);

        $paginator['search_keys'] = Collection::make($paginator['data'])->keyBy($this->apiSearchBy);

        return $paginator;
    }

    public function apiSearch($body)
    {

        $paginator = $this->where($this->apiSearchBy, 'LIKE', "%{$body['search']}%")->paginate($this->apiPerPage);

        $paginator = $paginator->toArray();

        $paginator['object'] = Collection::make($paginator['data'])->keyBy($this->apiReadBy);

        $paginator['search_keys'] = Collection::make($paginator['data'])->keyBy($this->apiSearchBy);

        return $paginator;
    }









    public function apiCreate($body)
    {
        return $this->create($body);
    }










    // public function uploadImage($image, $name = "")
    // {
    //     if (strlen($name) == 0) {
    //         $name = time() . ".jpg";
    //     } else {
    //         $name = $name . ".jpg";
    //     }

    //     try {
    //         move_uploaded_file($image['tmp_name'], IMAGE_DIR . $name);
    //     } catch (\Exception $e) {
    //         return $e->getMessage();
    //     }

    //     return $name;
    // }
}
