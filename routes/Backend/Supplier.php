<?php

Route::group([
    'namespace' => 'Supplier',
], function () {
    Route::group([
        'middleware' => 'access.routeNeedsPermission:manage-suppliers',
    ], function () {
        Route::get('supplier/{company}/users', 'SupplierController@users')->name('supplier.users');
        Route::get('supplier/{company}/receptions', 'SupplierReceptionsController@index')->name('supplier.receptions');
        Route::get('supplier/{company}/stock/article', 'SupplierStockByArticleController@index')->name('supplier.stockByArticle');
        Route::get('supplier/{company}/stock/detailed', 'SupplierDetailedStockController@index')->name('supplier.detailed');
        Route::get('supplier/{company}/stock/delivered', 'SupplierDeliveredStockController@index')->name('supplier.delivered');
        Route::get('supplier/{company}/deliveries', 'SupplierDeliveriesController@index')->name('supplier.deliveries');
        Route::get('supplier/{company}/inventories', 'SupplierInventoryController@index')->name('supplier.inventories');

        Route::get('supplier/{company}/users/create', 'SupplierController@createUser')->name('supplier.user.create');
        Route::post('supplier/{company}/users', 'SupplierController@storeUser')->name('supplier.user.store');
        Route::get('supplier/{company}/users/{user}/edit', 'SupplierController@editUser')->name('supplier.user.edit');
        Route::patch('supplier/{company}/users/{user}', 'SupplierController@updateUser')->name('supplier.user.update');

        Route::get('supplier', 'SupplierController@index')->name('supplier.index');
        Route::get('supplier/create', 'SupplierController@create')->name('supplier.create');
        Route::get('supplier/{company}', 'SupplierController@show')->name('supplier.show');
        Route::get('supplier/{company}/edit', 'SupplierController@edit')->name('supplier.edit');
        Route::post('supplier', 'SupplierController@store')->name('supplier.store');
        Route::put('supplier/{company}', 'SupplierController@update')->name('supplier.update');

    });
});
