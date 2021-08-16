<?php

namespace App\Modules\Wheels\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use ImageKit\ImageKit;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use App\Wheel;
use App\WheelSize;
use App\WheelColor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Sentinel;

class WheelsController extends Controller
{
    public function __construct(Request $request)
    {
        // dd(phpinfo());
        $this->wheel_diameter = [
            10,
            12,
            13,
            14,
            15,
            16,
            16.5,
            17,
            18,
            19,
            19.5,
            20,
            21,
            22,
            23,
            24
        ];

        $this->post_redirect_prefix = '/wheel';
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

        $this->namePath = $request->route()->getName();
    }

    private function reconstructionArray($array) {
        $newArray = array();
        foreach (array_keys($array) as $fieldKey) {
            foreach ($array[$fieldKey] as $key=>$value) {
                $newArray[$key][$fieldKey] = $value;
            }
        }
        return $newArray;
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
    
    public function wheelIndex(Request $request)
    {
        $dataPako = Wheel::where("brand",$this->namePath)->where(function($query) use($request){
            if ($request->get('global_search')) $query->whereRaw("name like '%".$request->get('global_search')."%'");
        })->with(['colors','sizes'])
        ->orderBy('created_at','desc')
        ->paginate($this->paginate)
        ->withQueryString();

        return view("Wheels::".$this->namePath."/index")->with('data', $dataPako);
    }

    public function wheelView(Request $request, $uuid) {
        $getData = Wheel::where("uuid",$uuid)->with(['colors','sizes'])->first();
        $data_response = [
            'wheel_diameter' => $this->wheel_diameter,
            'wheel_brand' => $this->namePath,
            'post_action' => $this->post_redirect_prefix.'/'.$this->namePath.'/update',
            'image_thumbnail_dimension' => $this->image_thumbnail_dimension,
            'uuid' => $uuid,
            'data_detail' => $getData,
        ];
        return view("Wheels::view")->with($data_response);
    }

    public function wheelCreateFormView(Request $request)
    {
        $default = new Wheel;
        $data_response = [
            'wheel_diameter' => $this->wheel_diameter,
            'wheel_brand' => $this->namePath,
            'post_action' => $this->post_redirect_prefix.'/'.$this->namePath.'/create',
            'image_thumbnail_dimension' => $this->image_thumbnail_dimension,
            'data_detail' => $default,
        ];
        return view("Wheels::".$this->namePath."/form", $data_response);
    }

    public function wheelCreateOrUpdateAction(Request $request,$brand){
        $action = 'Created';
        $auth = Sentinel::getUser();
        $is_new = 0;
        $is_discontinued = 0;
        $status = 0;
        if (!$request->has('diameter')) {
            return redirect()->back()->with('toast_error','Diameter is Required!')->withInput();
        }
        
        if (!$request->file('image') && !$request->has('uuid')) {
            return redirect()->back()->with('toast_error','Image Thumbnail is Required!')->withInput();
        }            
        $reconArray = $this->reconstructionArray($request->all()['wheel_color']);
        
        if ($request->has('is_new_release')) {
            $is_new = 1;
        }

        if ($request->has('is_discontinued')) {
            $is_discontinued = 1;
        }

        if ($request->has('status')) {
            $status = 1;
        }
        
        try{
            DB::beginTransaction();
            $wheel = new Wheel;
            if ($request->has('uuid')){
                $wheel = $wheel->where('uuid', $request->post('uuid'))->first();
                $action = "Updated";
            } else {
                $wheel->uuid = Str::uuid();
                $wheel->created_by = $auth->id;
            }
            $wheel->name = $request->post('name');
            if ($request->file('image')){
                $uploadImage = $this->uploadImageToImageKit($request->file('image'));
                $wheel->image = $uploadImage->url;
            }
            $wheel->is_new_release = $is_new;
            $wheel->is_discontinued = $is_discontinued;
            $wheel->brand = $brand;
            $wheel->about = $request->post('about');
            $wheel->status = $status;
            $wheel->updated_by = $auth->id;
            $wheel->save();
            
            // soft delete wheel size and wheel color
            if ($request->has('uuid')){
                WheelSize::where('wheel_id', $wheel->id)->delete();
                WheelColor::where('wheel_id', $wheel->id)->delete();
            }

            $wheelSizeData = array();
            foreach($request->post('diameter') as $key => $value){
                array_push($wheelSizeData,array("diameter" => $value, "wheel_id" => $wheel->id, "created_by" => $auth->id, "updated_by" => $auth->id));
            }
            WheelSize::insert($wheelSizeData);

            foreach($reconArray as $key => $value){
                $reconArray[$key]["wheel_id"] = $wheel->id;
                $reconArray[$key]["created_by"] = $auth->id;
                $reconArray[$key]["updated_by"] = $auth->id;
            }
            WheelColor::insert($reconArray);
            DB::commit();
            return redirect($this->post_redirect_prefix.'/'.$request->post('brand'))->with('toast_success', ucfirst($request->post('brand')).' Wheel Successfully '.$action.'!');
        } catch(Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('toast_error',$e->message())->withInput();
        }
    }

    public function inkoIndex(Request $request)
    {
        $dataInko = Wheel::where("brand","inko")->where(function($query) use($request){
            if ($request->get('global_search')) $query->whereRaw("name like '%".$request->get('global_search')."%'");
        })->with(['colors','sizes'])
        ->orderBy('created_at','desc')
        ->paginate($this->paginate)
        ->withQueryString();
        return view("Wheels::inko/index")->with('data', $dataInko);
    }

    public function inkoCreateForm() {
        $default = new Wheel;
        $data_response = [
            'wheel_diameter' => $this->wheel_diameter,
            'wheel_brand' => 'inko',
            'post_action' => $this->post_redirect_prefix.'/inko/create',
            'image_thumbnail_dimension' => $this->image_thumbnail_dimension,
            'data_detail' => $default,
        ];
        return view("Wheels::inko/form", $data_response);
    }
}
