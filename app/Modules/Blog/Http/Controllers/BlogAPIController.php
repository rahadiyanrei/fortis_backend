<?php

namespace App\Modules\Blog\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use ImageKit\ImageKit;
use App\Blog;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Sentinel;
use Illuminate\Support\Facades\DB;

class BlogAPIController extends Controller
{
    public function list(Request $request) {
      $limit = $request->get('limit') ? (int)$request->get('limit') : 10;
      $offset = $request->get('offset') ? (int)$request->get('offset') : 0;
      $count = Blog::where('status',1)->get()->count();
      $data = Blog::where('status',1)
      ->with('createdBy')
      ->orderBy('created_at','desc')
      ->limit($limit)
      ->offset($offset)
      ->get();
      $response = [
        "count" => $count,
        "data" => $data,
        "status" => true,
        "message" => "Success get blogs"
      ];
      return response()->json($response);
    }

    public function retrieve($slug) {
      $data = Blog::where('slug', $slug)->with('createdBy')->first();
      if(!$data) {
        return response()->json([
          "status" => false,
          "message" => "Blog not found!"
        ],400);
      }
      $response = [
        "data" => $data,
        "status" => true,
        "message" => "Success get retrieve blog"
      ];
      return response()->json($response);
    }
}
