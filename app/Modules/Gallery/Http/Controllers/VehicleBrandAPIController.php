<?php

namespace App\Modules\Gallery\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\VehicleBrand;
use Carbon\Carbon;

class VehicleBrandAPIController extends Controller
{
    public function dropdown(Request $request) {
      $data = VehicleBrand::orderBy('name','asc')->get();
      $response = [
        "data" => $data,
        "status" => true,
        "message" => "Success get vechicle brands"
      ];
      return response()->json($response);
    }
}
