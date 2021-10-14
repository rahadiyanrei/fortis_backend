<?php

namespace App\Modules\Apparel\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Apparel;
use App\ApparelImage;
use App\ApparelCategory;
use ImageKit\ImageKit;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Sentinel;
use Illuminate\Support\Facades\DB;
class ApparelAPIController extends Controller
{
    public function dropdown() {
        $apparelCategory = ApparelCategory::orderBy('id','asc')->get();
        $response = [
            "data" => $apparelCategory,
            "status" => true,
            "message" => "Success get apparel category"
        ];
        return response()->json($response);
    }

    public function apparelList(Request $request) {
        $limit = $request->get('limit') ? (int)$request->get('limit') : 4;
        $offset = $request->get('offset') ? (int)$request->get('offset') : 0;
        $orderBy = $request->get('orderBy') ? $request->get('orderBy') : 'created_at';
        $orderType = $request->get('orderType') ? $request->get('orderType') : 'desc';
        $category = $request->get('apparel_category_id') ? $request->get('apparel_category_id') : 'desc';
        $apparel = Apparel::where(function($q)use($category){
            $q->where('status', 1);
            if ($category) $q->where('apparel_category_id', $category);
        })
        ->orderBy($orderBy,$orderType)
        ->limit($limit)
        ->offset($offset)
        ->get();
        $count = Apparel::where(function($q)use($category){
            $q->where('status', 1);
            if ($category) $q->where('apparel_category_id', $category);
        })
        ->get()->count();
        $response = [
            "count" => $count,
            "data" => $apparel,
            "status" => true,
            "message" => "Success get apparel"
        ];
        return response()->json($response);
    }

    public function apparelView($uuid) {
        $apparel = Apparel::where('uuid',$uuid)
        ->with(['images','category'])
        ->first();
        $apparel->sizes = json_decode($apparel->sizes);
        $related = Apparel::where('status',1)
        ->where('apparel_category_id', $apparel->apparel_category_id)
        ->where('id','!=' , $apparel->id)
        ->orderBy('created_at','desc')
        ->limit(5)
        ->get();
        $response = [
            "data" => $apparel,
            "related" => $related,
            "status" => true,
            "message" => "Success retrieve apparel"
        ];
        return response()->json($response);
    }
}
