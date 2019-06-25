<?php

Route::group([
    'namespace' => 'Setting',
], function () {
    Route::group([
        'middleware' => 'access.routeNeedsPermission:manage-settings',
    ], function () {
        Route::resource('settings', 'SettingController');
    });
});