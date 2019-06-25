@extends('backend.layouts.app')

@section ('title')
    {{ app_name() }} | Statistics
@endsection

@section('after-styles')
    <link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet"
          type="text/css"/>
@endsection

@section('page-header')
    <h1>
        Statistics
    </h1>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <chart
                    title="Statistics"
                    container="delivery-container"
                    drilldown="true"
                    drilldown_endpoint="{{ route('api.statistics.reception.drilldown.data') }}"
                    endpoint="{{ route('api.statistics.reception.data') }}"
                    chart_title=""
                    company="{{ access()->user()->company }}"
                    series_name="Deliveries"
            ></chart>
        </div>
        <div class="col-md-6">
            <chart
                    title="Statistics"
                    container="reception-container"
                    drilldown="true"
                    drilldown_endpoint="{{ route('api.statistics.delivery.drilldown.data') }}"
                    endpoint="{{ route('api.statistics.delivery.data') }}"
                    chart_title=""
                    company="{{ access()->user()->company }}"
                    series_name="Receptions"
            ></chart>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <stats
                    source="{{ route('admin.statistics.detailed') }}"
                    companies="{{ route('api.companies.get') }}"
            ></stats>
        </div>
    </div>
@endsection

@section('after-scripts')
    <script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript" src="https://apis.google.com/js/api.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        const app = new Vue({
            el: '#app'
        });
    </script>
@endsection
