<?php

namespace Microit\LaravelAdminRetailTelecoms\Controllers;

use App\User;
use DCN\RBAC\Models\Role;
use Illuminate\Http\Request;
use Microit\LaravelAdminRetailTelecoms\Models\Headuser;
use Microit\LaravelAdminRetailTelecoms\Models\Store;
use Microit\LaravelAdminRetailTelecoms\Requests\HeaduserRequest;

/**
 * Class StoreController
 * @package Microit\LaravelAdminRetailTelecom\Controllers
 */
class HeaduserController extends Controller
{
    protected $model = Headuser::class;
    protected $package = 'laravel-admin-retail-telecoms';
    protected $route = 'admin.telecoms.headuser';

    /**
     * Handles the index view
     *
     * @return mixed
     */
    public function index()
    {
        //dd('index');
        $data = [];
        $data['title'] = 'Hoofdgebruiker overzicht';
        $data['title_link'] = ['route' => $this->route.'.create', 'title' => 'Hoofdgebruiker toevoegen'];
        $data['collection'] = User::whereHas('roles', function($query) {
            $query->where('slug', 'administrator')->orWhere('slug', 'storeowner');
        })->get();
        $data['table'] = [
            'fields' => [
                'name',
                'email',
                'roles.slug',
                'stores.display_title',
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
        $data['title'] = 'Hoofdgebruiker toevoegen';

        return $this->doCreate('laravel-admin-retail-telecoms::users.form', ['data' => $data]);
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
        $data['title'] = 'Hoofdgebruiker aanpassen';
        $data['model'] = User::whereHas('roles', function($query) {
                                $query->where('slug', 'administrator')->orWhere('slug', 'storeowner');
                            })
                            ->where('id', $id)
                            ->firstOrFail();

        return $this->doUpdate('laravel-admin-retail-telecoms::users.form', $id, ['data' => $data]);
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
        $data['title'] = 'Hoofdgebruiker verwijderen';
        $data['fields'] = [
            'name',
            'email',
        ];
        return $this->doRemove(null, $id, ['data' => $data]);
    }

    /**
     * Handle the update action
     * @param HeaduserRequest $request
     * @return mixed
     */
    public function update(HeaduserRequest $request)
    {
        $return = $this->doStore($request);

        // Attach role
        $user = User::where('email', $request->email)->firstOrFail();
        $role = Role::where('slug', 'storeowner')->firstOrFail();
        $user->attachRole($role);

        // Detach all stores & attach the selected ones
        $stores = $request->stores;
        $model = User::where('email', $request->email)->firstOrFail();
        $model->stores()->detach();

        if ($stores) {
            foreach ($stores as $store) {
                $store = Store::where('id', $store)->firstOrFail();
                $model->stores()->attach($store);

            }
        }

        return $return;
    }


    /**
     * Handles the store actions
     *
     * @param HeaduserRequest $request
     * @return mixed
     */
    public function store(HeaduserRequest $request)
    {
        $return = $this->doStore($request);

        // Attach role
        $user = User::where('email', $request->email)->firstOrFail();
        $role = Role::where('slug', 'storeowner')->firstOrFail();
        $user->attachRole($role);

        // Detach all stores & attach the selected ones
        $stores = $request->stores;
        $model = User::where('email', $request->email)->firstOrFail();
        $model->stores()->detach();

        if ($stores) {
            foreach ($stores as $store) {
                $store = Store::where('id', $store)->firstOrFail();
                $model->stores()->attach($store);

            }
        }
        
        return $return;
    }
}