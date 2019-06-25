@extends ('backend.layouts.app')

@section ('title', app_name() . ' | ' .  trans('labels.backend.access.users.management') . ' | ' . trans('labels.backend.access.users.create'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.access.users.management') }}
        <small>{{ trans('labels.backend.access.users.create') }}</small>
    </h1>
@endsection

@section('content')
    <div class="portlet light">
        <div class="portlet-title">
            <div class="caption">
                {{ trans('labels.backend.access.users.create') }}
            </div>
            <div class="actions">
                @include('backend.access.includes.partials.user-header-buttons')
            </div>
        </div>
        <div class="portlet-body form">
            {{ Form::open(['route' => 'admin.access.user.store', 'role' => 'form', 'method' => 'post']) }}
            <div class="form-body">
                <div class="form-group">
                    {{ Form::label('name', trans('validation.attributes.backend.access.users.name')) }}
                    {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.access.users.name')]) }}
                </div><!--form control-->
                <div class="form-group">
                    {{ Form::label('email', trans('validation.attributes.backend.access.users.email')) }}
                    {{ Form::text('email', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.access.users.email')]) }}
                </div><!--form control-->

                <div class="form-group">
                    {{ Form::label('password', trans('validation.attributes.backend.access.users.password')) }}
                    {{ Form::password('password', ['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.access.users.password')]) }}
                </div><!--form control-->

                <div class="form-group">
                    {{ Form::label('password_confirmation', trans('validation.attributes.backend.access.users.password_confirmation')) }}
                    {{ Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.access.users.password_confirmation')]) }}
                </div><!--form control-->

                <div class="form-group">
                    {{ Form::label('status', trans('validation.attributes.backend.access.users.active')) }}
                    {{ Form::checkbox('status', '1', true) }}
                </div><!--form control-->

                <div class="form-group">
                    {{ Form::label('confirmed', trans('validation.attributes.backend.access.users.confirmed')) }}
                    {{ Form::checkbox('confirmed', '1', true) }}
                </div><!--form control-->

                <div class="form-group">
                    <label class="control-label">{{ trans('validation.attributes.backend.access.users.send_confirmation_email') }}
                        <br/>
                        <small>{{ trans('strings.backend.access.users.if_confirmed_off') }}</small>
                    </label>

                    {{ Form::checkbox('confirmation_email', '1') }}
                </div><!--form control-->

                <div class="form-group">
                    {{ Form::label('status', trans('validation.attributes.backend.access.users.associated_roles')) }}

                    <div class="">
                        @if (count($roles) > 0)
                            @foreach($roles as $role)
                                <input type="checkbox" value="{{ $role->id }}" name="assignees_roles[{{ $role->id }}]"
                                       id="role-{{ $role->id }}" {{ is_array(old('assignees_roles')) && in_array($role->id, old('assignees_roles')) ? 'checked' : '' }} />
                                <label for="role-{{ $role->id }}">{{ $role->name }}</label>
                                <a href="#" data-role="role_{{ $role->id }}" class="show-permissions small">
                                    (
                                    <span class="show-text">{{ trans('labels.general.show') }}</span>
                                    <span class="hide-text hidden">{{ trans('labels.general.hide') }}</span>
                                    {{ trans('labels.backend.access.users.permissions') }}
                                    )
                                </a>
                                <br/>
                                <div class="permission-list hidden" data-role="role_{{ $role->id }}">
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
                    </div>
                </div><!--form control-->

                <div class="form-actions right">
                    <button type="submit" class="btn blue" data-disable-with="<i class='fa fa-refresh fa-spin fa-fw'></i> Sauvegarde...">
                        <span class="fa fa-save"></span> Sauvegarder
                    </button>
                    <button type="reset" value="Reset" class="btn default">Annuler</button>
                </div>
                {{ Form::close() }}
            </div>
        </div>
@stop

@section('after-scripts')
    {{ Html::script('js/backend/access/users/script.js') }}
@stop
