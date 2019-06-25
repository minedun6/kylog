<ul class="page-sidebar-menu   " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
    <li class="heading">
        <h3>
            Dashboard
        </h3>
    </li>
    <li class="nav-item start {{ Active::pattern('admin/dashboard') }}">
        <a href="{{ route('admin.dashboard') }}" class="nav-link nav-toggle">
            <i class="icon-home"></i>
            <span class="title">{{ trans('menus.backend.sidebar.dashboard') }}</span>
        </a>
    </li>

    @permission('manage-receptions')
    <li class="nav-item {{ Active::pattern('admin/reception*') }}">
        <a href="{{ route('admin.reception.index') }}" class="nav-link nav-toggle">
            <i class="icon-plane"></i>
            <span class="title">Receptions</span>
        </a>
    </li>
    @endauth

    @permission('manage-stocks')
    <li class="nav-item {{ Active::pattern('admin/stock*') }}">
        <a href="javascript:;" class="nav-link nav-toggle">
            <i class="icon-book-open"></i>
            <span class="title">Stock</span>
            <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
            @permission('view-detailed-stock')
            <li class="nav-item  {{ Active::pattern('admin/stock/detailed*') }}">
                <a href="{{ route('admin.stock.detailed') }}" class="nav-link ">
                    <i class="icon-user"></i>
                    <span class="title">Detailed Stock</span>
                </a>
            </li>
            @endauth
            @permission('view-stock-by-article')
            <li class="nav-item  {{ Active::pattern('admin/stock/article*') }}">
                <a href="{{ route('admin.stock.article') }}" class="nav-link ">
                    <i class="icon-notebook"></i>
                    <span class="title">Stock By Article</span>
                </a>
            </li>
            @endauth
            @permission('view-delivered-stock')
            <li class="nav-item  {{ Active::pattern('admin/stock/delivered*') }}">
                <a href="{{ route('admin.stock.delivered') }}" class="nav-link ">
                    <i class="icon-notebook"></i>
                    <span class="title">Delivered Stock</span>
                </a>
            </li>
            @endauth
        </ul>
    </li>
    @endauth

    @permission('manage-inventories')
    <li class="nav-item {{ Active::pattern('admin/inventory*') }}">
        <a href="{{ route('admin.inventory.index') }}" class="nav-link nav-toggle">
            <i class="icon-calendar"></i>
            <span class="title">Inventory</span>
        </a>
    </li>
    @endauth

    @permission('manage-deliveries')
    <li class="nav-item {{ Active::pattern('admin/delivery*') }}">
        <a href="{{ route('admin.delivery.index') }}" class="nav-link nav-toggle">
            <i class="icon-film"></i>
            <span class="title">Deliveries</span>
        </a>
    </li>
    @endauth

    @permission('manage-products')
    <li class="nav-item {{ Active::pattern('admin/product*') }}">
        <a href="{{ route('admin.product.index') }}" class="nav-link nav-toggle">
            <i class="icon-drawer"></i>
            <span class="title">Products</span>
        </a>
    </li>
    @endauth

    @permission('manage-clients')
    <li class="nav-item {{ Active::pattern('admin/client*') }}">
        <a href="{{ route('admin.client.index') }}" class="nav-link nav-toggle">
            <i class="icon-users"></i>
            <span class="title">Clients</span>
        </a>
    </li>
    @endauth

    @permission('manage-suppliers')
    <li class="nav-item last {{ Active::pattern('admin/supplier*') }}">
        <a href="{{ route('admin.supplier.index') }}" class="nav-link nav-toggle">
            <i class="icon-globe-alt"></i>
            <span class="title">Suppliers</span>
        </a>
    </li>
    @endauth

    @role('Administrator')
    <li class="nav-item last {{ Active::pattern('admin/statistics*') }}">
        <a href="{{ route('admin.statistics.index') }}" class="nav-link nav-toggle">
            <i class="icon-pie-chart"></i>
            <span class="title">Statistics</span>
        </a>
    </li>
    @endauth

    @permissions(['manage-users', 'manage-roles'])
    <li class="heading">
        <h3 class="uppercase">
            Access
        </h3>
    </li>
    <li class="nav-item ">
        <a href="javascript:;" class="nav-link nav-toggle">
            <i class="icon-users"></i>
            <span class="title">{{ trans('menus.backend.access.title') }}</span>
            <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
            @permission('manage-users')
            <li class="nav-item  {{ Active::pattern('admin/access/user*') }}">
                <a href="{{ route('admin.access.user.index') }}" class="nav-link ">
                    <i class="icon-user"></i>
                    <span class="title">{{ trans('labels.backend.access.users.management') }}</span>
                </a>
            </li>
            <li class="nav-item  {{ Active::pattern('admin/access/role*') }}">
                <a href="{{ route('admin.access.role.index') }}" class="nav-link ">
                    <i class="icon-notebook"></i>
                    <span class="title">{{ trans('labels.backend.access.roles.management') }}</span>
                </a>
            </li>
            @endauth
        </ul>
    </li>
    @endauth

    @permissions(['manage-tickets'])
    <li class="heading">
        <h3>
            Support
        </h3>
    </li>
    <li class="nav-item {{ Active::pattern('admin/ticket*') }}">
        <a href="{{ route('admin.ticket.index') }}" class="nav-link nav-toggle">
            <i class="icon-support"></i>
            <span class="title">Ticket Support</span>
        </a>
    </li>
    @endauth

    @permissions(['manage-settings'])
    <li class="heading">
        <h3>
            Settings
        </h3>
    </li>
    <li class="nav-item {{ Active::pattern('admin/settings*') }}">
        <a href="{{ route('admin.settings.index') }}" class="nav-link nav-toggle">
            <i class="icon-wrench"></i>
            <span class="title">Settings</span>
        </a>
    </li>
    @endauth

</ul>
