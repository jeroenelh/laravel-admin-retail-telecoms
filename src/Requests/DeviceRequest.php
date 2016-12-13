<?php

namespace Microit\LaravelAdminRetailTelecoms\Requests;

use Illuminate\Http\Request;

class DeviceRequest extends \App\Http\Requests\Request
{
    public function rules(Request $request)
    {
        switch($this->method()) {
            case 'POST':
                return [
                    'display_title' => 'required',
                    'slug' => 'required|unique:devices,slug',
                    'brand_id' => 'required|exists:brands,id',
                ];
            case 'PUT':
            case 'PATCH':
                return [
                    'display_title' => 'required',
                    'slug' => 'required|unique:devices,slug,'.$request->id,
                    'brand_id' => 'required|exists:brands,id',
                ];
        }

        return [];

    }
    public function authorize()
    {
        return \Auth::check();
    }
}