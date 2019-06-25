@extends ('backend.layouts.app')

@section ('title',app_name() . ' | ' . trans('labels.backend.access.users.management') . ' | ' . trans('labels.backend.access.users.edit'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.access.users.management') }}
        <small>{{ trans('labels.backend.access.users.edit') }}</small>
    </h1>
@endsection

@section('content')
    <div class="portlet light">
        <div class="portlet-title">
            <div class="caption">
                {{ trans('labels.backend.access.users.edit') }}
            </div>
            <div class="actions">
                @include('backend.access.includes.partials.user-header-buttons')
            </div>
        </div>
        <div class="portlet-body form">
            {{ Form::model($user, ['route' => ['admin.access.user.update', $user], 'role' => 'form', 'method' => 'PATCH']) }}
            <div class="form-body">
                <div class="form-group">
                    {{ Form::label('name', trans('validation.attributes.backend.access.users.name')) }}
                    {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.access.users.name')]) }}
                </div><!--form control-->
                <div class="form-group">
                    {{ Form::label('email', trans('validation.attributes.backend.access.users.email')) }}
                    {{ Form::text('email', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.access.users.email')]) }}
                </div><!--form control-->
            </div>
            @if ($user->id != 1)
                <div class="form-group">
                    {{ Form::label('status', trans('validation.attributes.backend.access.users.active')) }}
                    {{ Form::checkbox('status', '1', $user->status == 1) }}
                </div><!--form control-->

                <div class="form-group">
                    {{ Form::label('confirmed', trans('validation.attributes.backend.access.users.confirmed')) }}
                    {{ Form::checkbox('confirmed', '1', $user->confirmed == 1) }}
                </div><!--form control-->

                <div class="form-group">
                    {{ Form::label('status', trans('validation.attributes.backend.access.users.associated_roles')) }}
                    <div class="">
                        @if (count($roles) > 0)
                            @foreach($roles as $role)
                                <input type="checkbox" value="{{$role->id}}" name="assignees_roles[{{ $role->id }}]"
                                       {{ is_array(old('assignees_roles')) ? (in_array($role->id, old('assignees_roles')) ? 'checked' : '') : (in_array($role->id, $user_roles) ? 'checked' : '') }} id="role-{{$role->id}}"/>
                                <label for="role-{{$role->id}}">{{ $role->name }}</label>
                                <a href="#" data-role="role_{{$role->id}}" class="show-permissions small">
                                    (
                                    <span class="show-text">{{ trans('labels.general.show') }}</span>
                                    <span class="hide-text hidden">{{ trans('labels.general.hide') }}</span>
                                    {{ trans('labels.backend.access.users.permissions') }}
                                    )
                                </a>
                                <br/>
                                <div class="permission-list hidden" data-role="role_{{$role->id}}">
                                    @if ($role->all)
                                        <blockquote
                                                class="small">{{ trans('labels.backend.access.users.all_permissions') }}
                                        </blockquote>
                                    @else
                                        @if (count($role->permissions) > 0)
                                            <blockquote class="small">{{--
                                            --}}@foreach ($role->permissions as $perm){{--
                                            --}}{{$perm->display_name}}<br/>
                                                @endforeach
                                            </blockquote>
                                        @else
                                            <blockquote
                                                    class="small">{{ trans('labels.backend.access.users.no_permissions') }}
                                            </blockquote>
                                        @endif
                                    @endif
                                </div><!--permission list-->
                            @endforeach
                        @else
                            <blockquote
                                    class="small">{{ trans('labels.backend.access.users.no_roles') }}
                            </blockquote>
                        @endif
                    </div><!--col-lg-3-->
                </div><!--form control-->
            @endif
            <div class="form-actions right">
                <button type="submit" class="btn blue" data-disable-with="<i class='fa fa-refresh fa-spin fa-fw'></i> Sauvegarde...">
                    <span class="fa fa-save"></span> Sauvegarder
                </button>
                <button type="reset" value="Reset" class="btn default">Annuler</button>
                @if ($user->id == 1)
                    {{ Form::hidden('status', 1) }}
                    {{ Form::hidden('confirmed', 1) }}
                    {{ Form::hidden('assignees_roles[]', 1) }}
                @endif
            </div>
            {{ Form::close() }}
        </div>
    </div>
@stop

@section('after-scripts')
    {{ Html::script('js/backend/access/users/script.js') }}
@stop
