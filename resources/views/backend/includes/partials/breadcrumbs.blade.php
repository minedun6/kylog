@if ($breadcrumbs)
    <ul class="page-breadcrumb breadcrumb">
        @foreach ($breadcrumbs as $breadcrumb)
            @if (!$breadcrumb->last)
                @if(!$breadcrumb->first)
                    <i class="fa fa-circle"></i>
                @endif
                <li><a href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a></li>
            @else
                <li class="active"><i class="fa fa-circle"></i> {{ $breadcrumb->title }}</li>
            @endif
        @endforeach
    </ul>
@endif