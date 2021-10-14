<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use app\User;

class AdminController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("Admin::index");
    }

    public function form() {
        $default = new User;
        $data_response = [
            'data_detail' => $default,
        ];
        return view("Admin::form")->with($data_response);
    }
}
