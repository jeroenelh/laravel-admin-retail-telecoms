<?php

namespace Microit\LaravelAdminRetailTelecoms\Models;

use App\Brand;
use Illuminate\Database\Eloquent\SoftDeletes;
use Microit\LaravelAdminBaseStandalone\BaseModel;

class Store extends BaseModel
{
    use SoftDeletes;

    protected $visible = [
        'id',
        'display_name',
        'slug',
        'contact_name',
        'kiosks',
        'address_street',
        'address_number',
        'address_postalcode',
        'address_city',
        'address_lat',
        'address_lng',
        'email',
        'phone',
    ];

    protected $fillable = [
        'display_title',
        'slug',
        'contact_name',
        'kiosks',
        'address_street',
        'address_number',
        'address_postalcode',
        'address_city',
        'address_lat',
        'address_lng',
        'email',
        'phone',
    ];

    protected $queryable = [
        'display_title',
        'slug',
        'contact_name',
        'kiosks',
        'address_street',
        'address_number',
        'address_postalcode',
        'address_city',
        'address_lat',
        'address_lng',
        'email',
        'phone',
    ];

    protected $dates = [
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'kiosks' => 'integer',
    ];

    protected $form = [
        'Winkel' => [
            'display_title',
            'slug',
        ],
        'Contact' => [
            'contact_name' => [
                'cols' => 4
            ],
            'email' => [
                'cols' => 4
            ],
            'phone' => [
                'cols' => 4
            ],
        ],
        'Adres' => [
            'address_street' => [
                'cols' => 4
            ],
            'address_number' => [
                'cols' => 2
            ],
            'address_postalcode' => [
                'cols' => 2
            ],
            'address_city' => [
                'cols' => 4
            ],
            'address_lat' => [
                'cols' => 2
            ],
            'address_lng' => [
                'cols' => 2
            ],
        ],
        'Kiosk' => [
            'kiosks' => [
                'type' => 'number',
                'cols' => 2
            ],
        ]
    ];

    public function brands()
    {
        return $this->hasMany(Brand::class);
    }

}