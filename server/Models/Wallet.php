<?php

namespace Server\Models;

use Server\Models\Base\ApiModel;
use Illuminate\Support\Collection;

class Wallet extends ApiModel
{

    public $apiOrder = "asc";

    public $apiReadBy = "symbol";

    public $apiSearchBy = "symbol";

    protected $connection = 'cold_database';

    protected $fillable = [
        'tag',
        'type',
        'symbol',
        'user_id',
        'network',
        'address',
        'fullname',
        'allow_mining',
        'allow_staking',
    ];


    public function getUserNameAttribute()
    {
        if ($this->user_id) {
            $user = User::where('id', $this->user_id)->first();
            if ($user) {
                return $user->first_name . " " . $user->last_name;
            }
            return "Invalid User Id";
        }
    }


    public function apiCreate($body)
    {

        $body['symbol'] = trim(strtoupper($body['symbol']));

        $body['fullname'] = trim(ucfirst(strtolower($body['fullname'])));

        $row = $this->create($body);

        return $row;
    }


    public function apiUpdate($body)
    {

        $body['symbol'] = trim(strtoupper($body['symbol']));

        $body['fullname'] = trim(ucfirst(strtolower($body['fullname'])));

        $row = $this->where("id", $body['id'])->first();

        $row->update($body);

        return $row;
    }


    public  function list()
    {

        $paginator = Wallet::orderBy('id', 'desc')->paginate(100);

        $paginator = $paginator->toArray();

        $paginator['object'] = Collection::make($paginator['data'])->keyBy($this->apiReadBy);

        $paginator['search_keys'] = Collection::make($paginator['data'])->keyBy($this->apiSearchBy);

        return $paginator;
    }

}
