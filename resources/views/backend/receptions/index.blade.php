@extends('backend.layouts.app')

@section ('title', app_name() . ' | Receptions')

@section('after-styles')
    <link href="{{ asset('assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <style>
        .dt-buttons {
        margin-top: -379px !important;
        }
    </style>
@endsection

@section('page-header')
    <h1>
        Receptions Management
    </h1>
@endsection

@section('content')
    <div class="portlet light ">
        <div class="portlet-title">
            <div class="caption">Receptions</div>

            <div class="actions" id="BDT">

            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="portlet-body">
            @include ('backend.receptions.partials.form-filter')
            {!! $dataTable->table() !!}
        </div><!-- /.box-body -->
    </div><!--box-->
@stop

@section('after-scripts')
    <script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/backend/init.js') }}" type="text/javascript"></script>
    <script>
        $.fn.dataTable.ext.buttons.createReception = {
            text: '<i class="fa fa-plus"></i> Add Reception',
            className: 'btn xs default',
            action: function (e, dt, node, config) {
                window.location = "{{ route('admin.reception.create') }}";
            }
        };
    </script>
    {!! $dataTable->scripts() !!}
    <script>
        $(document).ready(function () {
            let receptionDate = null;
            let reception_reference = $('#reception_reference');
            let supplier = $('#reception_supplier');
            let client = $('#reception_client');
            let reception_date = $('#reception_date');
            let container_number = $('#container_number');
            let reception_status = $('#reception_status');
            let product = $('#product');
            let dataTable = $('#dataTableBuilder');

            $(".actions").append($(".dt-buttons"));

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
                client.val(null).trigger("change");
                container_number.val(null).trigger("change");
                reception_status.val('').trigger("change");
                reception_date.html('<i class="icon-calendar"></i>&nbsp;<span class="thin uppercase"></span>&nbsp;<i class="fa fa-angle-down"></i>');
                receptionDate = null;
                product.val(null).trigger("change");
                dataTable.DataTable().draw();
            });

            dataTable.on('preXhr.dt', function (e, settings, data) {
                data.reception_reference = reception_reference.val();
                data.supplier = supplier.val();
                data.client = client.val();
                data.reception_date = receptionDate;
                data.container_number = container_number.val();
                data.reception_status = reception_status.val();
                data.product = product.val();
            });
        });
    </script>
@stop
