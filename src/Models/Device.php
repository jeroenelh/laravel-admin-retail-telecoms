<?php

namespace Microit\LaravelAdminRetailTelecoms\Models;

use Microit\LaravelAdminBaseStandalone\BaseModel;
use Microit\LaravelAdminBaseStandalone\HasMediaTrait;

class Device extends BaseModel
{
    use HasMediaTrait;

    protected $visible = [
        'id',
        'display_title',
        'slug',
        'brand_id',
        'is_active',
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'display_title',
        'slug',
        'brand_id',
        'medium_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $queryable = [
        'id',
        'display_title',
        'slug',
        'brand_id',
        'is_active',
    ];

    protected $attributes = [
        'is_active' => true,
    ];

    protected $appends = [
        //
    ];

    public function accessories()
    {
        return $this->belongsToMany(Accessory::class);
    }

    public function repairs()
    {
        return $this->belongsToMany(RepairCategory::class, 'device_repair')->withPivot(['price', 'duration']);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function InteractionActions()
    {
        return $this->morphMany(InteractionAction::class, 'interactionable');
    }

    public function scopeCurrentStore($query)
    {
        return $query->whereHas('brand', function($query) {
            $query->currentStore();
        });
    }
}
