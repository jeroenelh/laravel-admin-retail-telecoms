<?php

namespace Microit\LaravelAdminRetailTelecoms\Requests;

use Illuminate\Http\Request;

class BrandRequest extends \App\Http\Requests\Request
{
    public function rules(Request $request)
    {
        switch($this->method()) {
            case 'POST':
                return [
                    'display_title' => 'required',
                    'slug' => 'required|unique:brands,slug,null,id,store_id,'.session('store'),
                    'file' => 'required|file',
                ];
            case 'PUT':
            case 'PATCH':
                return [
                    'display_title' => 'required',
                    'slug' => 'required|unique:brands,slug,'.$request->id.',id,store_id,'.session('store'),
                    'file' => 'file',
                ];
        }

        return [];

    }
    public function authorize()
    {
        return \Auth::check();
    }
}