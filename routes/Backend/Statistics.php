<?php

Route::group([
    'namespace' => 'Statistics',
], function () {
    Route::get('statistics', 'StatisticsController@index')->name('statistics.index');
    Route::get('statistics/data/detailed', 'StatisticsController@getDetailedStats')->name('statistics.detailed');
});