@extends('backend.layouts.app')

@section ('title', app_name() . ' | Ticket Support')

@section('after-styles')
    <link href="{{ asset('assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet"
          type="text/css"/>
@endsection

@section('page-header')
    <h1>
        Ticket Support Management
    </h1>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="portlet box white">
                <div class="portlet-title">
                    <div class="caption" style="margin-left: 40%;">
                        Support Tickets
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-3">
                            <ticket-stat-widget
                                    type="default"
                                    count="{{ $statuses->sum('tickets_count') }}"
                                    title="Total tickets"
                                    offs="true"
                                    icon="fa-th">
                            </ticket-stat-widget>
                        </div>
                        @foreach($statuses as $status)
                            <div class="col-lg-3 col-md-3">
                                <ticket-stat-widget
                                        type="{{ $status->class }}"
                                        count="{{ $status->tickets_count }}"
                                        offs="false"
                                        title="{{ ucfirst($status->name) }} tickets"
                                        icon="fa-{{ $status->icon }}">
                                </ticket-stat-widget>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="portlet box white">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-tag"></i> My Tickets
                    </div>
                </div>
                <div class="portlet-body">
                    {!! $dataTable->table() !!}
                </div>
            </div>
        </div>
    </div>
@stop

@section('after-scripts')
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        const app = new Vue({
            el: '#app'
        });
    </script>
    <script>
        $.fn.dataTable.ext.buttons.createTicketSupport = {
            text: '<i class="fa fa-plus"></i>',
            className: 'btn xs default',
            action: function (e, dt, node, config) {
                window.location = "{{ route('admin.ticket.create') }}";
            }
        };
    </script>
    {!! $dataTable->scripts() !!}
@stop