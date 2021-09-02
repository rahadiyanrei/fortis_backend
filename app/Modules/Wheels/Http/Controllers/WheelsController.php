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
use App\WheelColorImage;
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
            17,
            18,
            19,
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
        $stepper = 1;
        if ($request->has('created')) {
            $stepper = 3;
        }
        $getData = Wheel::where("uuid",$uuid)->with(['sizes','colors' => function($q){
            $q->with(['image']);
        }])->first();
        $data_response = [
            'wheel_diameter' => $this->wheel_diameter,
            'wheel_brand' => $this->namePath,
            'post_action' => $this->post_redirect_prefix.'/'.$this->namePath.'/update',
            'image_thumbnail_dimension' => $this->image_thumbnail_dimension,
            'uuid' => $uuid,
            'data_detail' => $getData,
            'stepper' => $stepper,
            'action' => 'view',
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
            'stepper' => 1,
            'action' => 'create',
        ];
        return view("Wheels::".$this->namePath."/form", $data_response);
    }

    public function wheelCreateOrUpdateAction(Request $request,$brand){
        // dd($request->all());
        $action = 'Created';
        $auth = Sentinel::getUser();
        $is_new = 0;
        $is_discontinued = 0;
        $status = 0;
        if ($request->post('name') === null) {
            return redirect()->back()->with('toast_error','Wheel Name is Required!')->withInput();
        }
        if ($request->post('PCD') === null) {
            return redirect()->back()->with('toast_error','PCD is Required!')->withInput();
        }
        if ($request->post('ET') === null) {
            return redirect()->back()->with('toast_error','ET is Required!')->withInput();
        }
        if ($request->post('hub') === null) {
            return redirect()->back()->with('toast_error','Hub is Required!')->withInput();
        }
        if ($request->post('about') === null) {
            return redirect()->back()->with('toast_error','About is Required!')->withInput();
        }
        if (!$request->has('diameter')) {
            return redirect()->back()->with('toast_error','Diameter is Required!')->withInput();
        }
        
        if (!$request->file('image') && !$request->has('uuid')) {
            return redirect()->back()->with('toast_error','Image Thumbnail is Required!')->withInput();
        }

        $reconArrayDiameter = $this->reconstructionArray($request->all()['diameter']);
        // $reconArray = $this->reconstructionArray($request->all()['wheel_color']);
        
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
            $wheel->PCD = $request->post('PCD');
            $wheel->ET = $request->post('ET');
            $wheel->hub = $request->post('hub');
            $wheel->about = $request->post('about');
            $wheel->status = $status;
            $wheel->updated_by = $auth->id;
            $wheel->save();
            $pluckIDWheelColor = [];
            
            // soft delete wheel size and wheel color
            if ($request->has('uuid')){
                $pluckIDWheelColor = WheelColor::where('wheel_id', $wheel->id)->orderBy('id','asc')->get()->pluck(['id']);
                // dd($pluckIDWheelColor);
                WheelSize::where('wheel_id', $wheel->id)->delete();
                WheelColor::where('wheel_id', $wheel->id)->delete();
                WheelColorImage::whereIn('wheel_color_id', $pluckIDWheelColor)->delete();
            }

            foreach($reconArrayDiameter as $key => $value){
                $reconArrayDiameter[$key]["wheel_id"] = $wheel->id;
                $reconArrayDiameter[$key]["created_by"] = $auth->id;
                $reconArrayDiameter[$key]["updated_by"] = $auth->id;
                // array_push($wheelSizeData,array("diameter" => $value, "wheel_id" => $wheel->id, "created_by" => $auth->id, "updated_by" => $auth->id));
            }
            WheelSize::insert($reconArrayDiameter);
            foreach($request->post('wheel_color') as $key => $value){
                $wheelColor = new WheelColor;
                $wheelColor->wheel_id = $wheel->id;
                $wheelColor->color_name = $value['color_name'];
                $wheelColor->color_hex = $value['color_hex'];
                $wheelColor->created_by = $auth->id;
                $wheelColor->updated_by = $auth->id;
                $wheelColor->save();
                if (isset($request->file('wheel_color')[$key])) {
                    foreach($request->file('wheel_color')[$key]['image'] as $keys => $item) {
                        $uploadImageColorWheel = $this->uploadImageToImageKit($item);
                        $wheelColorImage = new WheelColorImage;
                        $wheelColorImage->wheel_color_id = $wheelColor->id;
                        $wheelColorImage->image = $uploadImageColorWheel->url;
                        $wheelColorImage->created_by = $auth->id;
                        $wheelColorImage->updated_by = $auth->id;
                        $wheelColorImage->save();
                    }
                } else {
                    $getImageColor = WheelColorImage::where('wheel_color_id', $pluckIDWheelColor[$key])->withTrashed()->get();
                    foreach($getImageColor as $idx => $dataImg) {
                        $wheelColorImage = new WheelColorImage;
                        $wheelColorImage->wheel_color_id = $wheelColor->id;
                        $wheelColorImage->image = $dataImg->image;
                        $wheelColorImage->created_by = $auth->id;
                        $wheelColorImage->updated_by = $auth->id;
                        $wheelColorImage->save();
                    }
                    // dd($request->post('wheel_color'));
                }
            }
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
