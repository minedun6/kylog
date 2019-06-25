@extends('backend.layouts.app')

@section ('title', app_name() . ' | Client ' . $company->name . ' Details')

@section('after-styles')
    <link href="{{ asset('assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet"
          type="text/css"/>
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
                    @include ('backend.receptions.partials.form-filter')
                    {!! $dataTable->table() !!}
                </div>
            </div>
        </div><!-- /.box-body -->
    </div><!--box-->
@stop

@section('after-scripts')
    <script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/backend/init.js') }}" type="text/javascript"></script>
    {!! $dataTable->scripts() !!}
    <script>
        $(document).ready(function () {
            let receptionDate = null;
            let reception_reference = $('#reception_reference');
            let supplier = $('#reception_supplier');
            let reception_date = $('#reception_date');
            let container_number = $('#container_number');
            let reception_status = $('#reception_status');
            let dataTable = $('#dataTableBuilder');

            $('.date-range').on('apply.daterangepicker', function (ev, picker) {
                receptionDate = picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY');
            });

            $("#reception_search").on('click', function (e) {
                dataTable.DataTable().draw();
            });

            $('#reception_reset').on('click', function (e) {
                // Manually reset the receptionDate value and resetting the .date-range_reception_date html
                reception_reference.val('').trigger("change");
                supplier.val(null).trigger("change");
                container_number.val(null).trigger("change");
                reception_status.val('').trigger("change");
                reception_date.html('<i class="icon-calendar"></i>&nbsp;<span class="thin uppercase"></span>&nbsp;<i class="fa fa-angle-down"></i>');
                receptionDate = null;
                dataTable.DataTable().draw();
            });

            dataTable.on('preXhr.dt', function (e, settings, data) {
                data.reception_reference = reception_reference.val();
                data.supplier = supplier.val();
                data.reception_date = receptionDate;
                data.container_number = container_number.val();
                data.reception_status = reception_status.val();
            });
        });
    </script>
@stop
