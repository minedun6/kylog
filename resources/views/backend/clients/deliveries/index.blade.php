@extends('backend.layouts.app')

@section ('title', app_name() . ' | Client ' . $company->name . ' Details')

@section('after-styles')
    <link rel="stylesheet" href="{{ asset('assets/layouts/layout4/css/custom.min.css') }}">
@endsection

@section('page-header')
    <h1>
        Client Details
    </h1>
@endsection

@section('content')
    <div class="portlet light ">
        <div class="portlet-title tabbable-line">
            <div class="caption">Client {{ $company->name }}</div>
            @include ('backend.clients.partials.tabs')
            <div class="actions">

            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="portlet-body">
            <div class="tab-content">
                <div class="tab-pane active">
                    {!! $dataTable->table() !!}
                </div>
            </div>
        </div><!-- /.box-body -->
    </div><!--box-->
@stop

@section('after-scripts')
    {!! $dataTable->scripts() !!}
@stop
