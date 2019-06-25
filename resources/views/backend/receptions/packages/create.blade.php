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
            <div class="caption">Add Packages/Products</div>
            <div class="actions">
                <button :disabled="isLoading || packages.length == 0"
                        v-on:click.prevent="saveReception({{$reception->id}})" class="btn red btn-outline">
                    <i v-show="isLoading" class="fa fa-spinner fa-spin"></i> Save Reception Entries
                </button>
            </div>
        </div>
        <div class="portlet-body form">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-5 col-md-offset-1">
                        <div class="form-group">
                            <label for="package_type">Package Type</label>
                            <select class="form-control select2" name="package_type" id="package_type"
                                    v-model="newPackage.package_type">
                                <option value="1">Carton</option>
                                <option value="2">Palette</option>
                                <option value="3">Unit</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="number_packages">Number of Packages</label>
                            <input class="form-control" id="number_packages" type="number" max="1000" min="1"
                                   name="number_packages" v-model="newPackage.number_packages"/>
                        </div>
                        <div class="form-group">
                            <label for="bin_location">Bin Location</label>
                            <input class="form-control" type="text" name="bin_location" id="bin_location"
                                   v-model="newPackage.bin_location">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="package_state">Package State</label>
                            <select class="form-control select2" name="package_state" id="package_state"
                                    v-model="newPackage.package_state">
                                <option value="1">Quarantine</option>
                                <option value="2">Litigation</option>
                                <option value="3">Invalid</option>
                                <option value="4">OK</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="batch_number">Batch number</label>
                            <input class="form-control" type="text" name="batch_number" id="batch_number"
                                   v-model="newPackage.batch_number">
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions right">
                <button class="btn blue btn-outline" v-on:click.prevent="addPackage(newPackage)">Add package</button>
            </div>
        </div>
    </div>

    <transition-group tag="div" name="fadeInUp">
        <div class="portlet light" v-for="(package, key) in packages" :key="key">
            <div class="portlet-title">
                <div class="caption">
                    Package # @{{ key + 1 }}
                </div>
                <div class="actions">
                    <button class="btn btn-circle btn-icon-only btn-default" v-on:click.prevent="removePackage(key)">
                        <i class="icon-trash"></i>
                    </button>
                </div>
            </div>
            <div class="portlet-body">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="package_type">Package Type</label>
                                <select class="form-control select2"
                                        v-model="package.package_type">
                                    <option value="1">Carton</option>
                                    <option value="2">Palette</option>
                                    <option value="3">Unit</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Number of Packages</label>
                                <input class="form-control" type="number" max="1000" min="1"
                                        v-model="package.number_packages"/>
                            </div>
                            <div class="form-group">
                                <label for="bin_location">Bin Location</label>
                                <input class="form-control" type="text" v-model="package.bin_location">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="package_state">Package State</label>
                                <select class="form-control select2"
                                        v-model="package.package_state">
                                    <option value="1">Quarantine</option>
                                    <option value="2">Litigation</option>
                                    <option value="3">Invalid</option>
                                    <option value="4">OK</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="batch_number">Batch number</label>
                                <input class="form-control" type="text"
                                       v-model="package.batch_number">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="portlet blue-chambray box">
                                <div class="portlet-title">
                                    <div class="caption">
                                        Package #@{{ key + 1 }} - products
                                    </div>
                                    <div class="actions">
                                        <button v-on:click.prevent="addProduct(key)" class="btn btn-default btn-sm">
                                            <i class="fa fa-plus"></i> Add
                                        </button>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th width="30%">Designation</th>
                                            <th width="20%">PO</th>
                                            <th width="10%">Qty</th>
                                            <th width="20%">Number of SubPackages</th>
                                            <th width="20%">SubPackage Type</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody name="fadeInUp" is="transition-group">
                                        <tr v-for="( product, k ) in package.products" :key="k">
                                            <td width="30%">
                                                <select class="form-control" v-select2="product" data-placeholder="Select a Product ...">
                                                    <option value=""></option>
                                                    @foreach ($products as $product)
                                                        <option value="{{ $product->id }}">{{ $product->designation }} - {{ $product->supplier_reference }}</option>
                                                    @endforeach
                                                </select>
                                                <input type="hidden" v-model="product.designation">
                                            </td>
                                            <td width="20%">
                                                <input type="text" class="form-control"
                                                       v-model="product.po"
                                                       max="100" min="1">
                                            </td>
                                            <td width="10%">
                                                <input type="number" class="form-control" v-model="product.qty"
                                                       max="100"
                                                       min="1">
                                            </td>
                                            <td width="20%">
                                                <input type="number" class="form-control"
                                                       v-model="product.subpackages_number"
                                                       max="100" min="1">
                                            </td>
                                            <td width="20%">
                                                <select class="form-control" name="product" id="product"
                                                        v-model="product.subpackage_type">
                                                    <option value="1">Carton</option>
                                                    <option value="2">Unit</option>
                                                </select>
                                            </td>
                                            <td>
                                                <button v-on:click.prevent="removeProduct(key, k)"
                                                        class="btn btn-outline btn-circle red btn-sm black">
                                                    <i class="fa fa-trash-o"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </transition-group>

@endsection

@section('after-scripts')
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/backend/init.js') }}" type="text/javascript"></script>
    <script>
        Vue.directive('select2', {
            // When the bound element is inserted into the DOM...
            inserted: function (el, binding) {
                // Focus the element
                $(el).select2({
                    placeholder: $(el).data('placeholder'),
                    theme: "bootstrap",
                    allowClear: true,
                    width: null
                }).on('change', function () {
                    binding.value.designation = $(el).val();
                })
            }
        });
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const app = new Vue({
            'el': '#app',
            data() {
                return {
                    packages: [],
                    newPackage: this.instanceNewPackage(),
                    newProduct: this.instanceNewProduct(),
                    isLoading: false
                }
            },
            methods: {
                addPackage(newPackage) {
                    this.packages.push(newPackage);
                    this.newPackage = this.instanceNewPackage()
                },
                removePackage(existingPackage) {
                    this.packages.splice(existingPackage, 1);
                },
                addProduct(key) {
                    this.packages[key].products.push(this.instanceNewProduct());
                },
                removeProduct(key, k) {
                    this.packages[key].products.splice(k, 1);
                },
                instanceNewPackage() {
                    return {
                        package_type: '',
                        number_packages: 1,
                        bin_location: '',
                        package_state: 4,
                        batch_number: '',
                        products: [],
                    }
                },
                instanceNewProduct() {
                    return {
                        qty: 1,
                        po: null,
                        subpackages_number: 1,
                        subpackage_type: '',
                        designation: ''
                    }
                },
                saveReception() {
                    this.isLoading = true;
                    axios.post('{{ route('admin.reception.package.store', $reception) }}', this.packages)
                        .then((response) => {
                            this.isLoading = false;
                            console.log(response);
                            window.location.href = "{{ route('admin.reception.show', $reception) }}"
                        })
                        .catch((error) => {
                            this.isLoading = false;
                            console.log(error);
                        })
                }
            }
        });

        $('select[name="package_type"]').on('change', function () {
            app['newPackage'].package_type = $(this).val();
        });

        $('select[name="package_state"]').on('change', function () {
            app['newPackage'].package_state = $(this).val();
        });

    </script>
@endsection
