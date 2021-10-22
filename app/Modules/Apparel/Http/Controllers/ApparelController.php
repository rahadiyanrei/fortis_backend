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
class ApparelController extends Controller
{

    public function __construct(Request $request)
    {
        $this->post_redirect_prefix = '/apparel';
        $this->image_thumbnail_dimension = [
            'width' => 1280,
            'height' => 1280
        ];

        $this->imageKit = new ImageKit(
            env('IMAGEKIT_PUBLIC_KEY'),
            env('IMAGEKIT_PRIVATE_KEY'),
            env('IMAGEKIT_URL')
        );

        $this->paginate = 10;
        $this->sizes = [
            'S','M','L','XL','XXL','XXXXL'
        ];
    }

    private function uploadImageToImageKit($image) {

        $now = Carbon::now()->toDateString();
        $prefix = 'apparel';
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

    public function list(Request $request)
    {
        $banner = Apparel::where(function($query) use($request){
            if ($request->get('global_search')) {
                $query
                ->whereRaw("name like '%".$request->get('global_search')."%'");
            }
        })->orderBy('created_at','desc')
        ->with(['createdBy'])
        ->paginate($this->paginate)
        ->withQueryString();
        return view("Apparel::list")->with('data', $banner);
    }

    public function form() {
        $apparel_categories = ApparelCategory::get();
        $default_apparel = new Apparel;
        $data_response = [
            'image_thumbnail_dimension' => $this->image_thumbnail_dimension,
            'data_detail' => $default_apparel,
            'categories' => $apparel_categories,
            'sizes' => $this->sizes,
        ];
        return view("Apparel::form")->with($data_response);
    }

    public function view($uuid) {
        $apparel_categories = ApparelCategory::get();
        $default_apparel = Apparel::where('uuid', $uuid)->first();
        $data_response = [
            'image_thumbnail_dimension' => $this->image_thumbnail_dimension,
            'data_detail' => $default_apparel,
            'categories' => $apparel_categories,
            'sizes' => $this->sizes,
            'uuid' => $uuid
        ];
        return view("Apparel::form")->with($data_response);
    }

    public function post(Request $request) {
        $auth = Sentinel::getUser();
        $action = 'Created';
        if (!$request->file('image_thumbnail') && !$request->has('uuid')) {
            return redirect()->back()->with('toast_error','Image Thumbnail is Required!')->withInput();
        } 
        $status = 0;
        if ($request->has('status')) {
            $status = 1;
        }
        $allSizes = 0;
        if ($request->has('is_all_sizes')) {
            $allSizes = 1;
        }
        try{
            DB::beginTransaction();
            $apparel = new Apparel;
            if ($request->has('uuid')){
                $apparel = $apparel->where('uuid', $request->post('uuid'))->first();
                $action = "Updated";
            } else {
                $apparel->uuid = Str::uuid();
                $apparel->created_by = $auth->id;
            }
            $apparel->name = $request->post('name');
            $apparel->apparel_category_id = $request->post('apparel_category_id');
            $apparel->description = $request->post('description');
            $apparel->tokopedia_url = $request->post('tokopedia_url');
            $apparel->shopee_url = $request->post('shopee_url');
            $apparel->bukalapak_url = $request->post('bukalapak_url');
            $apparel->lazada_url = $request->post('lazada_url');
            $apparel->blibli_url = $request->post('blibli_url');
            $apparel->sizes = $request->post('sizes') && $allSizes === 0 ? json_encode($request->post('sizes')): null;
            $apparel->is_all_sizes = $allSizes;
            $apparel->status = $status;
            if ($request->file('image_thumbnail')){
                $uploadImage = $this->uploadImageToImageKit($request->file('image_thumbnail'));
                $apparel->image_thumbnail = $uploadImage->url;
            }
            $apparel->updated_by = $auth->id;
            $apparel->save();
            if ($request->has('uuid')){
                ApparelImage::where('apparel_id', $apparel->id)->delete();
            } 
            if ($request->post('image_galleries')) {
                foreach($request->post('image_galleries') as $value) {
                    $img_gallery = new ApparelImage;
                    $img_gallery->image = $value;
                    $img_gallery->apparel_id = $apparel->id;
                    $img_gallery->created_by = $auth->id;
                    $img_gallery->updated_by = $auth->id;
                    $img_gallery->save();
                }
            }
            DB::commit();
            return redirect($this->post_redirect_prefix)->with('toast_success', ' Apparel Successfully '.$action.'!');
        }catch(Exception $e){
            DB::rollBack();
            return redirect()->back()->with('toast_error',$e->message())->withInput();
        }
    }
}
