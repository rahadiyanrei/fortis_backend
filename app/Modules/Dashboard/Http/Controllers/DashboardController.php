<?php

namespace App\Modules\Dashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Wheel;
use App\Blog;
use App\Gallery;
class DashboardController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        return view("Dashboard::index");
    }

    public function wheel() {
        $wheel = Wheel::where('status', 1)->get()->count();
        return response()->json([
            'count' => $wheel
        ]);
    }

    public function blog() {
        $blog = Blog::where('status', 1)->get()->count();
        return response()->json([
            'count' => $blog
        ]);
    }

    public function gallery() {
        $gallery = Gallery::where('status', 1)->get()->count();
        return response()->json([
            'count' => $gallery
        ]);
    }
}
