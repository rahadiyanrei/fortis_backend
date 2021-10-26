<?php

namespace App\Modules\Banner\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use ImageKit\ImageKit;
use App\Banner;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Sentinel;
use Illuminate\Support\Facades\DB;

class BannerController extends Controller
{

    public function __construct(Request $request)
    {
        $this->post_redirect_prefix = '/banner';
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
    public function index(Request $request)
    {
        $banner = Banner::where(function($query) use($request){
            if ($request->get('global_search')) {
                $query
                ->whereRaw("title like '%".$request->get('global_search')."%' or body like '%".$request->get('global_search')."%'");
            }
        })->orderBy('created_at','desc')
        ->with(['createdBy'])
        ->paginate($this->paginate)
        ->withQueryString();
        return view("Banner::index")->with('data', $banner);
    }

    private function uploadImageToImageKit($image) {

        $now = Carbon::now()->toDateString();
        $prefix = 'banner';
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

    private function validateLimitBannerActive(){
        $checkBanner = Banner::where('status',1)->count();
        if ($this->limit_banner <= $checkBanner){
            return false;
        }
        return true;
    }

    public function form(){
        $default = new Banner;
        $validate = $this->validateLimitBannerActive();
        if(!$validate){
            return redirect('/banner')->with('toast_error', 'You have reached a limit for Active Banner, the limit is '.$this->limit_banner);
        }
        $data_response = [
            'image_thumbnail_dimension' => $this->image_thumbnail_dimension,
            'data_detail' => $default,
        ];
        return view("Banner::form", $data_response);
    }

    public function view($uuid){
        $banner = Banner::where('uuid', $uuid)->first();
        $data_response = [
            'image_thumbnail_dimension' => $this->image_thumbnail_dimension,
            'data_detail' => $banner,
            'uuid' => $uuid
        ];
        return view("Banner::form", $data_response);
    }

    public function post(Request $request){
        $auth = Sentinel::getUser();
        $action = 'Created';
        if (!$request->has('uuid')){
            $validate = $this->validateLimitBannerActive();
            if(!$validate){
                return redirect()->back()->with('toast_error', 'You have reached a limit for Active Banner, the limit is '.$this->limit_banner)->withInput();
            }
        }
        if (!$request->file('image') && !$request->has('uuid')) {
            return redirect()->back()->with('toast_error','Image Banner is Required!')->withInput();
        } 
        $status = 0;
        if ($request->has('status')) {
            $status = 1;
        }
        try{
            DB::beginTransaction();
            $banner = new Banner;
            if ($request->has('uuid')){
                $banner = $banner->where('uuid', $request->post('uuid'))->first();
                $action = "Updated";
            } else {
                $banner->uuid = Str::uuid();
                $banner->created_by = $auth->id;
            }
            $banner->title = $request->post('title');
            $banner->body = $request->post('body');
            $banner->url_ref = $request->post('url_ref');
            $banner->status = $status;
            if ($request->file('image')){
                $uploadImage = $this->uploadImageToImageKit($request->file('image'));
                $banner->image = $uploadImage->url;
            }
            $banner->updated_by = $auth->id;
            $banner->save();
            DB::commit();
            return redirect($this->post_redirect_prefix)->with('toast_success', ' Banner Successfully '.$action.'!');
        }catch(Exception $e){
            DB::rollBack();
            return redirect()->back()->with('toast_error',$e->message())->withInput();
        }
    }

    public function delete($uuid) {
        Banner::where('uuid', $uuid)->delete();
        return redirect($this->post_redirect_prefix)->with('toast_success', ' Banner Successfully Deleted!');
    }
}
