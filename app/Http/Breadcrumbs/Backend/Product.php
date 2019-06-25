<?php

Breadcrumbs::register('admin.product.index', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.dashboard');
    $breadcrumbs->push('Products Management', route('admin.product.index'));
});

Breadcrumbs::register('admin.product.create', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.product.index');
    $breadcrumbs->push('Create Product', route('admin.product.create'));
});

Breadcrumbs::register('admin.product.show', function ($breadcrumbs, $product) {
    $breadcrumbs->parent('admin.product.index');
    $breadcrumbs->push('View Product Details', route('admin.product.show', $product));
});
