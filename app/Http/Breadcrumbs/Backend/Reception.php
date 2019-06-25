<?php

Breadcrumbs::register('admin.reception.index', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.dashboard');
    $breadcrumbs->push('Receptions Management', route('admin.reception.index'));
});

Breadcrumbs::register('admin.reception.create', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.reception.index');
    $breadcrumbs->push('Create Reception', route('admin.reception.create'));
});

Breadcrumbs::register('admin.reception.show', function ($breadcrumbs, $reception) {
    $breadcrumbs->parent('admin.reception.index');
    $breadcrumbs->push('Reception Details', route('admin.reception.show', $reception));
});


Breadcrumbs::register('admin.reception.entries.create', function ($breadcrumbs, $reception) {
    $breadcrumbs->parent('admin.reception.create'); // change it with a show
    $breadcrumbs->push('Add Packages/Products to the reception', route('admin.reception.entries.create', $reception));
});
