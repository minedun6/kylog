@extends('backend.layouts.app')

@section ('title')
    {{ app_name() }} | Products Management
@endsection

@section('after-styles')
    <link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet"
          type="text/css"/>
@endsection

@section('page-header')
    <h1>
        Products Management
    </h1>
@endsection

@section('content')
    <!-- BEGIN CREATE PRODUCT PORTLET-->
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption font-red-sunglo">
                <span class="caption-subject bold uppercase">
                    Edit Product
                </span>
            </div>
            <div class="actions">

            </div>
        </div>
        <div class="portlet-body form">
            {{ Form::model($product, ['route' => ['admin.product.update', $product], 'role' => 'form', 'method' => 'PUT']) }}
            @include('backend.products.partials.form')
            {{ Form::close() }}
        </div>
    </div>
    <!-- END CREATE PRODUCT PORTLET-->

@endsection

@section('after-scripts')
    <script src="{{ asset('assets/global/plugins/jquery-repeater/jquery.repeater.js') }}"
            type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/backend/init.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/backend/products/create.js') }}"></script>
    <script>
        const app = new Vue({
            el: '#app',
            data() {
                return {
                    piece: '{{ $product->piece == "1" ? true : false }}'
                }
            }
        })
    </script>
@endsection
