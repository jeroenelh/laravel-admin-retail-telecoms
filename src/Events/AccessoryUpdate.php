<?php

namespace Microit\LaravelAdminRetailTelecoms\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Microit\LaravelAdminRetailTelecoms\Models\Accessory;
use Microit\LaravelAdminRetailTelecoms\Models\Device;

class AccessoryUpdate extends Event
{
    use SerializesModels;

    public $accessory;
    public $deleted;

    /**
     * Accessory just updated
     *
     * @param  Order  $order
     * @return void
     */
    public function __construct(Accessory $accessory, $deleted = false)
    {
        $this->accessory = $accessory;
        $this->deleted = $deleted;
    }
}