<?php

Breadcrumbs::register('admin.dashboard', function ($breadcrumbs) {
    $breadcrumbs->push('Dashboard', route('admin.dashboard'));
});

require __DIR__ . '/Search.php';
require __DIR__ . '/Access.php';
require __DIR__ . '/Supplier.php';
require __DIR__ . '/Product.php';
require __DIR__ . '/Reception.php';
require __DIR__ . '/Stock.php';
require __DIR__ . '/Client.php';
require __DIR__ . '/Ticket.php';
require __DIR__ . '/Inventory.php';
require __DIR__ . '/Delivery.php';
require __DIR__ . '/Statistics.php';
require __DIR__ . '/LogViewer.php';
require __DIR__ . '/Setting.php';
