<ul class="nav nav-tabs">
    <li class="{{ Active::route(['admin.client.show']) }}">
        <a href="{{ route('admin.client.show', $company) }}"> Details </a>
    </li>
    <li class="{{ Active::pattern('admin/client/*/users*') }}">
        <a href="{{ route('admin.client.users', $company) }}"> Users </a>
    </li>
    <li class="{{ Active::pattern('admin/client/*/receptions') }}">
        <a href="{{ route('admin.client.receptions', $company) }}"> Receptions </a>
    </li>
    <li class="{{ Active::pattern('admin/client/*/stock') }}">
        <a href="{{ route('admin.client.stock', $company) }}"> Stock </a>
    </li>
    <li class="{{ Active::pattern('admin/client/*/delivered') }}">
        <a href="{{ route('admin.client.delivered', $company) }}"> Delivered Stock </a>
    </li>
    <li class="{{ Active::pattern('admin/client/*/deliveries') }}">
        <a href="{{ route('admin.client.deliveries', $company) }}"> Deliveries </a>
    </li>
    <li class="{{ Active::pattern('admin/client/*/inventory') }}">
        <a href="{{ route('admin.client.inventory', $company) }}"> Inventory </a>
    </li>
</ul>
