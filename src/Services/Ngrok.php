<?php

namespace ProjectCLI\Ngrok\Services;

use Chriha\ProjectCLI\Helpers;

class Ngrok
{

    public function __construct()
    {
        //
    }

    public function isInstalled() : bool
    {
        return Helpers::commandExists('ngrok');
    }

    public function isConfigured() : bool
    {
        //
    }

}
