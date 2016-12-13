<?php

namespace Microit\LaravelAdminRetailTelecoms\Models;

use Microit\LaravelAdminBaseStandalone\BaseModel;
use Microit\LaravelAdminBaseStandalone\HasMediaTrait;

class Accessory extends BaseModel
{
    use HasMediaTrait;

    protected $visible = [
        'id',
        'display_title',
        'slug',
        'accessory_category_id',
        'article_number',
        'ean',
        'sku',
        'description',
        'brand',
        'price',
        'store_id',
        'is_active',
        'created_by',
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'display_title',
        'slug',
        'accessory_category_id',
        'medium_id',
        'article_number',
        'ean',
        'sku',
        'description',
        'brand',
        'price',
        'store_id',
        'is_active',
        'created_by',
    ];

    protected $form = [
        [
            'display_title',
            'slug',
        ]
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $attributes = [
        'is_active' => true,
    ];

    protected $queryable = [
        'id',
        'display_title',
        'slug',
        'accessory_category_id',
        'article_number',
        'ean',
        'sku',
        'description',
        'brand',
        'price',
        'store_id',
        'is_active',
        'created_by',
    ];

    protected $appends = [
        //
    ];

    public function category()
    {
        return $this->belongsTo(AccessoryCategory::class, 'accessory_category_id');
    }

    public function devices()
    {
        return $this->belongsToMany(Device::class);
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
