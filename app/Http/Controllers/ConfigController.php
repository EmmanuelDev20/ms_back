<?php

namespace App\Http\Controllers;

use App\Models\Config;
use App\Http\Controllers\Controller;
use App\Services\ImageService;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    protected ImageService $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index_api()
    {
        $configs = Config::all();
        $result = $configs[0]->translate('en');
        return $configs[0];
    }

    public function index()
    {
        $configArray = Config::all();

        $spanish_data = [];
        $english_data = [];

        foreach ($configArray[0]->translations as $conf) :
            if ($conf->locale === 'es') :
                $spanish_data = $conf;
            else :
                $english_data = $conf;
            endif;
        endforeach;

        $config = $configArray[0];
        return view('admin.config.index', compact('config', 'spanish_data', 'english_data'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        return;
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
            'about_image' => 'image',
        ]);

        $imageFields = [
            'home_image',
            'first_image',
            'second_image',
            'third_image',
            'about_image',
        ];

        foreach ($imageFields as $field) {
            if ($request->file($field)) {
                if ($config->$field) {
                    $this->imageService->delete($config->$field);
                }
                $config->$field = $this->imageService->upload($request->file($field));
            }
        }

        foreach ($config->translations as $conf) :
            if ($conf->locale === 'es') :
                $conf->home_description = $request->home_description;
                $conf->about_us_description = $request->about_us_description;
            else :
                $conf->home_description = $request->home_description_english;
                $conf->about_us_description = $request->about_us_description_english;
            endif;
        endforeach;

        $config->save();

        return redirect()->route('config.index');
    }

    public function destroy(Config $config)
    {
        //
    }
}
