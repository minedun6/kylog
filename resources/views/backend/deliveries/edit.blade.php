@extends('backend.layouts.app')

@section ('title')
    {{ app_name() }} | Deliveries Management
@endsection

@section('after-styles')
    <link rel="stylesheet" href="{{ asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css') }}">
    <link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('page-header')
    <h1>
        Deliveries Management
    </h1>
@endsection

@section('content')
    <!-- BEGIN CREATE DELIVERY PORTLET-->
    <div class="portlet light">
        <div class="portlet-title tabbable-line">
            <div class="caption">Add New Delivery</div>
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#general" data-toggle="tab"> General Informations </a>
                </li>
                <li>
                    <a href="#files" data-toggle="tab"> Files </a>
                </li>
            </ul>
        </div><!-- /.box-header -->
        <div class="portlet-body form">
            {{ Form::model($delivery, ['route' => ['admin.delivery.update', $delivery], 'role' => 'form', 'method' => 'PUT']) }}
            @include('backend.deliveries.partials.form')
            {{ Form::close() }}
        </div>
    </div>
    <!-- END CREATE DELIVERY PORTLET-->

@endsection

@section('after-scripts')
    <script src="{{ asset('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/backend/init.js') }}" type="text/javascript"></script>
@endsection
