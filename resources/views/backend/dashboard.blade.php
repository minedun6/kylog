@extends('backend.layouts.app')

@section ('title')
    {{ app_name() }} | Dashboard
@endsection

@section('after-styles')
@endsection

@section('page-header')
    <h1>
        {{ trans('strings.backend.dashboard.title') }}
    </h1>
@endsection

@section('content')
    {{--@role('Administrator')--}}
    <!-- Go to useful.txt and get the content -->
    <div class="row widget-row">
        <div class="col-md-3">
            <widget-thumb
                    title="Receptions This Month"
                    source="{{ route('api.reception') }}"
                    icon="icon-plane"
                    company='{{ auth()->user()->company }}'
                    bg-color="bg-green">
            </widget-thumb>
        </div>
        <div class="col-md-3">
            <widget-thumb
                    title="Deliveries This Month"
                    source="{{ route('api.delivery') }}"
                    icon="icon-film"
                    company='{{ auth()->user()->company }}'
                    bg-color="bg-red">
            </widget-thumb>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <latest-receptions
                    title="Latest 5 Receptions"
                    url="{{ route('api.reception.latest') }}"
                    company="{{ access()->user()->company }}"
            >
            </latest-receptions>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <latest-deliveries
                    title="Latest 5 Deliveries"
                    url="{{ route('api.delivery.latest') }}"
                    company="{{ access()->user()->company }}"
            >
            </latest-deliveries>
        </div>
    </div>
    {{--@endif--}}
@endsection

@section('after-scripts')
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        const app = new Vue({
            el: '#app'
        });
    </script>
@endsection