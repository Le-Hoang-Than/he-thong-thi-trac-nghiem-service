<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected $api;

    public function __construct()
    {
        $this->api = config('app.base_api');
    }
}
