<?php

Route::group([
    'namespace' => 'Stock',
], function () {
    Route::group([
        'middleware' => 'access.routeNeedsPermission:manage-stocks',
    ], function () {
        Route::get('stock/detailed/custom', 'StockController@getCustomFilterModal')->name('stock.detailed.modal');
        Route::get('stock/detailed', 'StockController@detailedStock')->name('stock.detailed');
        Route::get('stock/article', 'StockController@stockByArticle')->name('stock.article');
        Route::get('stock/delivered', 'StockController@deliveredStock')->name('stock.delivered');
    });
});
