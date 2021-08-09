<?php

namespace App\Modules\Wheels\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WheelsController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function pakoIndex()
    {
        return view("Wheels::pako/index");
    }

    public function inkoIndex()
    {
        return view("Wheels::inko/index");
    }
}
