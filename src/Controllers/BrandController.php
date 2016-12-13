<?php

namespace Microit\LaravelAdminRetailTelecoms\Controllers;

use Microit\LaravelAdminRetailTelecoms\Events\BrandUpdate;
use Microit\LaravelAdminRetailTelecoms\Models\Brand;
use Microit\LaravelAdminRetailTelecoms\Models\Store;
use Microit\LaravelAdminRetailTelecoms\Requests\BrandRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Microit\LaravelAdminBaseStandalone\Medium;

/**
 * Handles Website create/update/delete actions
 *
 * @package Microit\LaravelAdminCms
 */
class BrandController extends Controller
{
    protected $model = Brand::class;
    protected $package = 'laravel-admin-retail-telecoms';
    protected $route = 'admin.telecoms.brand';

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
        $data['title'] = 'Merken overzicht';
        $data['title_link'] = ['route' => $this->route.'.create', 'title' => 'Merk toevoegen'];
        $data['collection'] = Brand::currentStore()->orderBy('display_title')->get();
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
        $data['title'] = 'Merk toevoegen';

        return $this->doCreate('laravel-admin-retail-telecoms::brands.form', ['data' => $data]);
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

        if (!Brand::where('id', $id)->currentStore()->first()) {
            return view('laravel-admin-retail-telecoms::error_permission');
        }

        $data = [];
        $data['title'] = 'Merk aanpassen';

        return $this->doUpdate('laravel-admin-retail-telecoms::brands.form', $id, ['data' => $data]);
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
            //event(new BrandUpdate(Brand::where('id', $request->id)->currentStore()->first(), true));
            return $this->doStore($request);
        }

        $data = [];
        $data['title'] = 'Merk verwijderen';
        $data['fields'] = [
            'display_title',
            'slug',
        ];
        return $this->doRemove(null, $id, ['data' => $data]);
    }

    /**
     * Handle the update action
     * @param BrandRequest $request
     * @return mixed
     */
    public function update(BrandRequest $request)
    {
        if (!$this->storeCheck()) {
            return view('laravel-admin-retail-telecoms::configuration_store');
        }

        if ($request->store_id != session('store')) {
            return view('laravel-admin-retail-telecoms::error_permission');
        }

        if (!Brand::where('id', $request->id)->currentStore()->first()) {
            return view('laravel-admin-retail-telecoms::error_permission');
        }


        $return = $this->doStore($request);
        $this->handleSingleFile($request);

        return $return;
    }


    /**
     * Handles the store actions
     *
     * @param BrandRequest $request
     * @return mixed
     */
    public function store(BrandRequest $request)
    {
        if (!$this->storeCheck()) {
            return view('laravel-admin-retail-telecoms::configuration_store');
        }

        if ($request->store_id != session('store')) {
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
            Storage::disk('image_brand')->put($file->getFilename() . '.' . $extension, File::get($file));

            $model = Brand::where('slug', $request->slug)->currentStore()->firstOrFail();

            // delete all old media
            foreach ($model->media as $media) {
                if (Storage::disk('image_brand')->exists($media->storage_name)) {
                    Storage::disk('image_brand')->delete($media->storage_name);
                }
                $media->delete();
            }

            // Save to models
            $media = new Medium([
                'original_name' => $file->getClientOriginalName(),
                'storage_name' => $file->getFilename() . '.' . $extension,
                'storage_disk' => 'image_brand',
            ]);
            $model->media()->save($media);
            $model->update(['medium_id' => $media->id]);
        }
    }
}