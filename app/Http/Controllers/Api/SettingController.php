<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\CrudController;
use App\Http\Resources\SettingResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SettingController extends CrudController
{
    public $resource = SettingResource::class;

    public function __construct()
    {
        //
    }

    protected function validateInput(Request $request, $id = null)
    : array
    {
        $rules = [
            'items' => ['required']
        ];

        return $this->validate($request, $rules);
    }

    public function postCreate(Request $request)
    {
        $data = [
            "app_url" => $request->input('app_url'),
            "app_name" => $request->input('app_name', 'SattViki'),
            "text_logo_part_one" => $request->input('text_logo_part_one', 'Satt'),
            "text_logo_part_two" => $request->input('text_logo_part_two', 'Viki'),
            "home_page_title" => $request->input('home_page_title'),
            "job_application_number_prefix" => $request->input('job_application_number_prefix'),
            "about_us_content" => $request->input('about_us_content'),
            "user_has_to_verify_email_after_register" => (($request->input('user_has_to_verify_email_after_register') ? "yes" : "no"))
        ];

        if (File::exists(app_path('../front/public/settings.json'))) {
            File::replace(app_path('../front/public/settings.json'), json_encode($data));
        } else {
            File::put(app_path('../front/public/settings.json'), json_encode($data));
        }

        if (File::exists(public_path('settings.json'))) {
            File::replace(public_path('settings.json'), json_encode($data));
        } else {
            File::put(public_path('settings.json'), json_encode($data));
        }

        return response()->json([
            'success' => 1
        ]);
    }
}
