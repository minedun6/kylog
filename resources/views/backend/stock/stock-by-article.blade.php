@extends('backend.layouts.app')

@section ('title', app_name() . ' | View Stock')

@section('after-styles')
    <link rel="stylesheet"
          href="{{ asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css') }}">
    <link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link rel="stylesheet" href="{{ asset('assets/layouts/layout4/css/custom.min.css') }}">
@endsection

@section('page-header')
    <h1>
        View Stock By Article
    </h1>
@endsection

@section('content')
    <div class="portlet light ">
        <div class="portlet-title">
            <div class="caption">Stock By Article</div>

            <div class="actions">
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="portlet-body">
            <div class="modal fade in" id="detailedStockCustomFilterModal" tabindex="-1" role="basic"
                 aria-hidden="true">
                <div class="modal-dialog modal-lg" style="width: 1000px">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <h4 class="modal-title">Custom Filter</h4>
                        </div>
                        <div class="modal-body">

                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            @include ('backend.stock.partials.stock-by-article-filter-form')
            {!! $dataTable->table() !!}
        </div><!-- /.box-body -->
    </div><!--box-->
@stop

@section('after-scripts')
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="{{ asset('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/backend/init.js') }}" type="text/javascript"></script>
    <script>
        $.fn.dataTable.ext.buttons.customFilter = {
            text: '<i class="fa fa-filter"></i>',
            className: 'btn xs default',
            action: function (e, dt, node, config) {
                // use the bootstrap api to open the modal
                $('#detailedStockCustomFilterModal').modal();
            }
        };
    </script>
    {!! $dataTable->scripts() !!}
    <script>
        let modalDetailedStockCustomFilter = $('#detailedStockCustomFilterModal').find(".modal-body");
        console.log(modalDetailedStockCustomFilter);
        let detailedStockCustomFilterModalHtml = "";
        modalDetailedStockCustomFilter.html('');
        $('#detailedStockCustomFilterModal').on("shown.bs.modal", function (event) {
            window.dispatchEvent(new Event('resize'));
            modalDetailedStockCustomFilter.html('<div style="height: 643px"><img src="{{ asset('assets/global/img/balls.gif') }}" alt="" class="center-block loading"/></div>');
            setTimeout(function () {
                modalDetailedStockCustomFilter.load("{{route('admin.stock.detailed.modal')}}", function (response) {
                    detailedStockCustomFilterModalHtml = response;
                    window.dispatchEvent(new Event('resize'));
                });
            }, 500);
        });
    </script>
    <script>
        $(document).ready(function () {
            let product_reference = $('#product_reference');
            let product = $('#product');
            let supplier = $('#supplier');
            let client = $('#client');
            let dataTable = $('#dataTableBuilder');

            $(".actions").append($(".dt-buttons"));

            $("#detailed_stock_btn").on('click', function (e) {
                dataTable.DataTable().draw();
            });

            $('#detailed_stock_reset').on('click', function (e) {
                product_reference.val('').trigger('change');
                product.val('').trigger('change');
                supplier.val('').trigger('change');
                client.val('').trigger('change');
                dataTable.DataTable().draw();
            });

            dataTable.on('preXhr.dt', function (e, settings, data) {
                data.product_reference = product_reference.val();
                data.product = product.val();
                data.supplier = supplier.val();
                data.client = client.val();
            });
        });
    </script>
@stop
