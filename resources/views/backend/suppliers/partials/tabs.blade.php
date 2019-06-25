<ul class="nav nav-tabs">
    <li class="{{ Active::route(['admin.supplier.show']) }}">
        <a href="{{ route('admin.supplier.show', $company) }}"> Details </a>
    </li>
    <li class="{{ Active::pattern('admin/supplier/*/users*') }}">
        <a href="{{ route('admin.supplier.users', $company) }}"> Users </a>
    </li>
    <li class="{{ Active::pattern('admin/supplier/*/receptions') }}">
        <a href="{{ route('admin.supplier.receptions', $company) }}"> Receptions </a>
    </li>
    <li class="{{ Active::pattern('admin/supplier/*/stock/article') }}">
        <a href="{{ route('admin.supplier.stockByArticle', $company) }}"> Stock By Article </a>
    </li>
    <li class="{{ Active::pattern('admin/supplier/*/stock/detailed') }}">
        <a href="{{ route('admin.supplier.detailed', $company) }}"> Detailed Stock </a>
    </li>
    <li class="{{ Active::pattern('admin/supplier/*/stock/delivered') }}">
        <a href="{{ route('admin.supplier.delivered', $company) }}"> Delivered Stock </a>
    </li>
    <li class="{{ Active::pattern('admin/supplier/*/deliveries') }}">
        <a href="{{ route('admin.supplier.deliveries', $company) }}"> Deliveries </a>
    </li>
    <li class="{{ Active::pattern('admin/supplier/*/inventories') }}">
        <a href="{{ route('admin.supplier.inventories', $company) }}"> Inventory </a>
    </li>
</ul>
