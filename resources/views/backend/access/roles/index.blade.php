@extends ('backend.layouts.app')

@section ('title',app_name() . ' | ' . trans('labels.backend.access.roles.management'))

@section('after-styles')
@stop

@section('page-header')
    <h1>{{ trans('labels.backend.access.roles.management') }}</h1>
@endsection

@section('content')
    <div class="portlet light ">
        <div class="portlet-title">
            <div class="caption">{{ trans('labels.backend.access.users.active') }}</div>

            <div class="actions">
                @include('backend.access.includes.partials.role-header-buttons')
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="portlet-body">
            <table id="roles-table" class="table table-striped table-bordered table-hover">
                <thead>
                <tr>
                    <th>{{ trans('labels.backend.access.roles.table.role') }}</th>
                    <th>{{ trans('labels.backend.access.roles.table.permissions') }}</th>
                    <th>{{ trans('labels.backend.access.roles.table.number_of_users') }}</th>
                    <th>{{ trans('labels.backend.access.roles.table.sort') }}</th>
                    <th>{{ trans('labels.general.actions') }}</th>
                </tr>
                </thead>
            </table>
        </div><!-- /.box-body -->
    </div><!--box-->
@stop

@section('after-scripts')

    <script>
        $(function () {
            $('#roles-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route("admin.access.role.get") }}',
                    type: 'get'
                },
                columns: [
                    {data: 'name', name: '{{config('access.roles_table')}}.name', render: $.fn.dataTable.render.text()},
                    {data: 'permissions', name: '{{config('access.permissions_table')}}.display_name', sortable: false},
                    {data: 'users', name: 'users', searchable: false, sortable: false},
                    {data: 'sort', name: '{{config('access.roles_table')}}.sort', render: $.fn.dataTable.render.text()},
                    {data: 'actions', name: 'actions', searchable: false, sortable: false}
                ],
                order: [[3, "asc"]],
                searchDelay: 500
            });
        });
    </script>
@stop