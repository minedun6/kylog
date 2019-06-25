<?php

Breadcrumbs::register('admin.delivery.index', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.dashboard');
    $breadcrumbs->push('Deliveries Management', route('admin.delivery.index'));
});

Breadcrumbs::register('admin.delivery.create', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.delivery.index');
    $breadcrumbs->push('Create Delivery', route('admin.delivery.create'));
});

Breadcrumbs::register('admin.delivery.show', function ($breadcrumbs, $delivery) {
    $breadcrumbs->parent('admin.delivery.index');
    $breadcrumbs->push('View Delivery Details', route('admin.delivery.show', $delivery));
});

Breadcrumbs::register('admin.delivery.edit', function ($breadcrumbs, $delivery) {
    $breadcrumbs->parent('admin.delivery.index');
    $breadcrumbs->push('Edit Delivery', route('admin.delivery.edit', $delivery));
});

Breadcrumbs::register('admin.delivery.package.create', function ($breadcrumbs, $delivery) {
    $breadcrumbs->parent('admin.delivery.show', $delivery);
    $breadcrumbs->push('Add Products to Delivery', route('admin.delivery.package.create', $delivery));
});
//
//Breadcrumbs::register('admin.delivery.packages.quantities', function ($breadcrumbs, $delivery) {
//    $breadcrumbs->parent('admin.delivery.package.create', $delivery);
//    $breadcrumbs->push('Add Products to Delivery', route('admin.delivery.packages.quantities', $delivery));
//});

Breadcrumbs::register('admin.delivery.products.index', function ($breadcrumbs, $delivery) {
    $breadcrumbs->parent('admin.delivery.show', $delivery);
    $breadcrumbs->push('Edit Delivery\'s Products Quantity ', route('admin.delivery.products.index', $delivery));
});
