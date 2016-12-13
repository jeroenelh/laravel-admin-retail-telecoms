<?php

namespace Microit\LaravelAdminRetailTelecoms\Models;

use Microit\LaravelAdminBaseStandalone\BaseModel;
use Microit\LaravelAdminBaseStandalone\HasMediaTrait;

class RepairCategory extends BaseModel
{
    use HasMediaTrait;

    protected $visible = [
        'id',
        'display_title',
        'slug',
        'description',
        'created_at',
        'updated_at',
        'store_id',
    ];

    protected $fillable = [
        'display_title',
        'slug',
        'description',
        'medium_id',
        'store_id',
    ];

    protected $queryable = [
        'id',
        'display_title',
        'slug',
        'description',
        'store_id',
    ];

    protected $appends = [
        //
    ];

    public function devices()
    {
        return $this->belongsToMany(Device::class, 'device_repair')->withPivot(['price', 'duration']);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function scopeCurrentStore($query)
    {
        return $query->where('store_id', session('store'));
    }
}
