@extends('backend.layouts.app')

@section ('title', app_name() . ' | ' . trans('labels.backend.access.users.create'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.access.users.management') }}
        <small>{{ trans('labels.backend.access.users.create') }}</small>
    </h1>
@endsection

@section('content')
    <div class="portlet light ">
        <div class="portlet-title tabbable-line">
            <div class="caption">Client {{ $company->name }} Details</div>
            @include ('backend.clients.partials.tabs')
            <div class="actions">

            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="portlet-body">
            <div class="tab-content">
                <div class="tab-pane active form">
                    {{ Form::open(['route' => ['admin.client.user.store', $company], 'role' => 'form', 'method' => 'post']) }}
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
                        <div class="form-actions right">
                            <button type="submit" class="btn blue"
                                    data-disable-with="<i class='fa fa-refresh fa-spin fa-fw'></i> Sauvegarde...">
                                <span class="fa fa-save"></span> Sauvegarder
                            </button>
                            <button type="reset" value="Reset" class="btn default">Annuler</button>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div><!-- /.box-body -->
    </div><!--box-->
@stop

@section('after-scripts')
    {{ Html::script('js/backend/access/users/script.js') }}
@stop
