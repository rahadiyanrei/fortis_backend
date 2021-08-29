<?php

namespace App\Modules\Contact\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Dealer;

class DealerAPIController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        $dealer = Dealer::select(['uuid','lat','long','name','address','email','phone_number'])->where(function($query) use($request) {
            if ($request->get('province_id')) {
                $query->where('province_id', $request->get('province_id'));
            }
        })->where('status',1)->orderBy('created_at','desc')->get();
        $response = [
            'data' => $dealer,
            'status' => true,
            'message' => 'Success get Dealer'
        ];
        return response()->json($response);
    }
}
