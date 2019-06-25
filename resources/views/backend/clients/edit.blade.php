@extends('backend.layouts.app')

@section ('title')
    {{ app_name() }} | Clients Management
@endsection

@section('after-styles')
    <link href="{{ asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('page-header')
    <h1>
        Clients management
    </h1>
@endsection

@section('content')
    <div class="portlet light">
        <div class="portlet-title">
            <div class="caption">Edit Client</div>

            <div class="actions">
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="portlet-body form">
            {{ Form::model($company, ['route' => ['admin.client.update', $company], 'files' => true, 'role' => 'form', 'method' => 'PUT']) }}
            @include ('backend.clients.partials.form')
            {!! Form::close() !!}
        </div><!-- /.box-body -->
    </div><!--box-->
@stop

@section('after-scripts')
    <script src="{{ asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') }}" type="text/javascript"></script>
@stop
