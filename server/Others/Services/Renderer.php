<?php

namespace Server\Others\Services;

use Twig\Environment;
use Server\Models\Setting;
use Twig\Loader\FilesystemLoader;

class Renderer
{

    public static function render() {

        $loader = new FilesystemLoader(HTML_DIR);
        $lib = new Environment($loader, ['cache' => false]);

        $settings = Setting::where('id', 1)->first();

        $data['NODE_NAME'] = getenv("NODE_NAME");

        $data['NODE_DOMAIN'] = getenv("NODE_DOMAIN");

        $data['CONTACT_TELEGRAM'] = $settings->contact_telegram;

        $data['CONTACT_INSTAGRAM'] = $settings->contact_instagram;

        return $lib->render($view, $data);

    }

}
