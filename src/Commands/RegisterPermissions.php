<?php

namespace Microit\LaravelAdminRetailTelecoms\Commands;

use DCN\RBAC\Models\Permission;
use DCN\RBAC\Models\Role;
use Illuminate\Console\Command;

class RegisterPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin-retail-telecoms:register-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Register permissions';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Permissions
        $permissions_tree = [
            'administrator' => [
                'store.index',
                //'store.show',
                'store.create',
                'store.edit',
                'store.destroy',

                'headuser.index',
                //'headuser.show',
                'headuser.create',
                'headuser.edit',
                'headuser.destroy',

                'kiosk.index',
                'kiosk.show',
                'kiosk.create',
                'kiosk.edit',
                'kiosk.destroy',
            ],
            'storeowner' => [
                'storeuser.index',
                //'storeuser.show',
                'storeuser.create',
                'storeuser.edit',
                'storeuser.destroy',

                'brand.index',
                //'brand.show',
                'brand.create',
                'brand.edit',
                'brand.destroy',

                'device.index',
                //'device.show',
                'device.create',
                'device.edit',
                'device.destroy',

                'accessory_category.index',
                //'accessory_category.show',
                'accessory_category.create',
                'accessory_category.edit',
                'accessory_category.destroy',

                'repair_category.index',
                //'repair_category.show',
                'repair_category.create',
                'repair_category.edit',
                'repair_category.destroy',

                'accessory.index',
                //'accessory.show',
                'accessory.create',
                'accessory.edit',
                'accessory.destroy',

                'kiosk.index',
                //'kiosk.show',
                'kiosk.create',
                'kiosk.edit',
                'kiosk.destroy',
            ],
            'storeuser' => [
                'brand.index',
                //'brand.show',
                'brand.create',
                'brand.edit',
                'brand.destroy',

                'device.index',
                //'device.show',
                'device.create',
                'device.edit',
                'device.destroy',

                'accessory_category.index',
                //'accessory_category.show',
                'accessory_category.create',
                'accessory_category.edit',
                'accessory_category.destroy',

                'repair_category.index',
                //'repair_category.show',
                'repair_category.create',
                'repair_category.edit',
                'repair_category.destroy',

                'accessory.index',
                //'accessory.show',
                'accessory.create',
                'accessory.edit',
                'accessory.destroy',
            ]
        ];

        // Read permission tree and add permissions if needed
        foreach ($permissions_tree as $role => $permissions) {
            $role_obj = Role::where('slug', $role)->first();
            if (is_null($role_obj)) {
                $role_obj = Role::create([
                    'name' => $role,
                    'slug' => $role,
                ]);
            }

            foreach ($permissions as $permission) {
                $permission = str_replace("_", "", $permission);
                $permission_obj = Permission::where('slug', $permission)->first();
                if (is_null($permission_obj)) {
                    $permission_obj = Permission::create([
                        'name' => $permission,
                        'slug' => $permission,
                    ]);
                }

                $role_obj->attachPermission($permission_obj);
            }
        }
    }
}
