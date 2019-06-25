<?php

Route::group([
    'namespace' => 'Reception',
], function () {
    Route::group([
        'middleware' => 'access.routeNeedsPermission:manage-receptions',
    ], function () {
        Route::get('reception/{reception}/labels', 'ReceptionController@printPackageLabels')->name('reception.print.labels');
        Route::get('reception/{reception}/report', 'ReceptionController@printReceptionReport')->name('reception.print.report');
        Route::put('reception/{reception}/packages', 'ReceptionController@updatePackages')->name('receptions.packages.update');
        Route::get('reception/{reception}/model/form/data', 'ReceptionController@getModal')->name('reception.modal.deliveries');
        Route::get('reception/{reception}/stock/delivered/model/form/data', 'ReceptionController@getDeliveredStockModal')->name('reception.modal.delivered');
        Route::resource('reception', 'ReceptionController');
    });
});
