<?php

namespace App\Modules\Province\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Country;
class CountryController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        $country = Country::orderBy('name','asc')->get();
        $response = [
            "data" => $country,
            "status" => true,
            "message" => "Success get country"
        ];
        return response()->json($response);
    }
}
