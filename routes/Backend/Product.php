<?php

Route::group([
    'namespace' => 'Product',
], function () {
    Route::group([
        'middleware' => 'access.routeNeedsPermission:manage-products',
    ], function () {
        Route::resource('product', 'ProductController');
    });
});