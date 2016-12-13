<?php

namespace Microit\LaravelAdminRetailTelecoms\Models;

use Microit\LaravelAdminBaseStandalone\BaseModel;

class PriceConfigurator extends BaseModel
{

    protected $visible = [
        'id',
        'display_title',
        'price',
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'display_title',
        'price',
    ];

    protected $queryable = [
        'id',
        'display_title',
        'price',
    ];

    public function rules()
    {
        return $this->hasMany(PriceConfiguratorRule::class, 'price_configurator_id');
    }
}
