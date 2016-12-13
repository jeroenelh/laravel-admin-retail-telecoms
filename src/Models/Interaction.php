<?php

namespace Microit\LaravelAdminRetailTelecoms\Models;

use Microit\LaravelAdminBaseStandalone\BaseModel;

class Interaction extends BaseModel
{
    protected $visible = [
        'id',
        'kiosk_id',
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'kiosk_id',
    ];

    protected $queryable = [
        'id',
        'kiosk_id',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function kiosk()
    {
        return $this->belongsTo(Kiosk::class);
    }

    public function actions()
    {
        return $this->hasMany(InteractionAction::class);
    }
}
