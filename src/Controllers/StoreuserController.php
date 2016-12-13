<?php

namespace Microit\LaravelAdminRetailTelecoms\Controllers;

use App\User;
use DCN\RBAC\Models\Role;
use Illuminate\Http\Request;
use Microit\LaravelAdminRetailTelecoms\Models\Store;
use Microit\LaravelAdminRetailTelecoms\Models\Storeuser;
use Microit\LaravelAdminRetailTelecoms\Requests\StoreuserRequest;

/**
 * Class StoreController
 * @package Microit\LaravelAdminRetailTelecom\Controllers
 */
class StoreuserController extends Controller
{
    protected $model = Storeuser::class;
    protected $package = 'laravel-admin-retail-telecoms';
    protected $route = 'admin.telecoms.storeuser';

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
        $data['title'] = 'Winkel gebruiker overzicht';
        $data['title_link'] = ['route' => $this->route.'.create', 'title' => 'Winkel gebruiker toevoegen'];
        $data['collection'] = User
            ::whereHas('roles', function($query) {
                $query->where('slug', 'storeowner')->orWhere('slug', 'storeuser');
            })
            ->whereHas('stores', function($query) {
                $query->where('store_id', session('store'));
            })
            ->get();
        $data['table'] = [
            'fields' => [
                'name',
                'email',
                'roles.slug',
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
        $data['title'] = 'Winkel gebruiker toevoegen';

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
        if (!$this->storeCheck()) {
            return view('laravel-admin-retail-telecoms::configuration_store');
        }

        $data = [];
        $data['title'] = 'Winkel gebruiker aanpassen';
        $data['model'] = User
            ::whereHas('roles', function($query) {
                $query->where('slug', 'storeowner')->orWhere('slug', 'storeuser');
            })
            ->whereHas('stores', function($query) {
                $query->where('store_id', session('store'));
            })
            ->where('id', $id)
            ->firstOrFail();

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
        if (!$this->storeCheck()) {
            return view('laravel-admin-retail-telecoms::configuration_store');
        }

        if ($request->confirmed == 'true') {
            return $this->doStore($request);
        }

        $data = [];
        $data['title'] = 'Winkel gebruiker verwijderen';
        $data['fields'] = [
            'name',
            'email',
        ];
        return $this->doRemove(null, $id, ['data' => $data]);
    }

    /**
     * Handle the update action
     * @param StoreuserRequest $request
     * @return mixed
     */
    public function update(StoreuserRequest $request)
    {
        if (!$this->storeCheck()) {
            return view('laravel-admin-retail-telecoms::configuration_store');
        }

        $return = $this->doStore($request);

        // Attach role
        $user = User::where('email', $request->email)->firstOrFail();
        $role = Role::where('slug', 'storeuser')->firstOrFail();
        $user->attachRole($role);

        // Attach store
        $user = User::where('email', $request->email)->firstOrFail();
        $store = Store::where('id', session('store'))->firstOrFail();
        $user->stores->contains($store) ? : $user->stores()->attach($store);

        return $return;
    }


    /**
     * Handles the store actions
     *
     * @param StoreuserRequest $request
     * @return mixed
     */
    public function store(StoreuserRequest $request)
    {
        if (!$this->storeCheck()) {
            return view('laravel-admin-retail-telecoms::configuration_store');
        }

        $return = $this->doStore($request);

        // Attach role
        $user = User::where('email', $request->email)->firstOrFail();
        $role = Role::where('slug', 'storeuser')->firstOrFail();
        $user->attachRole($role);

        // Attach store
        $user = User::where('email', $request->email)->firstOrFail();
        $store = Store::where('id', session('store'))->firstOrFail();
        $user->stores->contains($store) ? : $user->stores()->attach($store);

        return $return;
    }
}