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

class BlogController extends Controller
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
    }

    public function imageUploadContent(Request $request) {
        $uploadImage = $this->uploadImageToImageKit($request->file('image'),'blog/content');
        return response()->json([
            'url' => $uploadImage->url
        ]);
    }

    public function imageUploadGallery(Request $request) {
        $uploadImage = $this->uploadImageToImageKit($request->file('image'),'gallery/content');
        return response()->json([
            'url' => $uploadImage->url
        ]);
    }

    private function uploadImageToImageKit($image, $folder) {

        $now = Carbon::now()->toDateString();
        $prefix = $folder;
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
        $blog = Blog::where(function($query) use($request){
            if ($request->get('global_search')) {
                $query
                ->whereRaw("title like '%".$request->get('global_search')."%'");
            }
        })->orderBy('created_at','desc')
        ->with(['createdBy'])
        ->paginate($this->paginate)
        ->withQueryString();
        return view("Blog::index")->with('data', $blog);
    }

    public function form() {
        $default = new Blog;
        $data_response = [
            'image_thumbnail_dimension' => $this->image_thumbnail_dimension,
            'data_detail' => $default,
        ];
        return view("Blog::form", $data_response);
    }

    public function formUpdate($uuid) {
        $data = Blog::where('uuid',$uuid)->first();
        $data_response = [
            'image_thumbnail_dimension' => $this->image_thumbnail_dimension,
            'data_detail' => $data,
            'uuid' => $uuid,
        ];
        return view("Blog::form", $data_response);
    }

    public function post(Request $request) {
        $auth = Sentinel::getUser();
        $action = 'Created';
        if (!$request->file('image') && !$request->has('uuid')) {
            return redirect()->back()->with('toast_error','Image Banner is Required!')->withInput();
        }
        $status = 0;
        if ($request->has('status')) {
            $status = 1;
        }
        try{
            DB::beginTransaction();
            $blog = new Blog;
            if ($request->has('uuid')){
                $blog = $blog->where('uuid', $request->post('uuid'))->first();
                $action = "Updated";
            } else {
                $blog->uuid = Str::uuid();
                $blog->created_by = $auth->id;
                $random = Str::random(10);
                $blog->slug = Str::slug($request->post('title').' '.$random, '-');
            }
            $blog->title = $request->post('title');
            $blog->content = $request->post('content');
            $blog->status = $status;
            if ($request->file('image')){
                $uploadImage = $this->uploadImageToImageKit($request->file('image'),'blog');
                $blog->image = $uploadImage->url;
            }
            $blog->updated_by = $auth->id;
            $blog->save();
            DB::commit();
            return redirect('blog')->with('toast_success', ' Blog Successfully '.$action.'!');
        }catch(Exception $e){
            DB::rollBack();
            return redirect()->back()->with('toast_error',$e->message())->withInput();
        }
    }

    public function delete($uuid) {
        Blog::where('uuid', $uuid)->delete();
        return redirect('blog')->with('toast_success', ' Blog Successfully Deleted!');
    }
}
