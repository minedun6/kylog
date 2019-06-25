@extends('backend.layouts.app')

@section ('title')
    {{ app_name() }} | Receptions Management
@endsection

@section('after-styles')
    <link rel="stylesheet"
          href="{{ asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css') }}">
    <link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet"
          type="text/css"/>
@endsection

@section('page-header')
    <h1>
        Receptions Management
    </h1>
@endsection

@section('content')
    <div class="portlet light">
        <div class="portlet-title tabbable-line">
            <div class="caption">Add New Reception</div>
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#general" data-toggle="tab"> General Informations </a>
                </li>
                <li>
                    <a href="#transport" data-toggle="tab"> Transport </a>
                </li>
                <li>
                    <a href="#files" data-toggle="tab"> Files </a>
                </li>
            </ul>
        </div><!-- /.box-header -->

        <div class="portlet-body form">
            {!! Form::open(['route' => 'admin.reception.store']) !!}
            @include ('backend.receptions.partials.form')
            {!! Form::close() !!}
        </div><!-- /.box-body -->
    </div><!--box-->
@stop

@section('after-scripts')
    <script src="{{ asset('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/backend/init.js') }}" type="text/javascript"></script>
    <script>

        Vue.directive('datepicker', function (elt) {
            $(elt).datepicker({
                format: "dd-mm-yyyy",
                todayHighlight: true,
                autoclose: true
            });
        });

        Vue.directive('select2', {
            twoWay: true,
            priority: 1000,
            bind: function () {
                var self = this;
                $(this.el)
                    .select2({})
                    .on('change', function () {
                        self.set($(self.el).val()); // Don't use this.value
                    })
            },
            update: function (value) {
                $(this.el).val(value).trigger('change')
            },
            unbind: function () {
                $(this.el).off().select2('destroy')
            }
        });

        const app = new Vue({
            el: '#app',
            data() {
                return {
                    type: ''
                }
            },
            watch: {
                type: function () {
                    _this = this;

                    if (_this.type == 1) {
                        $('input[name = "declaration_date"]').datepicker({
                            orientation: "left",
                            language: 'fr',
                            autoclose: !0
                        })
                    }
                }
            }
        });

        $('select[name="type"]').on('change', function () {
            app['type'] = $(this).val();
        });

    </script>
@stop
