<?php

namespace Microit\LaravelAdminRetailTelecoms\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Microit\LaravelAdminBaseStandalone\Medium;
use Microit\LaravelAdminRetailTelecoms\Events\AccessoryUpdate;
use Microit\LaravelAdminRetailTelecoms\Models\Accessory;
use Microit\LaravelAdminRetailTelecoms\Models\AccessoryCategory;
use Microit\LaravelAdminRetailTelecoms\Models\Device;
use Microit\LaravelAdminRetailTelecoms\Models\Store;
use Microit\LaravelAdminRetailTelecoms\Requests\AccessoryRequest;

/**
 * Handles Website create/update/delete actions
 *
 * @package Microit\LaravelAdminCms
 */
class AccessoryController extends Controller
{
    protected $model = Accessory::class;
    protected $package = 'laravel-admin-retail-telecoms';
    protected $route = 'admin.telecoms.accessory';

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

        $categories = [null => ''];
        foreach (AccessoryCategory::orderBy('display_title')->get() as $category) {
            $categories[$category->id] = $category->display_title;
        }

        $devices = [null => ''];
        foreach (Device::orderBy('display_title')->get() as $device) {
            $devices[$device->id] = $device->display_title;
        }

        $brands = [null => ''];
        foreach (Accessory::orderBy('brand')->groupBy('brand')->get() as $accessory) {
            $brands[$accessory->brand] = $accessory->brand;
        }

        $data = [];
        $data['title'] = 'Accessoire overzicht';
        $data['title_link'] = ['route' => $this->route.'.create', 'title' => 'Accessoire toevoegen'];
        $data['collection'] = Accessory::currentStore()->get();
        $data['table'] = [
            'fields' => [
                'display_title' => [
                    'filter' => [
                        'expression' => 'LIKE'
                    ]
                ],
                'sku' => [
                    'filter' => [
                        'expression' => 'LIKE'
                    ]
                ],
                'price',
                'brand' => [
                    'filter' => [
                        'type' => 'select',
                        'data' => $brands,
                        'field' => 'brand',
                    ]
                ],
                'category.display_title' => [
                    'filter' => [
                        'type' => 'select',
                        'data' => $categories,
                        'field' => 'category',
                    ]
                ],
                'devices.display_title' => [
                    'filter' => [
                        'type' => 'select_has',
                        'data' => $devices,
                        'field' => 'devices',
                    ]
                ],
                'is_active' => [
                    'filter' => [
                        'type' => 'select',
                        'data' => [
                            '' => '',
                            0 => 'Nee',
                            1 => 'Ja',
                        ]
                    ]
                ],
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
        $data['title'] = 'Accessoire toevoegen';

        return $this->doCreate('laravel-admin-retail-telecoms::accessories.form', ['data' => $data]);
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

        $data = [];
        $data['title'] = 'Accessoire aanpassen';

        return $this->doUpdate('laravel-admin-retail-telecoms::accessories.form', $id, ['data' => $data]);
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
            //event(new AccessoryUpdate(Accessory::where('id', $request->id)->first(), true));
            return $this->doStore($request);
        }

        $data = [];
        $data['title'] = 'Accessoire verwijderen';
        $data['fields'] = [
            'display_title',
            'slug',
        ];
        return $this->doRemove(null, $id, ['data' => $data]);
    }

    /**
     * Handle the update action
     * @param AccessoryRequest $request
     * @return mixed
     */
    public function update(AccessoryRequest $request)
    {
        if (!$this->storeCheck()) {
            return view('laravel-admin-retail-telecoms::configuration_store');
        }

        $store = $this->doStore($request);

        $devices = $request->devices;

        // Detach all devices
        $model = Accessory::where('id', $request->id)->firstOrFail();
        $model->devices()->detach();

        if ($devices) {
            foreach ($devices as $device) {
                $device = Device::where('id', $device)->firstOrFail();
                $model->devices()->attach($device);

            }
        }

        $this->handleSingleFile($request);

        return $store;
    }


    /**
     * Handles the store actions
     *
     * @param AccessoryRequest $request
     * @return mixed
     */
    public function store(AccessoryRequest $request)
    {
        if (!$this->storeCheck()) {
            return view('laravel-admin-retail-telecoms::configuration_store');
        }

        $store = $this->doStore($request);

        $devices = $request->devices;

        // Detach all devices
        $model = Accessory::where('ean', $request->ean)->firstOrFail();
        $model->devices()->detach();

        if ($devices) {
            foreach ($devices as $device) {
                $device = Device::where('id', $device)->firstOrFail();
                $model->devices()->attach($device);

            }
        }

        $this->handleSingleFile($request);

        return $store;
    }

    /**
     * Uploads a single file
     * @param Request $request
     */
    private function handleSingleFile(Request $request)
    {
        // Media handling
        $file = $request->file('file');
        if ($file) {
            $extension = $file->getClientOriginalExtension();
            Storage::disk('image_accessory')->put($file->getFilename() . '.' . $extension, File::get($file));

            $model = Accessory::where('ean', $request->ean)->firstOrFail();

            // delete all old media
            foreach ($model->media as $media) {
                if (Storage::disk('image_accessory')->exists($media->storage_name)) {
                    Storage::disk('image_accessory')->delete($media->storage_name);
                }
                $media->delete();
            }

            // Save to models
            $media = new Medium([
                'original_name' => $file->getClientOriginalName(),
                'storage_name' => $file->getFilename() . '.' . $extension,
                'storage_disk' => 'image_accessory',
            ]);
            $model->media()->save($media);
            $model->update(['medium_id' => $media->id]);
        }
    }
}