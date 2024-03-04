<?php

namespace Server\Models;

use GuzzleHttp\Client;
use Server\Models\Simple\Nft;
use Server\Models\Base\ApiModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection as IlluminateCollection;

class Collection extends ApiModel
{

    use SoftDeletes;

    public $apiSearchBy = "name";

    public $apiReadBy = "token_address";

    protected $fillable = [ 
        'name',
        'hero',
        'image',
        'status',
        'user_id',
        'address', 
        'network',
        'price_min',
        'price_max',
        'description',
        'categorie_id',
        'publish_price',
        'token_address',
        'external_image'
    ];

    public function getImageSizeAttribute() {
        return getimagesize($this->external_image);
    }

    public function relationships($row) {
        $row->nfts = $row->nfts()->paginate(12);
        return $row;
    }

    public function nfts()
    {
        return $this->hasMany(Nft::class, "token_address", "token_address")->orderBy('created_at', 'DESC');
    }

    public function apiList()
    {

        $paginator = $this->latest()->paginate($this->apiPerPage); 

        $paginator = $paginator->toArray();

        // id_data
        $paginator['object'] = IlluminateCollection::make($paginator['data'])->keyBy($this->apiReadBy);
        
        // name_id
        $paginator['name_id'] = IlluminateCollection::make($paginator['data'])->keyBy("name")->map(function($item) {
            return $item["token_address"];
        });

        // name_image
        $paginator['search_keys'] = IlluminateCollection::make($paginator['data'])->keyBy("name")->map(function($item) {
            return $item["external_image"];
        });

        return $paginator;
    } 

    public function apiCreate($body)
    {

        $token_address = $body["token_address"];
        
        $url = "https://deep-index.moralis.io/api/v2/nft/{$token_address}?chain=eth&format=decimal"; 
        $headers = ['X-API-Key' => 'o153kWH4zJsHXhzYfNZUIMj4fIMZESHsWBBgA18lwJxrCIxhzI71WE7n846C6aMT'];
        $client = new Client(['headers' => $headers]);
        $res = $client->request('GET', $url);
        $string_data = (string) $res->getBody();
        $data = json_decode($string_data);
        $nfts = $data->result;

        if (count($nfts) === 0) {
            return ['errors' => ['no nfts found in collection'], 'data' => []];
        }

        $metadata = json_decode($nfts[0]->metadata);

        if ($metadata == NULL) {
            return ['errors' => ['cant get nft meta data'], 'data' => []];
        }

        if (!property_exists($metadata, 'image')) {
            return ['errors' => ['cant get image data'], 'data' => []];
        }
        if ($metadata->image == '') {
            return ['errors' => ['cant get image data'], 'data' => []];
        }

        if (substr($metadata->image, 0,7) == "ipfs://") {
            $metadata->image = 'https://ipfs.io/ipfs/' . substr($metadata->image, 7,strlen($metadata->image));
        }

        $collection_name = $nfts[0]->name;

        // create collection
        $collection = $this->create([
            'name' => $nfts[0]->name,
            'price_min' => $body['price_min'],
            'price_max' => $body['price_max'],
            'external_image' => $metadata->image,
            'categorie_id' => $body['categorie_id'],
            'token_address' => $nfts[0]->token_address,
        ]);



        // create collection nfts
        for ($i = 0; $i < count($nfts); $i++) {

            $metadata = json_decode($nfts[$i]->metadata);

            if ($metadata == NULL) {
                continue;
            }

            if (!property_exists($metadata, 'image')) {
                continue;
            }

            if (substr($metadata->image, 0,7) == "ipfs://") {
                $metadata->image = 'https://ipfs.io/ipfs/' . substr($metadata->image, 7,strlen($metadata->image));
            }

            $exists = Nft::where('external_image', $metadata->image)->first();

            if (!$exists) {
                Nft::create([
                    'token_id' => $nfts[$i]->token_id,
                    'external_image' => $metadata->image,
                    'collection_name' => $collection_name,
                    'token_address' => $nfts[$i]->token_address,
                    'price' => rand($body['price_min'] * 10, $body['price_max'] * 10) / 10,
                ]);
            }

        }


        return ['data' => $collection, 'errors' => []];
    }

    public function apiSearch($body)
    {

        $paginator = $this->where($this->apiSearchBy, 'LIKE', "%{$body['search']}%")->paginate($this->apiPerPage); 

        $paginator = $paginator->toArray();

         // id_data
        $paginator['object'] = IlluminateCollection::make($paginator['data'])->keyBy($this->apiReadBy);

        // name_id
        $paginator['name_id'] = IlluminateCollection::make($paginator['data'])->keyBy("name")->map(function($item) {
            return $item["token_address"];
        });

        // name_image
        $paginator['search_keys'] = IlluminateCollection::make($paginator['data'])->keyBy($this->apiSearchBy)->map(function($item) {
            return $item["external_image"];
        });

        return $paginator;
    }

    public function apiDelete($body)
    {
        //  delete
        $collection = $this->where("id", $body["id"])->first();


        Nft::where('token_address', $collection->token_address)->where('id', NULL)->delete();


        $collection->delete();



        // response

        $paginator = $this->latest()->paginate($this->apiPerPage); 

        $paginator = $paginator->toArray();

        $paginator['object'] = IlluminateCollection::make($paginator['data'])->keyBy($this->apiReadBy);

        $paginator['search_keys'] = IlluminateCollection::make($paginator['data'])->keyBy($this->apiSearchBy);

        return $paginator;
    }

    public function apiDeleteNft($body)
    {

        $nft = Nft::where('id', $body['id'])->first();

        $token_address = $nft->token_address;

        $nft->delete();

        

        $collection = $this->where("token_address", $token_address)->first(); 

        $collection = $this->relationships($collection);

        return $collection;
    }



    // public function getImageSizeAttribute() {
    //     return getimagesize($this->external_image);
    // }

    public function getCategorieNameAttribute() {

        
        $cat = Categorie::where('id', $this->categorie_id)->first();

        if ($cat) {
            return $cat->name;
        }

    }


}