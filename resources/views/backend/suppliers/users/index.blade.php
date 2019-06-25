@extends('backend.layouts.app')

@section ('title', app_name() . ' | Supplier ' . $company->name . ' Details')

@section('after-styles')
    <link rel="stylesheet" href="{{ asset('assets/layouts/layout4/css/custom.min.css') }}">
@endsection

@section('page-header')
    <h1>
        Supplier Details
    </h1>
@endsection

@section('content')
    <div class="portlet light ">
        <div class="portlet-title tabbable-line">
            <div class="caption">Supplier {{ $company->name }}</div>
            @include ('backend.suppliers.partials.tabs')
            <div class="actions">

            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="portlet-body">
            <div class="tab-content">
                <div class="tab-pane active">
                    {!! $dataTable->table() !!}
                </div>
            </div>
        </div><!-- /.box-body -->
    </div><!--box-->
@stop

@section('after-scripts')
    <script>
        $.fn.dataTable.ext.buttons.createSupplierUser = {
            text: '<i class="fa fa-plus"></i> Add User',
            className: 'btn xs default',
            action: function ( e, dt, node, config ) {
                window.location = "{{ route('admin.supplier.user.create', $company) }}";
            }
        };
    </script>
    {!! $dataTable->scripts() !!}
@stop