<?php

Breadcrumbs::register('admin.statistics.index', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.dashboard');
    $breadcrumbs->push('View Statistics', route('admin.statistics.index'));
});