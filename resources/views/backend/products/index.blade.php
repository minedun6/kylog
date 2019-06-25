@extends('backend.layouts.app')

@section ('title')
    {{ app_name() }} | Products Management
@endsection

@section('after-styles')
    <link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet"
          type="text/css"/>
@endsection

@section('page-header')
    <h1>
        Products Management
    </h1>
@endsection

@section('content')
    <div class="portlet light ">
        <div class="portlet-title">
            <div class="caption">Products</div>

            <div class="actions">
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="portlet-body">
            @include('backend.products.partials.filter-form')
            {!! $dataTable->table() !!}
        </div><!-- /.box-body -->
    </div><!--box-->
@endsection

@section('after-scripts')
    <script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/backend/init.js') }}" type="text/javascript"></script>
    <script>
        $.fn.dataTable.ext.buttons.createProduct = {
            text: '<i class="fa fa-plus"></i> Add Product',
            className: 'btn xs default',
            action: function (e, dt, node, config) {
                window.location = "{{ route('admin.product.create') }}";
            }
        };
    </script>
    {!! $dataTable->scripts() !!}
    <script>
        $(document).ready(function () {
            var product = $('#product');
            var supplier = $('#supplier');
            var reference = $('#reference');
            var supplier_reference = $('#supplier_reference');
            var value = $('#value');
            var net_weight = $('#net_weight');
            var dataTable = $('#dataTableBuilder');

            $(".actions").append($(".dt-buttons"));

            $("#product_search").on('click', function (e) {
                dataTable.DataTable().draw();
            });

            $('#product_reset').on('click', function (e) {
                product.val('').trigger('change');
                supplier.val('').trigger('change');
                reference.val('').trigger('change');
                supplier_reference.val('').trigger('change');
                value.val('').trigger('change');
                net_weight.val('').trigger('change');
                dataTable.DataTable().draw();
            });

            dataTable.on('preXhr.dt', function (e, settings, data) {
                data.product = product.val();
                data.supplier = supplier.val();
                data.reference = reference.val();
                data.supplier_reference = supplier_reference.val();
                data.value = value.val();
                data.net_weight = net_weight.val();
            });
        });
    </script>
@endsection
