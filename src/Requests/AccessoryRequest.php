<?php

namespace Microit\LaravelAdminRetailTelecoms\Requests;

use Illuminate\Http\Request;

class AccessoryRequest extends \App\Http\Requests\Request
{
    public function rules(Request $request)
    {
        switch($this->method()) {
            case 'POST':
                return [
                    'display_title' => 'required',
                    'accessory_category_id' => 'required|exists:accessory_categories,id',
                    'article_number' => 'required',
                    'brand' => 'required',
                    'price' => 'required',
                    'ean' => 'required:unique:accessories,ean',
                ];
            case 'PUT':
            case 'PATCH':
                return [
                    'display_title' => 'required',
                    'accessory_category_id' => 'required|exists:accessory_categories,id',
                    'article_number' => 'required',
                    'brand' => 'required',
                    'price' => 'required',
                    'ean' => 'required:unique:accessories,ean,'.$request->id,
                ];
        }

        return [];

    }
    public function authorize()
    {
        return \Auth::check();
    }
}