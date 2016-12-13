<?php

namespace Microit\LaravelAdminRetailTelecoms\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Microit\LaravelAdminRetailTelecoms\Models\AccessoryCategory;

class AccessoryCategoryUpdate extends Event
{
    use SerializesModels;

    public $category;
    public $deleted;

    /**
     * Accessory Category just updated
     *
     * @param  Order  $order
     * @return void
     */
    public function __construct(AccessoryCategory $category, $deleted = false)
    {
        $this->category = $category;
        $this->deleted = $deleted;
    }
}