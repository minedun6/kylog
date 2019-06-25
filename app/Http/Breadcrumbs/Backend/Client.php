<?php

Breadcrumbs::register('admin.client.index', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.dashboard');
    $breadcrumbs->push('Clients Management', route('admin.client.index'));
});

Breadcrumbs::register('admin.client.create', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.client.index');
    $breadcrumbs->push('Create client', route('admin.client.create'));
});

Breadcrumbs::register('admin.client.show', function ($breadcrumbs, $company) {
    $breadcrumbs->parent('admin.client.index');
    $breadcrumbs->push('Client Details', route('admin.client.show', $company));
});

Breadcrumbs::register('admin.client.edit', function ($breadcrumbs, $company) {
    $breadcrumbs->parent('admin.client.index');
    $breadcrumbs->push('Edit client', route('admin.client.edit', $company));
});

Breadcrumbs::register('admin.client.users', function ($breadcrumbs, $company) {
    $breadcrumbs->parent('admin.client.show', $company);
    $breadcrumbs->push('Client Users', route('admin.client.users', $company));
});

Breadcrumbs::register('admin.client.receptions', function ($breadcrumbs, $company) {
    $breadcrumbs->parent('admin.client.show', $company);
    $breadcrumbs->push('Client Receptions', route('admin.client.receptions', $company));
});

Breadcrumbs::register('admin.client.stock', function ($breadcrumbs, $company) {
    $breadcrumbs->parent('admin.client.show', $company);
    $breadcrumbs->push('Client Stock', route('admin.client.stock', $company));
});

Breadcrumbs::register('admin.client.delivered', function ($breadcrumbs, $company) {
    $breadcrumbs->parent('admin.client.show', $company);
    $breadcrumbs->push('Client Stock Delivered', route('admin.client.delivered', $company));
});

Breadcrumbs::register('admin.client.deliveries', function ($breadcrumbs, $company) {
    $breadcrumbs->parent('admin.client.show', $company);
    $breadcrumbs->push('Client Deliveries', route('admin.client.deliveries', $company));
});

Breadcrumbs::register('admin.client.inventory', function ($breadcrumbs, $company) {
    $breadcrumbs->parent('admin.client.show', $company);
    $breadcrumbs->push('Client Inventories', route('admin.client.inventory', $company));
});
