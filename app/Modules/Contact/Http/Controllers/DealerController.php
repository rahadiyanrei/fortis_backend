<?php

namespace App\Modules\Contact\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Province;
use App\Dealer;
use Sentinel;
use Illuminate\Support\Str;

class DealerController extends Controller
{

    public function __construct() {
        $this->paginate = 10;
    }

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $dealers = Dealer::where(function($query) use($request) {
            if ($request->get('global_search')) {
                $query->whereRaw("name like '%".$request->get('global_search')."%'");
            }
        })->orderBy('created_at','desc')
        ->with(['createdBy','updatedBy','province'])
        ->paginate($this->paginate)
        ->withQueryString();
        return view("Contact::dealer.index")->with('data',$dealers);
    }

    public function form()
    {
        $province = Province::orderBy('name','asc')->get();
        $dealer = new Dealer;
        $data_response = [
          'province' => $province,
          'dealer' => $dealer
        ];
        return view("Contact::dealer.form")->with($data_response);
    }

    public function post(Request $request) {
        $action = 'Created!';
        $status = 0;
        if (!$request->post('lat') || !$request->post('long')){
            return redirect()->back()->with('toast_error','Please select location first!')->withInput();
        }
        if ($request->has('status')) {
            $status = 1;
        }
        $user = Sentinel::getUser();
        $dealer = new Dealer;
        if ($request->has('uuid')) {
            $dealer = $dealer->where('uuid', $request->post('uuid'))->first();
            $action = 'Updated!';
        } else {
            $dealer->uuid = Str::uuid();
            $dealer->created_by = $user->id;
        }
        $dealer->name = $request->post('name');
        $dealer->address = $request->post('address');
        $dealer->lat = $request->post('lat');
        $dealer->long = $request->post('long');
        $dealer->email = $request->post('email');
        $dealer->phone_number = $request->post('phone_number');
        $dealer->province_id = $request->post('province_id');
        $dealer->updated_by = $user->id;
        $dealer->status = $status;
        $dealer->save();
        return redirect('/contact/dealer')->with('toast_success', 'Dealer Successfully '.$action);
    }

    public function view($uuid) {
        $dealer = Dealer::where('uuid', $uuid)->first();
        $province = Province::get();
        $data_response = [
          'province' => $province,
          'dealer' => $dealer
        ];
        return view("Contact::dealer.form")->with($data_response);
    }
}
