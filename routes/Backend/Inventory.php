<?php

Route::group([
    'namespace' => 'Inventory',
], function () {
    Route::group([
        'middleware' => 'access.routeNeedsPermission:manage-inventories',
    ], function () {
        Route::get('inventory/products/pick', 'InventoryController@pickProducts')->name('inventory.product.pick');
        Route::post('inventory/products/save', 'InventoryController@save')->name('inventory.save');
        Route::resource('inventory', 'InventoryController');
        Route::get('inventory/{inventory}/export/excel', 'InventoryController@exportToExcel')->name('inventory.excel');
        Route::get('inventory/{inventory}/export/pdf', 'InventoryController@exportToPdf')->name('inventory.pdf');
        Route::get('inventory/empty/export', 'InventoryController@exportEmptyInventory')->name('inventory.empty.pdf');
        Route::get('inventory/{inventory}/model/form/data', 'InventoryController@getModal')->name('inventory.modal.edit');
    });
});
