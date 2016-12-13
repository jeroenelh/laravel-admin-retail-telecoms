<?php

namespace Microit\LaravelAdminRetailTelecoms\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Microit\LaravelAdminBaseStandalone\BaseModel;

class Storeuser extends BaseModel
{
    protected $table = 'users';
    
    protected $visible = [
        'id',
        'name',
        'email',
        'password',
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $queryable = [
        'id',
        'name',
        'email',
        'password',
    ];

    protected $dates = [
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [

    ];

    protected $form = [
        [
            'name',
            'email',
            'password' => [
                'type' => 'password',
            ],
        ],
    ];

}