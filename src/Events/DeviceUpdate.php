<?php

namespace Microit\LaravelAdminRetailTelecoms\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Microit\LaravelAdminRetailTelecoms\Models\Device;

class DeviceUpdate extends Event
{
    use SerializesModels;

    public $device;
    public $deleted;

    /**
     * Device just updated
     *
     * @param  Order  $order
     * @return void
     */
    public function __construct(Device $device, $deleted = false)
    {
        $this->device = $device;
        $this->deleted = $deleted;
    }
}