<?php
\Route::group(['middleware' => ['web','auth']], function () {
    \Route::resource('admin/telecoms/store',
        \Microit\LaravelAdminRetailTelecoms\Controllers\StoreController::class,
        [
            'only' => ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'],
            'as' => 'admin.telecoms'
        ]
    );

    \Route::resource('admin/telecoms/headuser',
        \Microit\LaravelAdminRetailTelecoms\Controllers\HeaduserController::class,
        [
            'only' => ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'],
            'as' => 'admin.telecoms'
        ]
    );

    \Route::resource('admin/telecoms/storeuser',
        \Microit\LaravelAdminRetailTelecoms\Controllers\StoreuserController::class,
        [
            'only' => ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'],
            'as' => 'admin.telecoms'
        ]
    );

    \Route::resource('admin/telecoms/brand',
        \Microit\LaravelAdminRetailTelecoms\Controllers\BrandController::class,
        [
            'only' => ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'],
            'as' => 'admin.telecoms'
        ]
    );

    \Route::resource('admin/telecoms/device',
        \Microit\LaravelAdminRetailTelecoms\Controllers\DeviceController::class,
        [
            'only' => ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'],
            'as' => 'admin.telecoms'
        ]
    );

    \Route::resource('admin/telecoms/accessory_category',
        \Microit\LaravelAdminRetailTelecoms\Controllers\AccessoryCategoryController::class,
        [
            'only' => ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'],
            'as' => 'admin.telecoms'
        ]
    );

    \Route::resource('admin/telecoms/accessory',
        \Microit\LaravelAdminRetailTelecoms\Controllers\AccessoryController::class,
        [
            'only' => ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'],
            'as' => 'admin.telecoms'
        ]
    );

    \Route::resource('admin/telecoms/repair_category',
        \Microit\LaravelAdminRetailTelecoms\Controllers\RepairCategoryController::class,
        [
            'only' => ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'],
            'as' => 'admin.telecoms'
        ]
    );

    \Route::resource('admin/telecoms/kiosk',
        \Microit\LaravelAdminRetailTelecoms\Controllers\KioskController::class,
        [
            'only' => ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'],
            'as' => 'admin.telecoms'
        ]
    );


    Route::post('admin/ajax/device/ajax_get_accessories', [
        'uses' => '\Microit\LaravelAdminRetailTelecoms\Controllers\DeviceController@ajax_get_accessories',
    ]);
    Route::post('admin/ajax/device/ajax_connect_accessory', [
        'uses' => '\Microit\LaravelAdminRetailTelecoms\Controllers\DeviceController@ajax_connect_accessory',
    ]);
    Route::post('admin/ajax/device/ajax_add_repair', [
        'uses' => '\Microit\LaravelAdminRetailTelecoms\Controllers\DeviceController@ajax_add_repair',
    ]);


    /**
     * Price configurator
     */
    Route::get('admin/telecoms/price_configuration', [
        'as' => 'admin.telecoms.price_configuration',
        'uses' => '\Microit\LaravelAdminRetailTelecoms\Controllers\PriceConfigurationController@overview',
    ]);

    Route::get('admin/telecoms/price_configuration/edit/{id}', [
        'as' => 'admin.telecoms.price_configuration.edit',
        'uses' => '\Microit\LaravelAdminRetailTelecoms\Controllers\PriceConfigurationController@edit',
    ]);
    Route::post('admin/telecoms/price_configuration/edit/{id}', [
        'as' => 'admin.telecoms.price_configuration.edit',
        'uses' => '\Microit\LaravelAdminRetailTelecoms\Controllers\PriceConfigurationController@save',
    ]);

    Route::get('admin/telecoms/price_configuration/run/{id}', [
        'as' => 'admin.telecoms.price_configuration.run',
        'uses' => '\Microit\LaravelAdminRetailTelecoms\Controllers\PriceConfigurationController@run',
    ]);

    /**
     * Kiosk
     */
    Route::post('admin/ajax/kiosk/ajax_get_activation_key', [
        'as' => 'admin.telecoms.kiosk.get_activation_key',
        'uses' => '\Microit\LaravelAdminRetailTelecoms\Controllers\KioskController@getActivationKey',
    ]);
    Route::post('admin/ajax/kiosk/ajax_refresh', [
        'as' => 'admin.telecoms.kiosk.refresh',
        'uses' => '\Microit\LaravelAdminRetailTelecoms\Controllers\KioskController@setRefresh',
    ]);
});