<?php

namespace App\Modules\Wheels\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use ImageKit\ImageKit;
use Carbon\Carbon;

class UploadController extends Controller
{
    public function __construct() {
      $this->imageKit = new ImageKit(
        env('IMAGEKIT_PUBLIC_KEY'),
        env('IMAGEKIT_PRIVATE_KEY'),
        env('IMAGEKIT_URL')
      );
    }

    public function upload(Request $request)
    {
        $upload = $this->uploadImageToImageKit($request->file('image'));
        return response()->json([
          "path" => $upload->url
        ]);
    }

    private function uploadImageToImageKit($image) {

      $now = Carbon::now()->toDateString();
      $prefix = 'wheel';
      $filename = $prefix.'-'.$now.'-'.$image->getClientOriginalName();
      $upload = $this->imageKit->uploadFiles(array(
          "file" => base64_encode(file_get_contents($image->path())), // required
          "fileName" => $filename, // required
          "useUniqueFileName" => true, // optional
          "folder" => env('IMAGEKIT_FOLDER'), // optional
      ));
      if ($upload->err === null) {
          return $upload->success;
      }
      return null;
  }
}
