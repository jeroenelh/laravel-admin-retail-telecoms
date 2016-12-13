<?php

namespace Microit\LaravelAdminRetailTelecoms\Models;

use Microit\LaravelAdminBaseStandalone\BaseModel;
use Microit\LaravelAdminBaseStandalone\HasMediaTrait;

class Kiosk extends BaseModel
{
    protected $visible = [
        'id',
        'store_id',
        'is_active',
        'display_title',
        'slug',
        'connection_key',
        'browser',
        'browser_version',
        'os',
        'os_version',
        'device',
        'language',
        'need_refresh',
        'need_reload_images',
        'need_reload_accessories',
        'last_connection',
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'store_id',
        'is_active',
        'display_title',
        'slug',
        'connection_key',
        'browser',
        'browser_version',
        'os',
        'os_version',
        'device',
        'language',
        'need_refresh',
        'need_reload_images',
        'need_reload_accessories',
        'last_connection',
    ];

    protected $queryable = [
        'id',
        'store_id',
        'is_active',
        'display_title',
        'slug',
        'connection_key',
        'browser',
        'browser_version',
        'os',
        'os_version',
        'device',
        'language',
        'need_refresh',
        'need_reload_images',
        'need_reload_accessories',
        'last_connection',
    ];

    protected $attributes = [
        'is_active' => true,
        'need_refresh' => false,
        'need_reload_images' => false,
        'need_reload_accessories' => false,
    ];

    protected $casts = [
        'is_active' =>                  'boolean',
        'need_refresh' =>               'boolean',
        'need_reload_images' =>         'boolean',
        'need_reload_accessories' =>    'boolean',
        'last_connection' =>            'datetime',
    ];

    protected $appends = [
        'os_full',
        'browser_full',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function activations()
    {
        return $this->hasMany(KioskActivation::class);
    }

    public function interactions()
    {
        return $this->hasMany(Interaction::class);
    }

    public function scopeCurrentStore($query)
    {
        return $query->where('store_id', session('store'));
    }

    public function getOsFullAttribute()
    {
        return $this->os." ".$this->os_version;
    }

    public function getBrowserFullAttribute()
    {
        return $this->browser." ".$this->browser_version;
    }

    public function generateNewConnectionKey()
    {
        $key = md5(time()*rand(0,PHP_INT_MAX));
        $this->setAttribute('connection_key', $key);
        $this->save();
        return $key;
    }
}
