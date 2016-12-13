<?php

namespace Microit\LaravelAdminRetailTelecoms\Requests;

use Illuminate\Http\Request;

class PriceConfiguratorRequest extends \App\Http\Requests\Request
{
    public function rules(Request $request)
    {
        return [
            'display_title' => 'required',
            'price' => 'required',
        ];

    }
    public function authorize()
    {
        return \Auth::check();
    }
}