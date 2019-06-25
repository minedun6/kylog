@extends ('backend.layouts.app')

@section ('title',app_name() . ' | ' . trans('labels.backend.access.roles.management') . ' | ' . trans('labels.backend.access.roles.create'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.access.roles.management') }}
        <small>{{ trans('labels.backend.access.roles.create') }}</small>
    </h1>
@endsection

@section('content')
    {{ Form::open(['route' => 'admin.access.role.store', 'role' => 'form', 'method' => 'post', 'id' => 'create-role']) }}

    <div class="portlet light">
        <div class="portlet-title">
            <div class="caption">Add new Role</div>

            <div class="actions">
                @include('backend.access.includes.partials.role-header-buttons')
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->


        <div class="portlet-body form">
            <div class="form-body">
                <div class="form-group">
                    {{ Form::label('name', trans('validation.attributes.backend.access.roles.name')) }}
                    {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.access.roles.name')]) }}
                </div><!--form control-->
                <div class="form-group">
                    {{ Form::label('associated-permissions', trans('validation.attributes.backend.access.roles.associated_permissions')) }}
                    {{ Form::select('associated-permissions', array('all' => trans('labels.general.all'), 'custom' => trans('labels.general.custom')), 'all', ['class' => 'form-control']) }}
                    <div id="available-permissions" class="hidden mt-20 mb-20">
                        <div class="row">
                            <div class="col-xs-12">
                                @if ($permissions->count())
                                    @foreach ($permissions as $perm)
                                        <input type="checkbox" name="permissions[{{ $perm->id }}]"
                                               value="{{ $perm->id }}"
                                               id="perm_{{ $perm->id }}" {{ is_array(old('permissions')) && in_array($perm->id, old('permissions')) ? 'checked' : '' }} />
                                        <label for="perm_{{ $perm->id }}">{{ $perm->display_name }}</label><br/>
                                    @endforeach
                                @else
                                    <p>There are no available permissions.</p>
                                @endif
                            </div><!--col-lg-6-->
                        </div><!--row-->
                    </div><!--available permissions-->
                </div><!--form control-->
                <div class="form-group">
                    {{ Form::label('sort', trans('validation.attributes.backend.access.roles.sort')) }}
                    {{ Form::text('sort', ($role_count+1), ['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.access.roles.sort')]) }}
                </div><!--form control-->
            </div>
            <div class="form-actions right">
                <button type="submit" class="btn blue"
                        data-disable-with="<i class='fa fa-refresh fa-spin fa-fw'></i> Sauvegarde...">
                    <span class="fa fa-save"></span> Sauvegarder
                </button>
                <button type="reset" value="Reset" class="btn default">Annuler</button>
            </div>
        </div>
    </div>

    {{ Form::close() }}
@stop

@section('after-scripts')
    {{ Html::script('js/backend/access/roles/script.js') }}
@stop
