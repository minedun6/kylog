@extends('backend.layouts.app')

{{-- This template will be used to edit the qty of the delivery's packages --}}

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
    <div class="portlet light" v-cloak>
        <div class="portlet-title">
            <div class="caption">Delivery Details</div>
            <div class="actions">
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(['route' => ['admin.delivery.products.update',$delivery], 'method' => 'patch']) !!}
            <div class="form-body">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th width="20%">Product</th>
                        <th width="20%">Package</th>
                        <td width="15">PO</td>
                        <td width="15">Batch Number</td>
                        <th width="15%">Qty</th>
                        <th width="10%">Remaining Qty</th>
                        <th width="5%"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(item, key) in packageItems" :key="key" v-cloak>
                        <td width="35%">
                            <a :href="item.product_link">@{{ item.designation }} - @{{ item.supplier_reference }}</a>
                        </td>
                        <td width="35%">
                            @{{ item.package }}
                        </td>
                        <td width="15">
                            <input type="text" name="po[]"
                                   class="form-control input-small"
                                   :value="item.po" />
                        </td>
                        <td width="15">
                            <input type="text" name="batch_number[]"
                                   class="form-control input-small"
                                   :value="item.batch_number" />
                        </td>
                        <td width="15%">
                            <input type="number" name="qty[]"
                                   class="form-control input-small"
                                   min="1" :value="item.qty">
                            <input type="hidden" v-model="item.pivot_id" name="delivery_package_id[]">
                            <input type="hidden" v-model="item.product.id" name="product_id[]">
                        </td>
                        <td width="10%">
                            @{{ item.remaining }}
                        </td>
                        <td width="5%">
                            <a class="btn btn-outline btn-xs red" @click.prevent="deleteDeliveryItem(key, item)">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="form-actions right">
                <button type="submit" class="btn blue"
                        data-disable-with="<i class='fa fa-refresh fa-spin fa-fw'></i> Sauvegarde...">
                    <span class="fa fa-save"></span>
                    Save
                </button>
                <button type="reset" value="Reset" class="btn default">Cancel</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection

@section('after-scripts')
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="{{ asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.js') }}"></script>
    <script>
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const app = new Vue({
            el: '#app',
            data() {
                return {
                    packageItems: [],
                    delivery: null,
                }
            },
            mounted: function () {
                this.getDeliveryProducts();
            },
            methods: {
                getDeliveryProducts: function () {
                    axios.get('/api/delivery/' + '{{ $delivery->id }}' + '/products')
                        .then((response) => {
                            this.$set(this, 'delivery', response.data);
                            this.$set(this, 'packageItems', response.data.data.items)
                        });
                },
                deleteDeliveryItem: function (key, packageItem) {
                    let self = this;
                    swal({
                            title: "Are you sure you want to delete this delivery item ?",
                            text: "You will not be able to recover this item later!",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Yes, delete it!",
                            closeOnConfirm: false,
                            showLoaderOnConfirm: true
                        },
                        function () {
                            axios.delete('/api/deliveries/' + '{{$delivery->id}}' + '/products/' + packageItem.pivot_id)
                                .then(function (response) {
                                    if (response.data.response === 'success') {
                                        self.packageItems.splice(key, 1);
                                        window.location.href = '{{ route('admin.delivery.show', $delivery) }}'
                                    }
                                });
                            swal("Deleted!", "This package has been deleted.", "success");
                        });
                }
            }
        });
    </script>
@endsection