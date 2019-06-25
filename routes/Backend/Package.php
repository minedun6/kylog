<?php

Route::group([
    'namespace' => 'Reception',
], function () {
    Route::group([
        'middleware' => 'access.routeNeedsPermission:manage-receptions',
    ], function () {
        Route::resource('reception.package', 'PackageController');
    });
});