@extends('backend.layouts.app')

{{-- This template is responsible for showing the reception packages and the packages items --}}

@section ('title')
    {{ app_name() }} | Deliveries Management
@endsection

@section('after-styles')
    <link rel="stylesheet" href="{{ asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.css') }}">
@endsection

@section('page-header')
    <h1>
        Deliveries Management
    </h1>
@endsection

@section('content')

    <div class="portlet light">
        <div class="portlet-title">
            <div class="caption">Delivery Details</div>
            <div class="actions">
                @permission('edit-delivery')
                {!! $delivery->getEditButton() !!}
                <a href="{{ route('admin.delivery.package.create', $delivery) }}" class="btn blue btn-outline">
                    <i class="fa fa-plus"></i> More Packages
                </a>
                <a href="{{ route('admin.delivery.products.index', $delivery) }}" class="btn btn-default">
                    <i class="fa fa-pencil"></i> Edit Packages
                </a>
                @endauth
                <a class="btn btn-default" href="{{ route('admin.delivery.report', $delivery) }}">
                    <i class="fa fa-print"></i> Print
                </a>
                @permission('delete-delivery')
                    {!! $delivery->getDeleteButton() !!}
                @endauth
            </div>
        </div>
        <div class="portlet-body form">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-5">
                        <div class="well">
                            <address style="line-height: 22px;">
                                <strong>Delivery Num : </strong>
                                {!! $delivery->delivery_number !!}
                                <br>
                                <strong>Supplier(s) : </strong>
                                {!! $delivery->getSuppliers() !!}
                                <br>
                                <strong>Client : </strong>
                                {!! $delivery->getClient() !!}
                                <br>
                                <strong>Delivery Order Date : </strong>
                                {!! $delivery->getDeliveryOrderDate() !!}
                                <br>
                            </address>
                        </div>
                    </div>
                    <div class="col-md-5 col-md-offset-2">
                        <div class="well">
                            <address style="line-height: 22px;">
                                <strong>BL Date : </strong>
                                {!! $delivery->getBLDate() !!}
                                <br>
                                <strong>Destination : </strong>
                                <span>
                                    {!! $delivery->getDestination() !!}
                                </span>
                                <br>
                                <strong>Delivery Outside Working Hours : </strong>
                                {!! $delivery->getDeliveryOutsideWorkingHours() !!}
                                <br>
                                <strong>Final Destination : </strong>
                                {!! $delivery->final_destination !!}
                                <br>
                                <strong>PO : </strong>
                                {!! $delivery->po !!}
                                <br>
                            </address>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="portlet light">
        <div class="portlet-title">
            <div class="caption">Delivery Package Details</div>
            <div class="actions">
            </div>
        </div>
        <div class="portlet-body">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th width="70%">Product</th>
                    <th width="10%"></th>
                    <th width="10%">Packages</th>
                    <th width="10%">Qty</th>
                </tr>
                </thead>
                <tbody>
                @foreach($delivery->packageItems->unique('product_id') as $item)
                    <tr>
                        <td width="70%">{{ $item->product->designation }} - {{ $item->product->supplier_reference }}</td>
                        <td width="10%"></td>
                        <td width="10%">{{ $delivery->packageItems()->where('product_id', $item->product->id)->get()->count() }} {{ $item->package->getPackageType() }}</td>
                        <td width="10%">{{ $delivery->packageItems()->where('product_id', $item->product->id)->get()->sum(function($i) {return $i->pivot->qty;}) }} {{ $item->product->getProductType() }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td width="70%">
                        <span class="bold">
                        </span>
                    </td>
                    <td width="10%">
                        <span class="bold">
                            Total
                        </span>
                    </td>
                    <td width="10%">
                        <span class="bold">
                            {{ $delivery->packageItems->count() }}
                        </span>
                    </td>
                    <td width="10%">
                        <span class="bold">
                            {{ $delivery->packageItems->sum(function ($i) {return $i->pivot->qty;}) }}
                        </span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection

@section('after-scripts')
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="{{ asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.js') }}"></script>
    <script>
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const app = new Vue({
            'el': '#app',
            methods: {
                deleteDelivery() {
                    let self = this;
                    swal({
                            title: "Are you sure you want to delete this delivery ?",
                            text: "You will not be able to recover this delivery later!",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Yes, delete it!",
                            closeOnConfirm: false,
                            showLoaderOnConfirm: true
                        },
                        function () {
                            axios.delete('{{route("api.delivery.destroy", $delivery)}}')
                                .then(function (response) {
                                    if (response.data.response === 'success') {
                                        window.location.href = '{{ route('admin.delivery.index') }}';
                                    }
                                });
                            swal("Deleted!", "This delivery has been deleted.", "success");
                        });
                }
            }
        })

    </script>
@endsection