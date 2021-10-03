<?php

namespace App\Modules\Gallery\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Gallery;

class GalleryAPIController extends Controller
{
  public function list(Request $request) {
    $limit = $request->get('limit') ? (int)$request->get('limit') : 10;
    $offset = $request->get('offset') ? (int)$request->get('offset') : 0;
    $data = Gallery::where(function($q) use($request) {
      $q->where('status', 1);
      if ($request->get('type')) {
        $q->where('type', $request->get('type'));
      }

      if ($request->get('wheel_id')) {
        $q->where('wheel_id', $request->get('wheel_id'));
      }

      if ($request->get('vehicle_brand_id')) {
        $q->where('vehicle_brand_id', $request->get('vehicle_brand_id'));
      }
    })
    ->with(['wheel' => function($q) {
      $q->with('sizes');
    },'vehicle_brand'])
    ->orderBy('created_at','desc')
    ->limit($limit)
    ->offset($offset)
    ->get();
    $response = [
      "count" => count($data),
      "data" => $data,
      "status" => true,
      "message" => "Success get gallery"
    ];
    return response()->json($response);
  }

  public function retrieve($uuid) {
    $data = Gallery::where('uuid', $uuid)
    ->with(['wheel' => function($q){
      $q->with('colors');
    },'img_gallery'])
    ->first();
    $response = [
      "data" => $data,
      "status" => true,
      "message" => "Success retrieve gallery"
    ];
    return response()->json($response);
  }

  public function gallery_dashboard() {
    $getSingleVehicle = Gallery::select(['id','uuid','image_thumbnail'])
      ->where('dashboard_flag', 1)
      ->where('type','car')
      ->inRandomOrder()
      ->first();
    $getMultipleVehicle = Gallery::select(['id','uuid','image_thumbnail'])
      ->where(function($q)use($getSingleVehicle){
        $q->where('type','car');
        if ($getSingleVehicle) {
          $q->where('id','!=',$getSingleVehicle->id);
        }
      })
      ->orderBy('created_at','desc')
      ->limit(5)
      ->get();

    $getSingleWheel = Gallery::select(['id','uuid','image_thumbnail'])
      ->where('dashboard_flag', 1)
      ->where('type','wheel')
      ->inRandomOrder()
      ->first();
    $getMultipleWheel = Gallery::select(['id','uuid','image_thumbnail'])
      ->where(function($q)use($getSingleWheel){
        $q->where('type','wheel');
        if($getSingleWheel){
          $q->where('id','!=',$getSingleWheel->id);
        }
      })
      ->orderBy('created_at','desc')
      ->limit(5)
      ->get();

    $data = [
      "single_vehicle" => $getSingleVehicle,
      "multiple_vehicle" => $getMultipleVehicle,
      "single_wheel" => $getSingleWheel,
      "multiple_wheel" => $getMultipleWheel
    ];
    $response = [
      "data" => $data,
      "status" => true,
      "message" => "Success get gallery dashboard"
    ];
    return response()->json($response);
  }
}
