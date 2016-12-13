<?php

namespace Microit\LaravelAdminRetailTelecoms\Controllers;

use Illuminate\Support\Facades\Auth;
use Microit\LaravelAdminBaseStandalone\Controller as BaseController;

/**
 * Class StoreController
 * @package Microit\LaravelAdminRetailTelecom\Controllers
 */
class Controller extends BaseController
{
    /**
     * Checks if the user can access the store
     * @return bool
     */
    protected function storeCheck()
    {
        $current_store = session('store');
        $user = Auth::user();
        if (is_null($current_store) || !$user->stores->contains($current_store)) {
            return false;
        }
        return true;
    }
}