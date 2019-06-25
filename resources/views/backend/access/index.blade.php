@extends ('backend.layouts.app')

@section ('title', app_name() . ' | ' . trans('labels.backend.access.users.management'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.access.users.management') }}
        <small>{{ trans('labels.backend.access.users.active') }}</small>
    </h1>
@endsection

@section('content')
    <div class="portlet light ">
        <div class="portlet-title">
            <div class="caption">{{ trans('labels.backend.access.users.active') }}</div>

            <div class="actions">
                @include('backend.access.includes.partials.user-header-buttons')
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="portlet-body">
            <table id="users-table" class="table table-striped table-bordered table-hover">
                <thead>
                <tr>
                    <th>{{ trans('labels.backend.access.users.table.id') }}</th>
                    <th>{{ trans('labels.backend.access.users.table.name') }}</th>
                    <th>{{ trans('labels.backend.access.users.table.email') }}</th>
                    <th>{{ trans('labels.backend.access.users.table.confirmed') }}</th>
                    <th>{{ trans('labels.backend.access.users.table.roles') }}</th>
                    <th>{{ trans('labels.backend.access.users.table.created') }}</th>
                    <th>{{ trans('labels.backend.access.users.table.last_updated') }}</th>
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
            $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route("admin.access.user.get") }}',
                    type: 'get',
                    data: {status: 1, trashed: false}
                },
                columns: [
                    {data: 'id', name: '{{config('access.users_table')}}.id'},
                    {data: 'name', name: '{{config('access.users_table')}}.name', render: $.fn.dataTable.render.text()},
                    {
                        data: 'email',
                        name: '{{config('access.users_table')}}.email',
                        render: $.fn.dataTable.render.text()
                    },
                    {data: 'confirmed', name: '{{config('access.users_table')}}.confirmed'},
                    {data: 'roles', name: '{{config('access.roles_table')}}.name', sortable: false},
                    {data: 'created_at', name: '{{config('access.users_table')}}.created_at'},
                    {data: 'updated_at', name: '{{config('access.users_table')}}.updated_at'},
                    {data: 'actions', name: 'actions', searchable: false, sortable: false}
                ],
                order: [[0, "asc"]],
                searchDelay: 500
            });
        });
    </script>
@stop
