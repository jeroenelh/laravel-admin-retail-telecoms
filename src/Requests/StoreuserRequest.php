<?php

namespace Microit\LaravelAdminRetailTelecoms\Requests;

use Illuminate\Http\Request;

class StoreuserRequest extends \App\Http\Requests\Request
{
    public function rules(Request $request)
    {
        switch($this->method()) {
            case 'POST':
                return [
                    'name' => 'required',
                    'email' => 'required|unique:users,email',
                    'password' => '',
                ];
            case 'PUT':
            case 'PATCH':
                return [
                    'name' => 'required',
                    'email' => 'required|unique:users,email,'.$request->id,
                    'password' => '',
                ];
        }

        return [];

    }
    public function authorize()
    {
        return \Auth::check();
    }
}