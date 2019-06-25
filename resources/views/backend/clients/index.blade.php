@extends('backend.layouts.app')

@section ('title', app_name() . ' | Clients Management')

@section('page-header')
    <h1>
        {{ app_name() }} | Clients Management
    </h1>
@endsection

@section('content')
    <div class="portlet light ">
        <div class="portlet-title">
            <div class="caption">Clients</div>

            <div class="actions">
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="portlet-body">
            {!! $dataTable->table() !!}
        </div><!-- /.box-body -->
    </div><!--box-->
@stop

@section('after-scripts')
    <script>
        $.fn.dataTable.ext.buttons.createClient = {
            text: '<i class="fa fa-plus"></i> Add Client',
            className: 'btn xs default',
            action: function (e, dt, node, config) {
                window.location = "{{ route('admin.client.create') }}";
            }
        };
    </script>
    {!! $dataTable->scripts() !!}
@stop
