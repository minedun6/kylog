@extends('backend.layouts.app')

@section ('title', app_name() . ' | View Stock')

@section('after-styles')
    <link href="{{ asset('assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link rel="stylesheet" href="{{ asset('assets/layouts/layout4/css/custom.min.css') }}">
@endsection

@section('page-header')
    <h1>
        View Detailed Stock
    </h1>
@endsection

@section('content')
    <div class="portlet light ">
        <div class="portlet-title">
            <div class="caption">Detailed Stock</div>

            <div class="actions">
                <div class="btn-group btn-group-devided" data-toggle="buttons">
                    @foreach(config('kylogger.package_types') as $k => $packageType)
                        <label class="btn btn-circle btn-transparent grey-salsa">
                            <input type="radio" name="package_type" value="{{ $k }}" class="toggle"
                                   id="option{{ $k }}"> {{ $packageType['text'] }}
                        </label>
                    @endforeach
                </div>
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="portlet-body">
            @include ('backend.stock.partials.filter-form')
            {!! $dataTable->table() !!}
        </div><!-- /.box-body -->
    </div><!--box-->
@stop

@section('after-scripts')
    <script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js') }}"
            type="text/javascript"></script>
    <script src="{{ asset('js/backend/init.js') }}" type="text/javascript"></script>
    {!! $dataTable->scripts() !!}
    <script>
        $(document).ready(function () {
            let receptionDate = null;
            let reception_date = $('#reception_date');
            let package_type = $('input[name="package_type"]');
            let product = $('#product');
            let reception_reference = $('#reception_reference');
            let status = $('#reception_status');
            let supplier = $('#supplier');
            let client = $('#client');
            let dataTable = $('#dataTableBuilder');

            package_type.on('change', function (e) {
                dataTable.DataTable().draw();
            });

            $('.date-range').on('apply.daterangepicker', function (ev, picker) {
                receptionDate = picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY');
            });

            $("#detailed_stock_btn").on('click', function (e) {
                dataTable.DataTable().draw();
            });

            $('#detailed_stock_reset').on('click', function (e) {
                product.val('').trigger('change');
                reception_reference.val('').trigger('change');
                status.val('').trigger('change');
                supplier.val('').trigger('change');
                client.val('').trigger('change');
                reception_date.html('<i class="icon-calendar"></i>&nbsp;<span class="thin uppercase"></span>&nbsp;<i class="fa fa-angle-down"></i>');
                receptionDate = null;
                dataTable.DataTable().draw();
            });

            dataTable.on('preXhr.dt', function (e, settings, data) {
                data.package_type = $('input[name="package_type"]:checked').val();
                data.product = product.val();
                data.reception_reference = reception_reference.val();
                data.reception_date = receptionDate;
                data.status = status.val();
                data.supplier = supplier.val();
                data.client = client.val();
            });
        });
    </script>
@stop
