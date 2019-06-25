<?php

Breadcrumbs::register('admin.supplier.index', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.dashboard');
    $breadcrumbs->push('Suppliers Management', route('admin.supplier.index'));
});

Breadcrumbs::register('admin.supplier.create', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.supplier.index');
    $breadcrumbs->push('Create Supplier', route('admin.supplier.create'));
});

Breadcrumbs::register('admin.supplier.show', function ($breadcrumbs, $company) {
    $breadcrumbs->parent('admin.supplier.index');
    $breadcrumbs->push('Supplier Details', route('admin.supplier.show', $company));
});

Breadcrumbs::register('admin.supplier.edit', function ($breadcrumbs, $company) {
    $breadcrumbs->parent('admin.supplier.index');
    $breadcrumbs->push('Edit Supplier', route('admin.supplier.edit', $company));
});

Breadcrumbs::register('admin.supplier.users', function ($breadcrumbs, $company) {
    $breadcrumbs->parent('admin.supplier.show', $company);
    $breadcrumbs->push('Supplier Users', route('admin.supplier.users', $company));
});

Breadcrumbs::register('admin.supplier.receptions', function ($breadcrumbs, $company) {
    $breadcrumbs->parent('admin.supplier.show', $company);
    $breadcrumbs->push('Supplier Receptions', route('admin.supplier.receptions', $company));
});

Breadcrumbs::register('admin.supplier.stockByArticle', function ($breadcrumbs, $company) {
    $breadcrumbs->parent('admin.supplier.show', $company);
    $breadcrumbs->push('Supplier Stock By Article', route('admin.supplier.stockByArticle', $company));
});

Breadcrumbs::register('admin.supplier.delivered', function ($breadcrumbs, $company) {
    $breadcrumbs->parent('admin.supplier.show', $company);
    $breadcrumbs->push('Supplier Delivered Stock', route('admin.supplier.delivered', $company));
});

Breadcrumbs::register('admin.supplier.detailed', function ($breadcrumbs, $company) {
    $breadcrumbs->parent('admin.supplier.show', $company);
    $breadcrumbs->push('Supplier Detailed Stock', route('admin.supplier.detailed', $company));
});

Breadcrumbs::register('admin.supplier.deliveries', function ($breadcrumbs, $company) {
    $breadcrumbs->parent('admin.supplier.show', $company);
    $breadcrumbs->push('Supplier Deliveries', route('admin.supplier.deliveries', $company));
});

Breadcrumbs::register('admin.supplier.inventories', function ($breadcrumbs, $company) {
    $breadcrumbs->parent('admin.supplier.show', $company);
    $breadcrumbs->push('Supplier Inventories', route('admin.supplier.inventories', $company));
});
