@extends('backend.layouts.app')

{{-- This template is responsible for displaying the reception packages and the packages items and editing them --}}

@section ('title')
    {{ app_name() }} | Receptions Management
@endsection

@section('after-styles')
    <link rel="stylesheet" href="{{ asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.css') }}">
@endsection

@section('page-header')
    <h1>
        Receptions Management
    </h1>
@endsection

@section('content')

    <div class="portlet light" v-cloak>
        <div class="portlet-title">
            <div class="caption">Reception Details</div>
            <div class="actions">
                <button :disabled="isLoading || packages.length == 0"
                        v-on:click.prevent="saveReception({{$reception->id}})" class="btn red btn-outline">
                    <i v-show="isLoading" class="fa fa-spinner fa-spin"></i> Update
                </button>
                <div class="btn-group">
                    <a class="btn btn-default " href="javascript:;" data-toggle="dropdown"
                       aria-expanded="false">
                        <i class="fa fa-print"></i> Print
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu pull-right">
                        <li>
                            <a href="{{ route('admin.reception.print.report', $reception) }}" target="_blank">
                                <i class="fa fa-file-text"></i> Reception Report
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.reception.print.labels', $reception) }}" target="_blank">
                                <i class="fa fa-file-text-o"></i> Package Labels
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="portlet-body form">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-5">
                        <div class="well">
                            <address>
                                <strong>Reference : </strong>
                                {{ $reception->reference }}
                                <br>
                                <strong>Supplier : </strong>
                                {{ $reception->supplier->name }}
                                <br>
                                <strong>Client : </strong>
                                {{ $reception->client->name }}
                                <br>
                                <strong>PO : </strong>
                                {{ $reception->po }}
                                <br>
                                <strong>Invoice Number : </strong>
                                {{ $reception->invoice_number }}
                                <br>
                                <strong>Invoice Date : </strong>
                                {!! $reception->getInvoiceDate() !!}
                                <br>
                            </address>
                        </div>
                    </div>
                    <div class="col-md-5 col-md-offset-2">
                        <div class="well">
                            <address>
                                <strong>Reception Date : </strong>
                                {!! $reception->getReceptionDate() !!}
                                <br>
                                <strong>Type : </strong>
                                {!! $reception->getType() !!}
                                <br>
                                @if($reception->type == 1)
                                    {{--Container Info--}}
                                    <strong>Declaration Type : </strong>
                                    {{ $reception->declaration_type }}
                                    <br>
                                    <strong>Declaration Number : </strong>
                                    {{ $reception->declaration_number }}
                                    <br>
                                    <strong>Declaration Date : </strong>
                                    {!! $reception->getDeclarationDate() !!}
                                    <br>
                                    <strong>Container Number : </strong>
                                    {{ $reception->container_number }}
                                    <br>
                                @elseif($reception->type == 2)
                                    {{--Truck Info--}}
                                    <strong>Driver : </strong>
                                    {{ $reception->driver }}
                                    <br>
                                    <strong>Plate : </strong>
                                    {{ $reception->registration_number }}
                                    <br>
                                @elseif($reception->type == 3)
                                    {{--Other Info--}}
                                    <strong>Other : </strong>
                                    {{ $reception->other }}
                                    <br>
                                @endif
                                <strong>Reception Status : </strong>
                                {!! $reception->getReceptionStatus() !!}
                                <br>
                                <strong>Reservations : </strong>
                                {{ $reception->reservations }}
                                <br>
                            </address>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <transition-group name="fadeInUp">
        <div class="portlet light" v-for="(package, key) in packages.data" :key="key" v-cloak>
            <div class="portlet-title">
                <div class="caption">
                    Package #@{{ package.id }}
                </div>
                <div class="actions">
                </div>
            </div>
            <div class="portlet-body">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="package_type">Package Type</label>
                                <select class="form-control select2" name="package_type" id="package_type"
                                        v-model="package.type">
                                    <option value="1">Carton</option>
                                    <option value="2">Palette</option>
                                    <option value="3">Unit</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="bin_location">Bin Location</label>
                                <input class="form-control" type="text" name="bin_location" id="bin_location"
                                       v-model="package.bin_location">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="package_state">Package State</label>
                                <select class="form-control select2" name="package_state" id="package_state"
                                        v-model="package.state">
                                    <option value="1">Quarantine</option>
                                    <option value="2">Litigation</option>
                                    <option value="3">Invalid</option>
                                    <option value="4">OK</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="batch_number">Batch number</label>
                                <input class="form-control" type="text" name="batch_number" id="batch_number"
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
                                            <th width="30%">Description</th>
                                            <th width="20%">PO</th>
                                            <th width="10%">Qty</th>
                                            <th width="20%">Number of SubPackages</th>
                                            <th width="20%">SubPackage Type</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody name="fadeInUp" is="transition-group">
                                        <tr v-for="( product, k ) in package.items" :key="k">
                                            <td width="30%">
                                                <select class="form-control select2" name="product"
                                                        v-model="product.id">
                                                    @foreach ($products as $product)
                                                        <option value="{{ $product->id }}">{{ $product->designation }}
                                                            - {{ $product->supplier_reference }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td width="20%">
                                                <input type="text" class="form-control" v-model="product.pivot.po">
                                                {{--<input type="hidden" v-model="product.pivot.id" name="package_product_id[]">--}}
                                            </td>
                                            <td width="10%">
                                                <input type="number" class="form-control" v-model="product.pivot.qty"
                                                       max="100"
                                                       min="1">
                                            </td>
                                            <td width="20%">
                                                <input type="number" class="form-control"
                                                       v-model="product.pivot.subpackages_number"
                                                       max="100" min="1">
                                            </td>
                                            <td width="20%">
                                                <select class="form-control" name="type"
                                                        v-model="product.pivot.type">
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
    <script src="{{ asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.js') }}"></script>
    <script>
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
            mounted: function () {
                this.getPackages();
            },
            methods: {
                getPackages: function () {
                    axios.get('/api/reception/' + '{{ $reception->id }}')
                        .then((response) => {
                            this.$set(this, 'packages', response.data);
                        });
                },
                addPackage(newPackage) {
                    this.packages.data.push(newPackage);
                    this.newPackage = this.instanceNewPackage()
                },
                removePackage(existingPackage) {
                    this.packages.data.splice(existingPackage, 1);
                },
                addProduct(key) {
                    this.packages.data[key].items.push(this.instanceNewProduct());
                },
                removeProduct(key, k) {
                    this.packages.data[key].items.splice(k, 1);
                },
                instanceNewPackage() {
                    return {
                        package_type: '',
                        number_packages: 1,
                        bin_location: '',
                        package_state: '4',
                        batch_number: '',
                        products: [],
                    }
                },
                instanceNewProduct() {
                    return {
                        pivot: {
                            qty: 1,
                            po: null,
                            subpackages_number: 1,
                            type: '',
                            used_qty: 0
                        }
                    }
                },
                saveReception() {
                    this.isLoading = true;
                    axios.put('/admin/reception/' + '{{$reception->id}}' + '/packages', this.packages.data)
                        .then((response) => {
                            this.isLoading = false;
                            if (response.data.response === 'success') {
                                window.location.href = "{{ route('admin.reception.show', $reception) }}"
                            } else if (response.data.response === 'error') {
                                swal("Error!", response.data.errors, "warning");
                            }
                        })
                        .catch((error) => {
                            this.isLoading = false;
                            console.log(error);
                        })
                }
            }
        })

    </script>
@endsection
