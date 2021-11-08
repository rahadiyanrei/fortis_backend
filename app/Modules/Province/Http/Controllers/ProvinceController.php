<?php

namespace App\Modules\Province\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Province;
use App\Dealer;
class ProvinceController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        if ($request->get('only_exists') === "true") {
            $dealer = Dealer::select('province_id')->where('status',1)->groupBy('province_id')->pluck('province_id');
            $province = Province::whereIn('id', $dealer)->orderBy('name','asc')->get();
        } else {
            $province = Province::orderBy('name','asc')->get();
        }
        $response = [
            "data" => $province,
            "status" => true,
            "message" => "Success get province"
        ];
        return response()->json($response);
    }
}
