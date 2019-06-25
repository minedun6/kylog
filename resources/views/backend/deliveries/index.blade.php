@extends('backend.layouts.app')

@section ('title')
    {{ app_name() }} | Deliveries Management
@endsection

@section('after-styles')
    <link href="{{ asset('assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet"
          type="text/css"/>
@endsection

@section('page-header')
    <h1>
        Deliveries Management
    </h1>
@endsection

@section('content')
    <div class="portlet light ">
        <div class="portlet-title">
            <div class="caption">Deliveries</div>

            <div class="actions">
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="portlet-body">
            @include ('backend.deliveries.partials.filter-form')
            {!! $dataTable->table() !!}
        </div><!-- /.box-body -->
    </div><!--box-->
@endsection

@section('after-scripts')
    <script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js') }}"
            type="text/javascript"></script>
    <script src="{{ asset('js/backend/init.js') }}" type="text/javascript"></script>
    <script>
        $.fn.dataTable.ext.buttons.createDelivery = {
            text: '<i class="fa fa-plus"></i> Add Delivery',
            className: 'btn xs default',
            action: function (e, dt, node, config) {
                window.location = "{{ route('admin.delivery.create') }}";
            }
        };
    </script>
    {!! $dataTable->scripts() !!}
    <script>
        $(document).ready(function () {
            var deliveryOrderDate = null;
            var delivery_number = $('#delivery_number');
            var supplier = $('#supplier');
            var client = $('#client');
            var delivery_order_date = $('#delivery_order_date');
            var destination = $('#destination');
            var dataTable = $('#dataTableBuilder');

            $(".actions").append($(".dt-buttons"));

            delivery_order_date.on('apply.daterangepicker', function (ev, picker) {
                deliveryOrderDate = picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY');
            });

            $("#delivery_search_btn").on('click', function (e) {
                dataTable.DataTable().draw();
            });

            $('#delivery_reset_btn').on('click', function (e) {
                delivery_number.val('').trigger("change");
                supplier.val(null).trigger("change");
                client.val(null).trigger("change");
                destination.val('').trigger("change");
                delivery_order_date.html('<i class="icon-calendar"></i>&nbsp;<span class="thin uppercase"></span>&nbsp;<i class="fa fa-angle-down"></i>');
                deliveryOrderDate = null;
                dataTable.DataTable().draw();
            });

            dataTable.on('preXhr.dt', function (e, settings, data) {
                data.delivery_number = delivery_number.val();
                data.supplier = supplier.val();
                data.client = client.val();
                data.delivery_order_date = deliveryOrderDate;
                data.destination = destination.val();
            });
        });
    </script>
    <script>
        $(".actions").append($(".dt-buttons"));
    </script>
@endsection
