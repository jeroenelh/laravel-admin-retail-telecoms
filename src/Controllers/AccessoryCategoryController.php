<?php

namespace Microit\LaravelAdminRetailTelecoms\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Microit\LaravelAdminBaseStandalone\Medium;
use Microit\LaravelAdminRetailTelecoms\Events\AccessoryCategoryUpdate;
use Microit\LaravelAdminRetailTelecoms\Models\AccessoryCategory;
use Microit\LaravelAdminRetailTelecoms\Requests\AccessoryCategoryRequest;

/**
 * Handles Website create/update/delete actions
 *
 * @package Microit\LaravelAdminCms
 */
class AccessoryCategoryController extends Controller
{
    protected $model = AccessoryCategory::class;
    protected $package = 'laravel-admin-retail-telecoms';
    protected $route = 'admin.telecoms.accessory_category';

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
        $data['title'] = 'Accessoire categorie overzicht';
        $data['title_link'] = ['route' => $this->route.'.create', 'title' => 'Categorie toevoegen'];
        $data['collection'] = AccessoryCategory::currentStore()->get();
        $data['table'] = [
            'fields' => [
                'display_title',
                'slug',
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
        $data['title'] = 'Accessoire categorie toevoegen';

        return $this->doCreate('laravel-admin-retail-telecoms::accessory_categories.form', ['data' => $data]);
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
        $data['title'] = 'Accessoire categorie aanpassen';

        return $this->doUpdate('laravel-admin-retail-telecoms::accessory_categories.form', $id, ['data' => $data]);
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
            //event(new AccessoryCategoryUpdate(AccessoryCategory::where('id', $request->id)->currentStore()->first(), true));
            return $this->doStore($request);
        }

        $data = [];
        $data['title'] = 'Accessoire categorie verwijderen';
        $data['fields'] = [
            'display_title',
            'slug',
        ];
        return $this->doRemove(null, $id, ['data' => $data]);
    }

    /**
     * Handle the update action
     * @param AccessoryCategoryRequest $request
     * @return mixed
     */
    public function update(AccessoryCategoryRequest $request)
    {
        if (!$this->storeCheck()) {
            return view('laravel-admin-retail-telecoms::configuration_store');
        }

        if ($request->store_id != session('store')) {
            return view('laravel-admin-retail-telecoms::error_permission');
        }

        $return = $this->doStore($request);
        $this->handleSingleFile($request);

        //event(new AccessoryCategoryUpdate(AccessoryCategory::where('slug', $request->slug)->currentStore()->first()));

        return $return;
    }


    /**
     * Handles the store actions
     *
     * @param AccessoryCategoryRequest $request
     * @return mixed
     */
    public function store(AccessoryCategoryRequest $request)
    {
        if (!$this->storeCheck()) {
            return view('laravel-admin-retail-telecoms::configuration_store');
        }

        if ($request->store_id != session('store')) {
            return view('laravel-admin-retail-telecoms::error_permission');
        }

        $return = $this->doStore($request);
        $this->handleSingleFile($request);
        //event(new AccessoryCategoryUpdate(AccessoryCategory::where('slug', $request->slug)->currentStore()->first()));

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
            Storage::disk('image_category')->put($file->getFilename() . '.' . $extension, File::get($file));

            $model = AccessoryCategory::where('slug', $request->slug)->firstOrFail();

            // delete all old media
            foreach ($model->media as $media) {
                if (Storage::disk('image_category')->exists($media->storage_name)) {
                    Storage::disk('image_category')->delete($media->storage_name);
                }
                $media->delete();
            }

            // Save to models
            $media = new Medium([
                'original_name' => $file->getClientOriginalName(),
                'storage_name' => $file->getFilename() . '.' . $extension,
                'storage_disk' => 'image_category',
            ]);
            $model->media()->save($media);
            $model->update(['medium_id' => $media->id]);
        }
    }
}