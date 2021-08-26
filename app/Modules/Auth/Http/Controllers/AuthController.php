<?php

namespace App\Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Sentinel;
use RealRashid\SweetAlert\Facades\Alert;

class AuthController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        return view("Auth::welcome");
    }

    public function login(Request $request)
    {
        $credentials = $request->except('_token');
        if ($request->has('remember')){
            if(!Sentinel::authenticateAndRemember($credentials)){
                // Alert::toast('The email or password you entered is incorrect!', 'error');
                return redirect()->back()->with('toast_error','The email or password you entered is incorrect!')->withInput();
            }
        } else {
            if(!Sentinel::authenticate($credentials)){
                // Alert::toast('The email or password you entered is incorrect!', 'error');
                return redirect()->back()->with('toast_error','The email or password you entered is incorrect!')->withInput();
            }
        }
        return redirect("dashboard");
    }

    public function logout()
    {
        Sentinel::logout();
        return redirect("auth");
    }
}
