<?php

Route::delete('/deliveries/{delivery}/products/{id}', 'Api\DeliveryController@deletePackageItem');

Route::get('/reception/stats', 'Api\ReceptionController@get')->name('api.reception');

Route::get('/delivery/stats', 'Api\DeliveryController@get')->name('api.delivery');

Route::get('/reception/latest', 'Api\ReceptionController@latest')->name('api.reception.latest');

Route::get('/delivery/latest', 'Api\DeliveryController@latest')->name('api.delivery.latest');

Route::delete('/reception/{reception}/destroy', 'Api\ReceptionController@destroy')->name('api.reception.destroy');

Route::delete('/delivery/{delivery}/destroy', 'Api\DeliveryController@destroy')->name('api.delivery.destroy');

Route::get('/reception/{reception}', 'Api\ApiController@getPackages');

Route::delete('/reception/{reception}/package/{package}', 'Api\ApiController@deletePackage');

Route::get('/delivery/{delivery}/product/{product}', 'Api\ApiController@getProduct');

Route::get('/statistics/reception', 'Api\ReceptionStatisticsController@index')->name('api.statistics.reception.data');

Route::get('/statistics/reception/drilldown', 'Api\ReceptionStatisticsController@drilldown')->name('api.statistics.reception.drilldown.data');

Route::get('/statistics/delivery', 'Api\DeliveryStatisticsController@index')->name('api.statistics.delivery.data');

Route::get('/statistics/delivery/drilldown', 'Api\DeliveryStatisticsController@drilldown')->name('api.statistics.delivery.drilldown.data');

Route::get('/delivery/{delivery}/products', 'Api\DeliveryController@show');

Route::get('/companies/get', 'Api\ApiController@getCompanies')->name('api.companies.get');

Route::post('/settings/buildDatabase', 'Api\SettingController@buildDatabase')->name('api.settings.buildb');
