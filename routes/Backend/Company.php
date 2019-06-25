<?php

Route::group([
    'namespace' => 'Supplier',
], function () {
    Route::group([
        'middleware' => 'access.routeNeedsPermission:manage-suppliers',
    ], function () {
        Route::get('company/{company}/users', 'CompanyController@users')->name('company.users');
        Route::get('company/{company}/receptions', 'SupplierController@receptions')->name('company.receptions');
        Route::get('company/{company}/preparations', 'SupplierController@preparations')->name('company.preparations');
        Route::get('company/{company}/stocksByArticle', 'SupplierController@stocksByArticle')->name('company.stocksByArticle');
        Route::get('company/{company}/detailedStock', 'SupplierController@detailedStock')->name('company.detailedStock');
        Route::get('company/{company/inventory}', 'SupplierController@inventory')->name('company.inventory');

        Route::get('company/{company}/users/create', 'SupplierController@createUser')->name('company.user.create');
        Route::post('company/{company}/users', 'SupplierController@storeUser')->name('company.user.store');
        Route::get('company/{company}/users/edit', 'SupplierController@createUser')->name('company.user.edit');

        Route::resource('company', 'CompanyController');
    });
});