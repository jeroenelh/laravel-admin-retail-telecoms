<?php

namespace Microit\LaravelAdminRetailTelecoms\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Microit\LaravelAdminRetailTelecoms\Models\Brand;

class BrandUpdate extends Event
{
    use SerializesModels;

    public $brand;
    public $deleted;

    /**
     * Brand just updated
     *
     * @param  Order  $order
     * @return void
     */
    public function __construct(Brand $brand, $deleted = false)
    {
        $this->brand = $brand;
        $this->deleted = $deleted;
    }
}