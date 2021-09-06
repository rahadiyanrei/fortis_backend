<?php

namespace App\Modules\Email\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Stream;
class EmailController extends Controller
{
    public function __construct(){
        $this->client = new Client(['base_uri' => 'https://api.postmarkapp.com']);
    }
    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function send(Request $request)
    {
        $send = $this->client->request('POST','email',[
            'headers'   => [
                'X-Postmark-Server-Token'   => env('POSTMARK_TOKEN'),
                'Content-Type'              => 'application/json'
            ],
            'body'      => json_encode($request->all())
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Success Send Email'
        ]);
    }
}
