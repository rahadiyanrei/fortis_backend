<?php

namespace App\Modules\Gallery\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\VehicleBrand;
use App\Gallery;
use App\Wheel;
use App\GalleryImage;
use ImageKit\ImageKit;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Sentinel;
use Illuminate\Support\Facades\DB;

class GalleryController extends Controller
{

    public function __construct(Request $request)
    {
        $this->image_thumbnail_dimension = [
            'width' => 1920,
            'height' => 1080
        ];
        $this->imageKit = new ImageKit(
            env('IMAGEKIT_PUBLIC_KEY'),
            env('IMAGEKIT_PRIVATE_KEY'),
            env('IMAGEKIT_URL')
        );

        $this->paginate = 10;
        $this->limit_banner = 6;
    }

    private function uploadImageToImageKit($image) {

        $now = Carbon::now()->toDateString();
        $prefix = 'gallery';
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

    public function welcome(Request $request)
    {
        $blog = Gallery::where(function($query) use($request){
            if ($request->get('global_search')) {
                $query
                ->whereRaw("title like '%".$request->get('global_search')."%'");
            }
        })->orderBy('created_at','desc')
        ->with(['createdBy','vehicle_brand','wheel'])
        ->paginate($this->paginate)
        ->withQueryString();
        return view("Gallery::welcome")->with('data', $blog);
    }

    public function formCreate() {
        $vehicle_brand = VehicleBrand::get();
        $wheel = Wheel::select(['id','name','status'])->get();
        $data_resp = [
            'image_thumbnail_dimension' => $this->image_thumbnail_dimension,
            'vehicle_brand' => $vehicle_brand,
            'wheel' => $wheel,
            'data_detail' => new Gallery,
        ];
        return view("Gallery::form", $data_resp);
    }

    public function formUpdate($uuid) {
        $vehicle_brand = VehicleBrand::get();
        $wheel = Wheel::select(['id','name','status'])->get();
        $data_resp = [
            'image_thumbnail_dimension' => $this->image_thumbnail_dimension,
            'vehicle_brand' => $vehicle_brand,
            'wheel' => $wheel,
            'data_detail' => Gallery::where('uuid', $uuid)->first(),
            'uuid' => $uuid
        ];
        return view("Gallery::form", $data_resp);
    }

    public function post(Request $request) {
        $auth = Sentinel::getUser();
        $action = 'Created';
        if (!$request->file('image_thumbnail') && !$request->has('uuid')) {
            return redirect()->back()->with('toast_error','Image Banner is Required!')->withInput();
        }
        $status = 0;
        if ($request->has('status')) {
            $status = 1;
        }
        $flag = 0;
        if ($request->has('dashboard_flag')) {
            $flag = 1;
        }
        try{
            DB::beginTransaction();
            $gallery = new Gallery;
            if ($request->has('uuid')){
                $gallery = $gallery->where('uuid', $request->post('uuid'))->first();
                $action = "Updated";
            } else {
                $gallery->uuid = Str::uuid();
                $gallery->created_by = $auth->id;
            }
            $gallery->updated_by = $auth->id;
            $gallery->title = $request->post('title');
            $gallery->dashboard_flag = $flag;
            $gallery->type = $request->post('type');
            if ($request->post('type') === 'wheel'){
                $gallery->vehicle_brand_id = null;
            } else {
                $gallery->vehicle_brand_id = $request->post('vehicle_brand_id');
            }
            $gallery->wheel_id = $request->post('wheel_id');
            $gallery->status = $status;
            if ($request->file('image_thumbnail')){
                $uploadImage = $this->uploadImageToImageKit($request->file('image_thumbnail'),'blog');
                $gallery->image_thumbnail = $uploadImage->url;
            }
            $gallery->save();
            if ($request->has('uuid')){
                GalleryImage::where('gallery_id', $gallery->id)->delete();
            } 
            if ($request->post('image_galleries')) {
                foreach($request->post('image_galleries') as $value) {
                    $img_gallery = new GalleryImage;
                    $img_gallery->image = $value;
                    $img_gallery->gallery_id = $gallery->id;
                    $img_gallery->created_by = $auth->id;
                    $img_gallery->updated_by = $auth->id;
                    $img_gallery->save();
                }
            }
            DB::commit();
            return redirect('gallery')->with('toast_success', ' Gallery Successfully '.$action.'!');
        }catch(Exception $e){
            DB::rollBack();
            return redirect()->back()->with('toast_error',$e->message())->withInput();
        }
    }
}
