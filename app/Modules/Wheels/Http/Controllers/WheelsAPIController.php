<?php

namespace App\Modules\Wheels\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Wheel;

class WheelsAPIController extends Controller
{
    public function newArrival(Request $request) {
      $limit = $request->get('limit') ? (int)$request->get('limit') : 4;
      $offset = $request->get('offset') ? (int)$request->get('offset') : 0;
      $count = Wheel::where('status', 1)->where('is_new_release', 1)->get()->count();
      $wheel = Wheel::select(['id','uuid','name','brand','image'])
        ->with([
          'sizes' => function($q) {
            $q->select(['wheel_id','diameter']);
          },
          'colors' => function($q) {
            $q->select(['wheel_id','color_name','color_hex']);
          }
        ])
        ->where('status', 1)
        ->where('is_new_release', 1)
        ->orderBy('created_at','desc')
        ->limit($limit)
        ->offset($offset)
        ->get();
      $response = [
        "count" => $count,
        "data" => $wheel,
        "status" => true,
        "message" => "Success get wheel new arrival"
      ];
      return response()->json($response);
    }

    public function list(Request $request) {
      $limit = $request->get('limit') ? (int)$request->get('limit') : 4;
      $offset = $request->get('offset') ? (int)$request->get('offset') : 0;
      $newRelease = $request->get('newRelease') ? (int)$request->get('newRelease') : 0;
      $orderBy = $request->get('orderBy') ? $request->get('orderBy') : 'created_at';
      $orderType = $request->get('orderType') ? $request->get('orderType') : 'desc';
      $brand = $request->get('brand');
      $brandExplode = [];
      if ($brand) {
        $brandExplode = explode(",", $brand);
      }
      $count = Wheel::where(function ($q) use($brandExplode,$newRelease) {
        $q->where('status', 1);
        if (count($brandExplode) !== 0) {
          $q->whereIn('brand', $brandExplode);
        }
        if ($newRelease === 1) {
          $q->where('is_new_release', 1);
        }
      })->get()->count();
      $wheel = Wheel::select(['id','uuid','name','brand','image'])
        ->with([
          'sizes' => function($q) {
            $q->select(['wheel_id','diameter']);
          },
          'colors' => function($q) {
            $q->select(['id','wheel_id','color_name','color_hex']);
          }
        ])
        ->where(function ($q) use($brandExplode,$newRelease) {
          $q->where('status', 1);
          if (count($brandExplode) !== 0) {
            $q->whereIn('brand', $brandExplode);
          }
          if ($newRelease === 1) {
            $q->where('is_new_release', 1);
          }
        })
        ->orderBy($orderBy, $orderType)
        ->limit($limit)
        ->offset($offset)
        ->get();
      
      $response = [
        "count" => $count,
        "data" => $wheel,
        "status" => true,
        "message" => "Success get wheel list"
      ];
      return response()->json($response);
    }
}
