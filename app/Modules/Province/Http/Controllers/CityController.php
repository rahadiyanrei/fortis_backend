<?php

namespace App\Modules\Province\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\City;
class CityController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        $city = City::where(function($q) use($request) {
          if ($request->get('province_id')) {
            $q->where('province_id', $request->get('province_id'));
          }
        })->orderBy('name','asc')->get();
        $response = [
            "data" => $city,
            "status" => true,
            "message" => "Success get city"
        ];
        return response()->json($response);
    }
}
