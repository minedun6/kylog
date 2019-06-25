<?php

Breadcrumbs::register('admin.ticket.index', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.dashboard');
    $breadcrumbs->push('Ticket Support Management', route('admin.ticket.index'));
});

Breadcrumbs::register('admin.ticket.create', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.ticket.index');
    $breadcrumbs->push('Create new ticket', route('admin.ticket.create'));
});

Breadcrumbs::register('admin.ticket.show', function ($breadcrumbs, $ticket) {
    $breadcrumbs->parent('admin.ticket.index');
    $breadcrumbs->push('Support Ticket Details', route('admin.ticket.show', $ticket));
});