<?php

Route::group([
    'namespace' => 'Delivery',
], function () {
    Route::group([
        'middleware' => 'access.routeNeedsPermission:manage-deliveries',
    ], function () {
        Route::resource('delivery', 'DeliveryController');
        Route::get('delivery/{delivery}/packages/create', 'DeliveryController@createDeliveryPackages')->name('delivery.package.create');
        Route::get('delivery/{delivery}/packages', 'DeliveryController@getSelectedPackages')->name('delivery.packages');
        Route::get('delivery/{delivery}/packages/select', 'DeliveryController@selectQuantities')->name('delivery.packages.quantities');
        Route::post('delivery/{delivery}/packages/quantities', 'DeliveryController@saveDeliveryQuantities')->name('delivery.packages.saveQty');
        // Add Print route
        Route::patch('delivery/{delivery}/products/', 'DeliveryController@updateDeliveryQuantities')->name('delivery.products.update');
        Route::get('delivery/{delivery}/products/', 'DeliveryController@getDeliveryDetails')->name('delivery.products.index');
        Route::get('delivery/{delivery}/report', 'DeliveryController@printDeliveryReport')->name('delivery.report');
    });
});
