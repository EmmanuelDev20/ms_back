<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public function index() {
        return view('admin.index');
    }

    public function hookNetlify() {
        $netlifyBuildHookURL = 'https://api.netlify.com/build_hooks/6549c5c3aa79b3474bc9ce18';

        $response = Http::post($netlifyBuildHookURL);

        if ($response->successful()) {
            // Redeployment triggered successfully
            // return response('Redeployment triggered: ' . $response->body());
            return redirect()->route('config.index');
        } else {
            // Handle the error if the request is not successful
            // return response('Error triggering redeployment: ' . $response->status() . ' - ' . $response->body(), 500);
            return redirect()->route('config.index');
        }
    }
}
