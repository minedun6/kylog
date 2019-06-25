@extends('backend.layouts.app')

@section ('title', app_name() . ' | Receptions')

@section('after-styles')

@endsection

@section('page-header')
    <h1>
        Settings
    </h1>
@endsection

@section('content')
    <div class="portlet light ">
        <div class="portlet-title">
            <div class="caption"><i class="fa fa-wrench"></i> Settings</div>
            <div class="actions">
                <a :disabled="isProcessing"
                        @click.prevent="buildDb" class="btn btn-circle btn-default tooltips" title="Build the database">
                    <span v-show="isProcessing">
                        <i class="fa fa-spinner fa-spin"></i> Building the database ...
                    </span>
                    <span v-show="!isProcessing">
                        Build the database
                    </span>
                </a>
            </div>
        </div><!--box-tools pull-right-->
    </div><!-- /.box-header -->

    <div class="portlet-body">

    </div><!-- /.box-body -->
    </div><!--box-->
@stop

@section('after-scripts')
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
        const app = new Vue({
            el: '#app',
            data() {
                return {
                    isProcessing: false
                }
            },
            methods: {
                buildDb() {
                    this.isProcessing = true
                    axios.post('{{route("api.settings.buildb")}}')
                        .then((response) => {
                            this.isProcessing = false
                        })
                        .catch((err) => {
                            this.isProcessing = false
                        })
                }
            }
        })
    </script>
@stop
