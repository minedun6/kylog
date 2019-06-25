@permissions(['create-reception', 'create-delivery'])
<!-- BEGIN PAGE ACTIONS -->
<!-- DOC: Remove "hide" class to enable the page header actions -->
<div class="page-actions">
    <div class="btn-group">
        <button type="button" class="btn red-haze btn-sm dropdown-toggle" data-toggle="dropdown"
                data-hover="dropdown" data-close-others="true">
            <span class="hidden-sm hidden-xs">Quick Actions&nbsp;</span>
            <i class="fa fa-angle-down"></i>
        </button>
        <ul class="dropdown-menu" role="menu">
            <li>
                <a href="{{ route('admin.reception.create') }}">
                    New Reception</a>
            </li>
            <li>
                <a href="{{ route('admin.delivery.create') }}">
                    New Delivery</a>
            </li>
        </ul>
    </div>
</div>
<!-- END PAGE ACTIONS -->

@endauth