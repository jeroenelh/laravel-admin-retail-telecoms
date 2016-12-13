<?php

namespace Microit\LaravelAdminRetailTelecoms\Models;

use Microit\LaravelAdminBaseStandalone\BaseModel;
use Microit\LaravelAdminBaseStandalone\HasMediaTrait;

class Brand extends BaseModel
{
    use HasMediaTrait;

    protected $visible = [
        'id',
        'display_title',
        'slug',
        'store_id',
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'display_title',
        'slug',
        'medium_id',
        'store_id',
    ];

    protected $queryable = [
        'id',
        'display_title',
        'slug',
        'store_id',
    ];

    protected $appends = [
        //
    ];

    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function InteractionActions()
    {
        return $this->morphMany(InteractionAction::class, 'interactionable');
    }

    public function scopeCurrentStore($query)
    {
        return $query->where('store_id', session('store'));
    }
}
