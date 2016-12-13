<?php

namespace Microit\LaravelAdminRetailTelecoms\Models;

use Microit\LaravelAdminBaseStandalone\BaseModel;

class InteractionAction extends BaseModel
{
    protected $visible = [
        'id',
        'interaction_id',
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'interaction_id',
    ];

    protected $queryable = [
        'id',
        'interaction_id',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function interaction()
    {
        return $this->belongsTo(Interaction::class);
    }

    public function interactionable()
    {
        return $this->morphTo();
    }
}
