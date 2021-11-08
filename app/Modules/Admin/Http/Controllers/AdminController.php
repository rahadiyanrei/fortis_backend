<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use app\User;
use Sentinel;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $admin = User::where(function($query) use($request){
            if ($request->get('global_search')) {
                $query
                ->whereRaw("fullname like '%".$request->get('global_search')."%'");
            }
            $query->where('id','!=', 1);
        })->orderBy('created_at','desc')
        ->paginate(10)
        ->withQueryString();
        return view("Admin::index")->with('data', $admin);
    }

    public function detail($id){
        $default = User::where('id', $id)->first();
        $data_response = [
            'data_detail' => $default,
            'id' => $id
        ];
        return view("Admin::form")->with($data_response);
    }

    public function form() {
        $default = new User;
        $data_response = [
            'data_detail' => $default,
        ];
        return view("Admin::form")->with($data_response);
    }

    public function post(Request $request){
        $action = 'Created';
        if ($request->has('id')){
            $action = 'Updated';
            $admin = Sentinel::findById($request->post('id'));
            if ($request->post('password')){
                $credentials = [
                    'email'    => $request->post('email'),
                    'password' => $request->post('password'),
                    'fullname' => $request->post('fullname')
                ];
            } else {
                $credentials = [
                    'email'    => $request->post('email'),
                    'fullname' => $request->post('fullname')
                ];
            }
            Sentinel::update($admin, $credentials);
        } else {
            $credentials = [
                'email'    => $request->post('email'),
                'password' => $request->post('password'),
                'fullname' => $request->post('fullname')
            ];
            Sentinel::register($credentials);
        }
        return redirect('admin')->with('toast_success', ' Admin Successfully '.$action.'!');
    }

    public function profile() {
        $user = Sentinel::getUser();
        $data_response = [
            'user' => $user,
        ];
        return view("Admin::profile")->with($data_response);
    }

    public function profileUpdate(Request $request) {
        $user = Sentinel::getUser();
        $newPassword = password_hash($request->post('old_password'), PASSWORD_DEFAULT);
        if (!Hash::check($request->old_password, $user->password)) {
            return redirect()->back()->with('toast_error','Old password does not match')->withInput();
        }
        $credentials = [
            'password' => $request->password
        ];
        Sentinel::update($user, $credentials);
        return redirect()->back()->with('toast_success', 'Password Successfully Changed!');
    }

    public function banned($id) {
        $admin = Sentinel::findById($id);
        $credentials = [
            'status'    => 0,
        ];
        Sentinel::update($admin, $credentials);
        return redirect()->back()->with('toast_success', 'Admin '.$admin->fullname.' Successfully Banned!');
    }
}
