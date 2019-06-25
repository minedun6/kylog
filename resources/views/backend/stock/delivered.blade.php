@extends('backend.layouts.app')

@section ('title', app_name() . ' | View Stock')

@section('after-styles')
    <link href="{{ asset('assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('page-header')
    <h1>
        View Delivered Stock
    </h1>
@endsection

@section('content')
    <div class="portlet light ">
        <div class="portlet-title">
            <div class="caption">Detailed Stock</div>

            <div class="actions">
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="portlet-body">
            @include ('backend.stock.partials.delivered-filter-form')
            {!! $dataTable->table() !!}
        </div><!-- /.box-body -->
    </div><!--box-->
@stop

@section('after-scripts')
    <script src="{{ asset('assets/global/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/backend/init.js') }}" type="text/javascript"></script>
    {!! $dataTable->scripts() !!}
    <script>
        $(document).ready(function () {
            let receptionDate = null;
            let deliveryOrderDate = null;
            let delivery_number = $('#delivery_number');
            let product = $('#product');
            let supplier = $('#supplier');
            let client = $('#client');
            let reception_date = $('#reception_date');
            let delivery_order_date = $('#delivery_order_date');
            let dataTable = $('#dataTableBuilder');

            $(".actions").append($(".dt-buttons"));

            $('#reception_date').on('apply.daterangepicker', function (ev, picker) {
                receptionDate = picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY');
            });

            $('#delivery_order_date').on('apply.daterangepicker', function (ev, picker) {
                deliveryOrderDate = picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY');
            });

            $("#delivered_stock_btn").on('click', function (e) {
                dataTable.DataTable().draw();
            });

            $('#delivered_stock_reset').on('click', function (e) {
                delivery_number.val().trigger('change');
                product.val('').trigger('change');
                supplier.val('').trigger('change');
                client.val('').trigger('change');
                reception_date.html('<i class="icon-calendar"></i>&nbsp;<span class="thin uppercase"></span>&nbsp;<i class="fa fa-angle-down"></i>');
                receptionDate = null;
                delivery_order_date.html('<i class="icon-calendar"></i>&nbsp;<span class="thin uppercase"></span>&nbsp;<i class="fa fa-angle-down"></i>');
                deliveryOrderDate = null;
                dataTable.DataTable().draw();
            });

            dataTable.on('preXhr.dt', function (e, settings, data) {
                data.delivery_number = delivery_number.val();
                data.product = product.val();
                data.supplier = supplier.val();
                data.client = client.val();
                data.reception_date = receptionDate;
                data.delivery_order_date = deliveryOrderDate;
            });
        });
    </script>
@endsection