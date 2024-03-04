<?php

namespace Server\Controllers;

use Server\Models\Setting;
use Server\Others\Validators\ApiValidator;
use Server\Controllers\Base\SolidController;

class SettingsController extends SolidController
{

    public function __construct()
    {
        $this->model = new Setting;
        $this->validator = new ApiValidator(new Setting);
    }




    public function currencies($request, $response) {

        $settings = Setting::where('id', 1)->first();

        $body = $request->getParsedBody();

        $settings->currencies = json_encode($body);

        $settings->save();

        $this->data['data'] = (new Setting)->apiList();
        
        $this->data['message'] = "Updated";
        
        $response->getBody()->write(json_encode($this->data));

        return $response->withHeader('Content-Type', 'application/json');
    }




    public function withdrawalMethods($request, $response) {

        $settings = Setting::where('id', 1)->first();

        $body = $request->getParsedBody();

        $settings->withdrawal_methods = json_encode($body);

        $settings->save();

        // Paginator::currentPathResolver(function () {
        //     return "/api/settings";
        // });

        $this->data['data'] = (new Setting)->apiList();

        $this->data['message'] = "Updated";
        
        $response->getBody()->write(json_encode($this->data));

        return $response->withHeader('Content-Type', 'application/json');
    }


    

}