<?php

Route::group([
    'namespace' => 'Client',
], function () {
    Route::group([
        'middleware' => 'access.routeNeedsPermission:manage-clients',
    ], function () {
        Route::get('client/{company}/users', 'ClientController@users')->name('client.users');
        Route::get('client/{company}/receptions', 'ClientReceptionController@index')->name('client.receptions');
        Route::get('client/{company}/stock', 'ClientStockController@index')->name('client.stock');
        Route::get('client/{company}/delivered', 'ClientDeliveredStockController@index')->name('client.delivered');
        Route::get('client/{company}/deliveries', 'ClientDeliveriesController@index')->name('client.deliveries');
        Route::get('client/{company}/inventory', 'ClientInventoryController@index')->name('client.inventory');

        Route::post('client/{company}/users', 'ClientController@storeUser')->name('client.user.store');
        Route::get('client/{company}/users/create', 'ClientController@createUser')->name('client.user.create');
        Route::get('client/{company}/users/{user}/edit', 'ClientController@editUser')->name('client.user.edit');
        Route::patch('client/{company}/users/{user}', 'ClientController@updateUser')->name('client.user.update');

        Route::get('client/{company}/preparations/create', 'ClientController@createPreparation')->name('client.preparation.create');

        Route::get('client', 'ClientController@index')->name('client.index');
        Route::get('client/create', 'ClientController@create')->name('client.create');
        Route::get('client/{company}', 'ClientController@show')->name('client.show');
        Route::get('client/{company}/edit', 'ClientController@edit')->name('client.edit');
        Route::post('client', 'ClientController@store')->name('client.store');
        Route::put('client/{company}', 'ClientController@update')->name('client.update');


        Route::get('client/{company}/toggle', 'ClientController@toggle')->name('client.toggle');

    });
});
