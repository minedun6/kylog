<?php

Breadcrumbs::register('admin.inventory.index', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.dashboard');
    $breadcrumbs->push('Inventory Management', route('admin.inventory.index'));
});

Breadcrumbs::register('admin.inventory.show', function ($breadcrumbs, $inventory) {
    $breadcrumbs->parent('admin.inventory.index');
    $breadcrumbs->push('View Inventory Detail', route('admin.inventory.show', $inventory));
});