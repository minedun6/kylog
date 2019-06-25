<?php

Breadcrumbs::register('admin.stock.detailed', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.dashboard');
    $breadcrumbs->push('View Detailed Stock', route('admin.stock.detailed'));
});

Breadcrumbs::register('admin.stock.article', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.dashboard');
    $breadcrumbs->push('View Stock By Article', route('admin.stock.article'));
});

Breadcrumbs::register('admin.stock.delivered', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.dashboard');
    $breadcrumbs->push('View Delivered Stock', route('admin.stock.delivered'));
});
