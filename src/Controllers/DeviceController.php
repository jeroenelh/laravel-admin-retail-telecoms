<?php

namespace Microit\LaravelAdminRetailTelecoms\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Microit\LaravelAdminBaseStandalone\Medium;
use Microit\LaravelAdminRetailTelecoms\Events\DeviceUpdate;
use Microit\LaravelAdminRetailTelecoms\Models\Accessory;
use Microit\LaravelAdminRetailTelecoms\Models\Brand;
use Microit\LaravelAdminRetailTelecoms\Models\Device;
use Microit\LaravelAdminRetailTelecoms\Models\RepairCategory;
use Microit\LaravelAdminRetailTelecoms\Models\Store;
use Microit\LaravelAdminRetailTelecoms\Requests\DeviceRequest;

/**
 * Handles Website create/update/delete actions
 *
 * @package Microit\LaravelAdminCms
 */
class DeviceController extends Controller
{
    protected $model = Device::class;
    protected $package = 'laravel-admin-retail-telecoms';
    protected $route = 'admin.telecoms.device';

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

        $brands = [null => ''];
        foreach (Brand::orderBy('display_title')->get() as $brand) {
            $brands[$brand->id] = $brand->display_title;
        }

        $data = [];
        $data['title'] = 'Apparaat overzicht';
        $data['title_link'] = ['route' => $this->route.'.create', 'title' => 'Apparaat toevoegen'];
        $data['collection'] = Device::currentStore()->get();
        $data['table'] = [
            'fields' => [
                'display_title' => [
                    'filter' => [
                        'expression' => 'LIKE'
                    ]
                ],
                'slug',
                'brand.display_title' => [
                    'filter' => [
                        'type' => 'select',
                        'data' => $brands,
                        'field' => 'brand_id',
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
        $data['title'] = 'Apparaat toevoegen';

        return $this->doCreate('laravel-admin-retail-telecoms::devices.form', ['data' => $data]);
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
        $data['title'] = 'Apparaat aanpassen';

        return $this->doUpdate('laravel-admin-retail-telecoms::devices.form', $id, ['data' => $data]);
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
        $data['title'] = 'Apparaat verwijderen';
        $data['fields'] = [
            'display_title',
            'slug',
        ];
        return $this->doRemove(null, $id, ['data' => $data]);
    }

    /**
     * Handle the update action
     * @param DeviceRequest $request
     * @return mixed
     */
    public function update(DeviceRequest $request)
    {
        if (!$this->storeCheck()) {
            return view('laravel-admin-retail-telecoms::configuration_store');
        }

        $return = $this->doStore($request);
        $this->handleSingleFile($request);

        return $return;
    }


    /**
     * Handles the store actions
     *
     * @param DeviceRequest $request
     * @return mixed
     */
    public function store(DeviceRequest $request)
    {
        if (!$this->storeCheck()) {
            return view('laravel-admin-retail-telecoms::configuration_store');
        }

        if (!Brand::where('id', $request->brand_id)->currentStore()->first()) {
            return view('laravel-admin-retail-telecoms::error_permission');
        }

        $return = $this->doStore($request);
        $this->handleSingleFile($request);

        return $return;
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
            Storage::disk('image_device')->put($file->getFilename() . '.' . $extension, File::get($file));

            $model = Device::where('slug', $request->slug)->firstOrFail();

            // delete all old media
            foreach ($model->media as $media) {
                if (Storage::disk('image_device')->exists($media->storage_name)) {
                    Storage::disk('image_device')->delete($media->storage_name);
                }
                $media->delete();
            }

            // Save to models
            $media = new Medium([
                'original_name' => $file->getClientOriginalName(),
                'storage_name' => $file->getFilename() . '.' . $extension,
                'storage_disk' => 'image_device',
            ]);
            $model->media()->save($media);
            $model->update(['medium_id' => $media->id]);
        }
    }

    /**
     * Handles AJAX requests for showing the accessories
     * @return mixed
     */
    public function ajax_get_accessories()
    {
        $device = Device::whereId(Input::get('device_id'))->firstOrFail();
        $cat = Input::get('accessories_cat', 'selected');
        $search = Input::get('accessories_search', null);

        switch($cat) {
            case 'selected':
                $accessories = $device->accessories()->with('medium')->orderBy('display_title');
                break;
            case 'all':
                $accessories = Accessory::with('medium')->orderBy('display_title');
                break;
            default:
                $accessories = Accessory::with('medium')->orderBy('display_title')->where('accessory_category_id', $cat);
                break;
        }

        if (!is_null($search) && trim($search) != '') {
            $accessories->where(function($query) use ($search){
                $query->orWhere('display_title', 'LIKE', '%'.$search.'%')
                ->orWhere('description', 'LIKE', '%'.$search.'%');
            });
        }

        $accessories = $accessories->paginate(12);
        return view('laravel-admin-retail-telecoms::devices.accessory', ['accessories' => $accessories, 'device' => $device]);
    }

    /**
     * Handles the AJAX request to attach an accessory
     */
    public function ajax_connect_accessory()
    {
        $device = Device::whereId(Input::get('device_id'))->firstOrFail();
        $accessory = Accessory::whereId(Input::get('accessory_id'))->firstOrFail();
        $connect = Input::get('connect');

        if ($connect == 'connect') {
            $device->accessories()->attach($accessory->id);
        } elseif ($connect == 'disconnect') {
            $device->accessories()->detach($accessory->id);
        } else {
            abort(500);
        }
    }

    /**
     * Handles the AJAx request for adding a repair categorie
     */
    public function ajax_add_repair()
    {
        $device = Device::whereId(Input::get('device_id'))->firstOrFail();
        $repair = RepairCategory::whereId(Input::get('repair_id'))->firstOrFail();
        $price = Input::get('price');
        $duration = Input::get('duration');

        if ($price == 0) {
            $device->repairs()->detach($repair);
            echo "DELETE";
        } else {
            if ($device->repairs->contains($repair)) {
                $device->repairs()->updateExistingPivot($repair->id, ['price' => $price, 'duration' => $duration], false);
                echo "UPDATE";
            } else {
                $device->repairs()->save($repair, ['price' => $price, 'duration' => $duration]);
                echo "INSERT";
            }
        }
    }
}