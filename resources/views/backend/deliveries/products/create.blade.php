@extends('backend.layouts.app')

{{-- This template is responsible for creating the reception packages and the packages items --}}

@section ('title')
    {{ app_name() }} | Receptions Management
@endsection

@section('after-styles')
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
        <div class="portlet-title">
            <div class="caption">
                Select a Product
            </div>
            <div class="actions">

            </div>
        </div>
        <div class="portlet-body form">
            <form class="form-horizontal" v-on:submit.prevent="submitProductSelection()">
                <div class="form-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Product</label>
                        <div class="col-md-5">
                            <select name="product" class="select2" data-placeholder="Select Product ..."
                                    v-model="selectedProduct">
                                <option value=""></option>
                                @foreach($supplierProducts as $k => $product)
                                    <option value="{{ $product->id }}">{{ $product->designation }} - {{ $product->supplier_reference }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-6">
                            <button type="submit" class="btn blue" :disabled="isLoading">
                                <span v-show="isLoading"><i class='fa fa-refresh fa-spin fa-fw'></i></span>
                                <span class="fa fa-save" v-show="!isLoading"></span>
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="portlet light">
        <div class="portlet-title">
            <div class="caption">
                Quantities
            </div>
            <div class="actions">

            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(['route' => ['admin.delivery.packages', $delivery], 'method' => 'get']) !!}
            <input type="hidden" name="product" v-model="selectedProduct">
            <div class="form-body">
                <table class="table table-hover table-light">
                    <thead>
                    <tr>
                        <th>
                            <label class="mt-checkbox mt-checkbox-outline">
                                <input type="checkbox" @click="toggleSelect" :checked="selectAll">
                                <span></span>
                            </label>
                        </th>
                        <th>Reference</th>
                        <th>Reception Date</th>
                        <th>Status</th>
                        <th>Package</th>
                        <th>Package Type</th>
                        <th>Package State</th>
                        <th>Remaining Quantity</th>
                        {{--<th>Total Quantity</th>--}}
                    </tr>
                    </thead>
                    <tbody name="fadeInUp" is="transition-group">
                    <tr v-if="items && items.data" v-for="(item,key) in items.data" :key="key">
                        <td>
                            <label class="mt-checkbox mt-checkbox-outline">
                                <input type="checkbox" name="packages[]" :value="item.id" v-model="item.checked">
                                <span></span>
                            </label>
                        </td>
                        <td>@{{ item.reception.reference }}</td>
                        <td>@{{ item.reception.reception_date }}</td>
                        <td>
                            <span :class="'label bg-' + receptionStatusColor(item)">@{{ receptionStatusText(item) }}</span>
                        </td>
                        <td>@{{ item.id }}</td>
                        <td>
                            <span :class="'label bg-' + packageTypeColor(item)">@{{ packageTypeText(item) }}</span>
                        </td>
                        <td>
                            <span :class="'label bg-' + packageStateColor(item)">@{{ packageStateText(item) }}</span>
                        </td>
                        <td>@{{ item.remaining }}</td>
                        {{--<td>@{{ item.totalQty }}</td>--}}
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-6">
                        <button :disabled="items.data == null" type="submit" class="btn blue btn-outline"
                                data-disable-with="<i class='fa fa-refresh fa-spin fa-fw'></i> Sauvegarde...">
                            <span class="fa fa-save"></span>
                            Save
                        </button>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection

@section('after-scripts')
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/backend/init.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/backend/config.js') }}"></script>
    <script>
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const app = new Vue({
            el: '#app',
            data() {
                return {
                    packages: [],
                    selectedProduct: '',
                    items: [],
                    isLoading: false,
                    config: []
                }
            },
            mounted() {
                this.config = configs;
            },
            computed: {
                selectAll: function () {
                    return this.items.data && this.items.data.every(function (item) {
                            return item.checked;
                        });
                },

            },
            methods: {
                submitProductSelection() {
                    this.isLoading = true;
                    axios.get('/api/delivery/' + "{{$delivery->id}}" + '/product/' + this.selectedProduct)
                        .then((response) => {
                            this.isLoading = false;
                            this.items = response.data;
                        })
                        .catch((error) => {
                            this.isLoading = false;
                            console.log(error);
                        });
                },
                toggleSelect: function () {
                    const select = this.selectAll;
                    this.items.data.forEach((item) => {
                        this.packages.push(item.id);
                        item.checked = !select;
                    });
                    this.selectAll = !select;
                },
                receptionStatusColor: function (item) {
                    if (item.reception.status !== null) {
                        return this.config.reception_states[item.reception.status].color
                    }
                    //return 'red-thunderbird';
                },
                receptionStatusText: function (item) {
                    if (item.reception.status !== null) {
                        return this.config.reception_states[item.reception.status].text
                    }
                    //return "Not Specified";
                },
                packageTypeColor: function (item) {
                    if (item.type !== null) {
                        return this.config.package_types[item.type].color
                    }
                    //return 'red-thunderbird';
                },
                packageTypeText: function (item) {
                    if (item.type !== null) {
                        return this.config.package_types[item.type].text
                    }
                    //return "Not Specified";
                },
                packageStateColor: function (item) {
                    if (item.state !== null) {
                        return this.config.package_states[item.state].color
                    }
                    //return 'red-thunderbird';
                },
                packageStateText: function (item) {
                    if (item.state !== null) {
                        return this.config.package_states[item.state].text
                    }
                    //return "Not Specified";
                },
            }
        });

        $('select[name="product"]').on('change', function () {
            app.selectedProduct = $(this).val();
        });
    </script>
@endsection
