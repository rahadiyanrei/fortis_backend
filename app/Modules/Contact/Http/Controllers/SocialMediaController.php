<?php

namespace App\Modules\Contact\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\SocialMedia;
use Sentinel;
class SocialMediaController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $data = new SocialMedia;
      $getData = SocialMedia::first();
      if ($getData) {
        $data = $getData;
      }
      return view("Contact::social_media.index")->with(['data' => $data]);
    }

    public function post(Request $request){
      $auth = Sentinel::getUser();
      if ($request->post('id')) {
        SocialMedia::where('id',$request->post('id'))->update([
          'facebook' => $request->post('facebook'),
          'linkedin' => $request->post('linkedin'),
          'youtube' => $request->post('youtube'),
          'twitter' => $request->post('twitter'),
          'instagram' => $request->post('instagram'),
          'updated_by' => $auth->id,
        ]);
      } else {
        SocialMedia::updateOrCreate([
          'facebook' => $request->post('facebook'),
          'linkedin' => $request->post('linkedin'),
          'youtube' => $request->post('youtube'),
          'twitter' => $request->post('twitter'),
          'instagram' => $request->post('instagram'),
          'created_by' => $auth->id,
          'updated_by' => $auth->id,
        ]);
      }
      return redirect('/contact/social_media')->with('toast_success', 'Social Media Successfully updated!');;
    }

    public function listAPI() {
      $get = SocialMedia::first();
      $data = [
        'linkedIn' => $get ? $get->linkedin : null,
        'twitter' => $get ? $get->twitter : null,
        'facebook' => $get ? $get->facebook : null,
        'youtube' => $get ? $get->youtube : null,
        'instagram' => $get ? $get->instagram : null,
      ];
      $response = [
        'data' => $data,
        'status' => true,
        'message' => 'success get Social Media'
      ];
      return response()->json($response);
    }
}
