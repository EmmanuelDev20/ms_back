<?php

namespace App\Http\Controllers;

use App\Models\Config;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class ConfigController extends Controller
{
    public function index_api()
    {
        $configs = Config::all();
        $result = $configs[0]->translate('en');
        // return $result;
        return $configs[0];
    }

    public function index()
    {
        $configArray = Config::all();

        $spanish_data = [];
        $english_data = [];

        foreach($configArray[0]->translations as $conf) :
            if($conf->locale === 'es' ) :
                $spanish_data = $conf;
            else :
                $english_data = $conf;
            endif;
        endforeach;
        // return Config::all();
        // $configArray = Config::all();
        $config = $configArray[0];
        return view('admin.config.index', compact('config', 'spanish_data', 'english_data'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        // return $config;
        // $request->validate([
        //     'home_image' => 'image',
        //     'first_image' => 'image',
        //     'second_image' => 'image',
        //     'third_image' => 'image',
        //     'about_image' => 'image'
        // ]);

        // // Storage
        // $home_image = $request->file('home_image')->store('public/images');
        // $first_image = $request->file('first_image')->store('public/images');
        // $second_image = $request->file('second_image')->store('public/images');
        // $third_image = $request->file('third_image')->store('public/images');
        // $about_image = $request->file('about_image')->store('public/images');

        // $home_image_url = Storage::url($home_image);
        // $first_image_url = Storage::url($first_image);
        // $second_image_url = Storage::url($second_image);
        // $third_image_url = Storage::url($third_image);
        // $about_image_url = Storage::url($about_image);

        // return $home_image_url;

        // Config::create([
        //  'url' => '/'
        // ]);

        // return redirect()->route('config.index');
        return;
        $configs = Config::all();

        $configs[0]->home_description = $request->home_description;

        $configs->save();
        return $configs[0];

        $request->validate([
            'home_image' => 'image',
            'first_image' => 'image',
            'second_image' => 'image',
            'third_image' => 'image',
            'about_image' => 'image'
        ]);

        if ($request->file('home_image')) {
            $name = Str::random(10) . $request->file('home_image')->getClientOriginalName();
            $ruta = storage_path() . '\app\public\images/' . $name;
            return $ruta;
            Image::make($request->file('home_image'))->resize(1200, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($ruta);

            Config::create([
                'url' => '/storage/images/' . $name
            ]);
        }


        return redirect()->route('config.index');
    }


    public function show(Config $config)
    {
        //
    }

    public function edit(Config $config)
    {
        //
    }

    public function update(Request $request, Config $config)
    {
        $request->validate([
            'home_description' => 'required',
            'about_us_description' => 'required',
            'home_image' => 'image',
            'first_image' => 'image',
            'second_image' => 'image',
            'third_image' => 'image',
            'about_image' => 'image'
        ]);

        if ($request->file('home_image')) {
            $name = Str::random(10) . $request->file('home_image')->getClientOriginalName();
            $probandoruta = public_path('storage/images/'.$name);
            // $ruta = storage_path() . '\app\public\images/' . $name;
            $ruta = public_path('storage/images/'.$name);
            Image::make($request->file('home_image'))->resize(1200, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($ruta);

            $new_home_image = '/storage/images/' . $name;
            $config->home_image = $new_home_image;
        }
        if ($request->file('first_image')) {
            $name = Str::random(10) . $request->file('first_image')->getClientOriginalName();
            // $ruta = storage_path() . '\app\public\images/' . $name;
            $ruta = public_path('storage/images/'.$name);
            Image::make($request->file('first_image'))->resize(1200, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($ruta);

            $new_first_image = '/storage/images/' . $name;
            $config->first_image = $new_first_image;
        }
        if ($request->file('second_image')) {
            $name = Str::random(10) . $request->file('second_image')->getClientOriginalName();
            // $ruta = storage_path() . '\app\public\images/' . $name;
            $ruta = public_path('storage/images/'.$name);
            Image::make($request->file('second_image'))->resize(1200, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($ruta);

            $new_second_image = '/storage/images/' . $name;
            $config->second_image = $new_second_image;
        }
        if ($request->file('third_image')) {
            $name = Str::random(10) . $request->file('third_image')->getClientOriginalName();
            // $ruta = storage_path() . '\app\public\images/' . $name;
            $ruta = public_path('storage/images/'.$name);
            Image::make($request->file('third_image'))->resize(1200, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($ruta);

            $new_third_image = '/storage/images/' . $name;
            $config->third_image = $new_third_image;
        }
        if ($request->file('about_image')) {
            $name = Str::random(10) . $request->file('about_image')->getClientOriginalName();
            // $ruta = storage_path() . '\app\public\images/' . $name;
            $ruta = public_path('storage/images/'.$name);
            Image::make($request->file('about_image'))->resize(1200, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($ruta);

            $new_about_image = '/storage/images/' . $name;
            $config->about_image = $new_about_image;
        }

        foreach($config->translations as $conf) :
            if($conf->locale === 'es' ) :
                $conf->home_description = $request->home_description;
                $conf->about_us_description = $request->about_us_description;
                else :
                $conf->home_description = $request->home_description_english;
                $conf->about_us_description = $request->about_us_description_english;
            endif;
        endforeach;

        // $config->home_description = $request->home_description;
        // $config->about_us_description = $request->about_us_description;

        $config->save();

        return redirect()->route('config.index');
    }

    public function destroy(Config $config)
    {
        //
    }
}
