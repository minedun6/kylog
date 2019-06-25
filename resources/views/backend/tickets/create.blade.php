@extends('backend.layouts.app')

@section ('title')
    {{ app_name() }} | Support Tickets Management
@endsection

@section('after-styles')
    <link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet"
          type="text/css"/>
@endsection

@section('page-header')
    <h1>
        Support Tickets Management
    </h1>
@endsection

@section('content')
    <div class="portlet light">
        <div class="portlet-title">
            <div class="caption">Add New Ticket</div>
        </div><!-- /.box-header -->

        <div class="portlet-body form">
            {!! Form::open(['route' => 'admin.ticket.store', 'class' => 'form-horizontal']) !!}
            @include ('backend.tickets.partials.form')
            {!! Form::close() !!}
        </div><!-- /.box-body -->
    </div><!--box-->
@stop

@section('after-scripts')
    <script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
    <script src='{{ asset('assets/global/plugins/tinymce/js/tinymce/tinymce.min.js') }}'></script>
    <script src="{{ asset('js/backend/init.js') }}" type="text/javascript"></script>
@stop
