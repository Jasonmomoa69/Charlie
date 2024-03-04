<?php

namespace Server\Others;

use Twig\Environment;
use Server\Models\Setting;
use Twig\Loader\FilesystemLoader;

class Renderer
{

    private $lib;

    public function __construct()
    {

        $loader = new FilesystemLoader(HTML_DIR);

        $this->lib = new Environment($loader, ['cache' => false]);
    }


    public function render($view, $data = [])
    {

        $settings = Setting::where('id', 1)->first();

        $data['NODE_NAME'] = getenv("NODE_NAME");

        $data['NODE_DOMAIN'] = getenv("NODE_DOMAIN");

        $data['CONTACT_TELEGRAM'] = $settings->contact_telegram;

        $data['CONTACT_INSTAGRAM'] = $settings->contact_instagram;

        return $this->lib->render($view, $data);
    }
}
