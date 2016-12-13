<?php

namespace Microit\LaravelAdminRetailTelecoms\Models;

use Microit\LaravelAdminBaseStandalone\BaseModel;

class PriceConfiguratorRule extends BaseModel
{

    protected $visible = [
        'id',
        'price_configurator_id',
        'field',
        'operation',
        'value',
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'price_configurator_id',
        'field',
        'operation',
        'value',
    ];

    protected $queryable = [
        'id',
        'price_configurator_id',
        'field',
        'operation',
        'value',
    ];

    public function configurator()
    {
        return $this->belongsTo(PriceConfigurator::class, 'price_configurator_id');
    }
}
