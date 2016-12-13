<?php

namespace Microit\LaravelAdminRetailTelecoms\Requests;

use Illuminate\Http\Request;

class RepairCategoryRequest extends \App\Http\Requests\Request
{
    public function rules(Request $request)
    {
        switch($this->method()) {
            case 'POST':
                return [
                    'display_title' => 'required',
                    'slug' => 'required|unique:repair_categories,slug,null,id,store_id,'.session('store'),
                ];
            case 'PUT':
            case 'PATCH':
                return [
                    'display_title' => 'required',
                    'slug' => 'required|unique:repair_categories,slug,'.$request->id.',id,store_id,'.session('store'),
                ];
        }

        return [];

    }
    public function authorize()
    {
        return \Auth::check();
    }
}