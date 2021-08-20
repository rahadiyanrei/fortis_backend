<?php
namespace App\Modules\Banner\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Banner;

class BannerAPIController extends Controller
{
    public function __construct(Request $request)
    {
        $this->limit_banner = 6;
    }

    public function list(){
        $banner = Banner::where('status', 1)->orderBy('created_at','desc')->limit($this->limit_banner)->get();
        $response = [
            "data" => $banner,
            "status" => true,
            "message" => "Success get banner"
        ];
        return response()->json($response);
    }
}