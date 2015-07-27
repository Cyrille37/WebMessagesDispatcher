<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function home()
    {
        return view('home');
    }

    public function about()
    {
        return view('about');
    }

}
