<?php

namespace Microit\LaravelAdminRetailTelecoms\Requests;

use Illuminate\Http\Request;

class KioskRequest extends \App\Http\Requests\Request
{
    public function rules(Request $request)
    {
        switch($this->method()) {
            case 'POST':
                return [
                    'display_title' => 'required',
                    'slug' => 'required|unique:kiosks,slug',
                ];
            case 'PUT':
            case 'PATCH':
                return [
                    'display_title' => 'required',
                    'slug' => 'required|unique:brands,slug,'.$request->id,
                ];
        }

        return [];

    }
    public function authorize()
    {
        return \Auth::check();
    }
}