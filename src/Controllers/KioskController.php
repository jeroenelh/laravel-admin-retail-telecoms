<?php

namespace Microit\LaravelAdminRetailTelecoms\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Microit\LaravelAdminRetailTelecoms\Models\Kiosk;
use Illuminate\Http\Request;
use Microit\LaravelAdminRetailTelecoms\Requests\KioskRequest;

/**
 * Handles Website create/update/delete actions
 *
 * @package Microit\LaravelAdminCms
 */
class KioskController extends Controller
{
    protected $model = Kiosk::class;
    protected $package = 'laravel-admin-retail-telecoms';
    protected $route = 'admin.telecoms.kiosk';

    /**
     * Handles the index view
     *
     * @return mixed
     */
    public function index()
    {
        if (!$this->storeCheck()) {
            return view('laravel-admin-retail-telecoms::configuration_store');
        }

        $data = [];
        $data['title'] = 'Kiosk overzicht';
        $data['title_link'] = ['route' => $this->route.'.create', 'title' => 'Kiosk toevoegen'];
        $data['collection'] = Kiosk::currentStore()->orderBy('display_title')->get();
        $data['table'] = [
            'fields' => [
                'display_title',
                'device',
                'os_full',
                'browser_full',
                'last_connection',
            ],
        ];

        return $this->doIndex(null, ['data' => $data]);
    }

    /**
     * Handles the create view
     *
     * @return mixed
     */
    public function create()
    {
        if (!$this->storeCheck()) {
            return view('laravel-admin-retail-telecoms::configuration_store');
        }

        $data = [];
        $data['title'] = 'Kiosk toevoegen';

        return $this->doCreate('laravel-admin-retail-telecoms::kiosks.form', ['data' => $data]);
    }

    /**
     * Handles the update view
     *
     * @param $id
     * @return mixed
     */
    public function edit($id)
    {
        if (!$this->storeCheck()) {
            return view('laravel-admin-retail-telecoms::configuration_store');
        }

        if (!Kiosk::where('id', $id)->currentStore()->first()) {
            return view('laravel-admin-retail-telecoms::error_permission');
        }

        $data = [];
        $data['title'] = 'Kiosk aanpassen';

        return $this->doUpdate('laravel-admin-retail-telecoms::kiosks.form', $id, ['data' => $data]);
    }

    /**
     * Handles the delete view
     *
     * @param $request Request
     * @param $id
     * @return mixed
     */
    public function destroy(Request $request, $id)
    {
        if (!$this->storeCheck()) {
            return view('laravel-admin-retail-telecoms::configuration_store');
        }

        if ($request->confirmed == 'true') {
            return $this->doStore($request);
        }

        $data = [];
        $data['title'] = 'Kiosk verwijderen';
        $data['fields'] = [
            'display_title',
            'slug',
        ];
        return $this->doRemove(null, $id, ['data' => $data]);
    }

    /**
     * Handle the update action
     * @param KioskRequest $request
     * @return mixed
     */
    public function update(KioskRequest $request)
    {
        if (!$this->storeCheck()) {
            return view('laravel-admin-retail-telecoms::configuration_store');
        }

        if ($request->store_id != session('store')) {
            return view('laravel-admin-retail-telecoms::error_permission');
        }

        if (!Kiosk::where('id', $request->id)->currentStore()->first()) {
            return view('laravel-admin-retail-telecoms::error_permission');
        }


        $return = $this->doStore($request);

        return $return;
    }


    /**
     * Handles the store actions
     *
     * @param KioskRequest $request
     * @return mixed
     */
    public function store(KioskRequest $request)
    {
        if (!$this->storeCheck()) {
            return view('laravel-admin-retail-telecoms::configuration_store');
        }

        if ($request->store_id != session('store')) {
            return view('laravel-admin-retail-telecoms::error_permission');
        }

        $return = $this->doStore($request);

        return $return;
    }

    /**
     * Shows a single kiosk instance
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        if (!$this->storeCheck()) {
            return view('laravel-admin-retail-telecoms::configuration_store');
        }

        $kiosk = Kiosk::whereId($id)->currentStore()->firstOrFail();

        return view('laravel-admin-retail-telecoms::kiosks.show', ['kiosk' => $kiosk]);
    }

    /**
     * Get an activation key of the selected kiosk
     * @return mixed
     */
    public function getActivationKey()
    {
        $kiosk_id = Input::get('kiosk_id');
        $kiosk = Kiosk::whereId($kiosk_id)->currentStore()->firstOrFail();


        $key = $kiosk->activations()->possibleActivations()->first();
        if (is_null($key)) {
            $key = $kiosk->activations()->create([]);
        }
        return $key->activation_key;
    }

    /**
     * Set refresh information of the selected kiosk
     */
    public function setRefresh()
    {
        $kiosk_id = Input::get('kiosk_id');
        $kiosk = Kiosk::whereId($kiosk_id)->currentStore()->firstOrFail();

        $category = Input::get('category');

        switch ($category) {
            case 'browser':
                $kiosk->update(['need_refresh' => true]);
                break;
            case 'images':
                $kiosk->update(['need_reload_images' => true]);
                break;
            case 'accessories':
                $kiosk->update(['need_reload_accessories' => true]);
                break;
        }
    }
}