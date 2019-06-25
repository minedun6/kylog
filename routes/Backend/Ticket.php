<?php

Route::group([
    'namespace' => 'Ticket',
], function () {
    Route::group([
        'middleware' => 'access.routeNeedsPermission:manage-tickets',
    ], function () {
        Route::resource('ticket', 'TicketController');
    });
});