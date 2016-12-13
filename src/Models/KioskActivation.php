<?php

namespace Microit\LaravelAdminRetailTelecoms\Models;

use Illuminate\Support\Facades\DB;
use Microit\LaravelAdminBaseStandalone\BaseModel;

class KioskActivation extends BaseModel
{
    protected $visible = [
        'id',
        'kiosk_id',
        'activation_key',
        'is_used',
        'is_expired',
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'kiosk_id',
        'activation_key',
        'is_used',
        'is_expired',
    ];

    protected $queryable = [
        'id',
        'kiosk_id',
        'activation_key',
        'is_used',
        'is_expired',
    ];

    protected $attributes = [
        'is_used' => false,
        'is_expired' => false,
    ];

    protected $casts = [
        'is_used' => 'boolean',
        'is_expired' => 'boolean',
    ];

    protected $appends = [
        //
    ];

    public function __construct(array $attributes = [])
    {
        $this->setAttribute('activation_key', rand(100000,999999));
        parent::__construct($attributes);
    }

    public function kiosk()
    {
        return $this->belongsTo(Kiosk::class);
    }

    public function scopePossibleActivations($query)
    {
        $query->where('is_used', 0)->where('is_expired', 0)->where('created_at', '>', date('Y-m-d H:i:s', time()-60*5));
    }
}
