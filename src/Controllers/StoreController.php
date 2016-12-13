<?php

namespace Microit\LaravelAdminRetailTelecoms\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Microit\LaravelAdminRetailTelecoms\Models\Store;
use Microit\LaravelAdminRetailTelecoms\Requests\StoreRequest;

/**
 * Class StoreController
 * @package Microit\LaravelAdminRetailTelecom\Controllers
 */
class StoreController extends Controller
{
    protected $model = Store::class;
    protected $package = 'laravel-admin-retail-telecoms';
    protected $route = 'admin.telecoms.store';

    /**
     * Handles the index view
     *
     * @return mixed
     */
    public function index()
    {
        $data = [];
        $data['title'] = 'Winkel overzicht';
        $data['title_link'] = ['route' => $this->route.'.create', 'title' => 'Winkel toevoegen'];
        $data['table'] = [
            'fields' => [
                'display_title',
                'contact_name',
                'address_city',
                'kiosks',
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
        $data = [];
        $data['title'] = 'Winkel toevoegen';

        return $this->doCreate(null, ['data' => $data]);
    }

    /**
     * Handles the update view
     *
     * @param $id
     * @return mixed
     */
    public function edit($id)
    {
        $data = [];
        $data['title'] = 'Winkel aanpassen';

        return $this->doUpdate(null, $id, ['data' => $data]);
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
        if ($request->confirmed == 'true') {
            return $this->doStore($request);
        }

        $data = [];
        $data['title'] = 'Winkel verwijderen';
        $data['fields'] = [
            'display_title',
            'slug',
        ];
        return $this->doRemove(null, $id, ['data' => $data]);
    }

    /**
     * Handle the update action
     * @param StoreRequest $request
     * @return mixed
     */
    public function update(StoreRequest $request)
    {
        $return = $this->doStore($request);

        if ($request->lat == '' || $request->lng == '') {
            $store = Store::where('id', $request->id)->first();
            $this->updateLocation($store);
        }

        return $return;
    }


    /**
     * Handles the store actions
     *
     * @param StoreRequest $request
     * @return mixed
     */
    public function store(StoreRequest $request)
    {
        $return = $this->doStore($request);

        if ($request->lat == '' || $request->lng == '') {
            $store = Store::where('slug', $request->slug)->first();
            $this->updateLocation($store);
        }

        return $return;
    }

    /**
     * Set lat/lng fields on the store
     *
     * @todo Check if the google maps key is set
     * @param Store $store
     */
    public function updateLocation(Store $store)
    {
        $client = new Client([
            'base_uri' => 'https://maps.googleapis.com/',
            'timeout' => 5,
        ]);

        $response = $client->get('/maps/api/geocode/json?address='.$store->address_street.','.$store->address_city.'&key='.config('laravel-admin-retail-telecoms.google_maps_key'));
        $result = json_decode($response->getBody());

        if (isset($result->results[0])) {
            $store->update([
                'address_lat' => $result->results[0]->geometry->location->lat,
                'address_lng' => $result->results[0]->geometry->location->lng,
            ]);
        }
    }
}