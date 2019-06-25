@extends('backend.layouts.app')

{{-- This template is responsible for showing the reception packages and the packages items --}}

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
                {!! $reception->getDeliveriesThatIsRelatedToReception() !!}
                {!! $reception->getDeliveredStockForReception() !!}
                @permission('edit-reception')
                {!! $reception->getEditButton() !!}
                <a href="{{ route('admin.reception.package.create', $reception) }}" class="btn btn-success tooltips"
                   title="Add more packages to reception">
                    <i class="fa fa-plus"></i> More Packages
                </a>
                <a href="{{ route('admin.reception.package.index', $reception) }}" class="btn default tooltips"
                   title="Edit packages that exists">
                    <i class="fa fa-pencil"></i> Edit Packages
                </a>
                @endauth
                <div class="btn-group">
                    <a class="btn default " href="javascript:;" data-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-print"></i> Print
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu pull-right">
                        <li>
                            <a href="{{ route('admin.reception.print.report', $reception) }}">
                                <i class="fa fa-file-text"></i> Reception Report
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.reception.print.labels', $reception) }}">
                                <i class="fa fa-file-text-o"></i> Package Labels
                            </a>
                        </li>
                    </ul>
                </div>
                @permission('delete-reception')
                {!! $reception->getDeleteButton() !!}
                @endauth
            </div>
        </div>
        {{-- Modal showing Deliveries using the current reception --}}
        <div class="modal fade in" id="getDeliveriesModal" tabindex="-1" role="basic" aria-hidden="true">
            <div class="modal-dialog modal-lg" style="width: 1200px">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4 class="modal-title">Deliveries which Current Reception have Packages For Reception
                            : {{ $reception->reference }}</h4>
                    </div>
                    <div class="modal-body">

                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

        {{-- Modal showing Delivered Stock used from the current reception --}}
        <div class="modal fade in" id="getDeliveredStockModal" tabindex="-1" role="basic" aria-hidden="true">
            <div class="modal-dialog modal-lg" style="width: 1200px">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4 class="modal-title">Delivered Stock used from the reception
                            : {{ $reception->reference }}</h4>
                    </div>
                    <div class="modal-body">

                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

        <div class="portlet-body form">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-5">
                        <div class="well">
                            <address style="line-height: 22px;">
                                <strong>Reference : </strong>
                                {{ $reception->reference }}
                                <br>
                                <strong>Supplier : </strong>
                                {!! $reception->getSupplier() !!}
                                <br>
                                <strong>Client : </strong>
                                {!! $reception->getClient() !!}
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
                            <address style="line-height: 20px;">
                                <strong>Reception Date : </strong>
                                {{ $reception->getReceptionDate() }}
                                <br>
                                <strong>Type : </strong>
                                {{ $reception->getType() }}
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
                                    {{ $reception->getDeclarationDate() }}
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
                <div class="row">
                    <div class="col-md-12">
                        <div class="well">
                            <address>
                                <strong>Summary of Reception</strong>
                                <span class="pull-right">
                                    <span class="bold">
                                        Reception Number of Packages : {{ $reception->packages->count() }} @{{ packagesCount }}
                                        Package(s)
                                    </span>
                                </span>
                                <br><br>
                                <table class="table table-condensed table-hover">
                                    <tbody>
                                    @foreach($reception->getSummary() as $item)
                                        <tr>
                                            <td width="70%">
                                                {{ $item->product->designation }}
                                                - {{ $item->product->supplier_reference }}
                                            </td>
                                            <td width="15%">
                                                <span class="bold">{{ $item->subpackages_total }}</span> SubPackages
                                            </td>
                                            <td width="15%">
                                                <span class="bold">{{ $item->pcs_total }}</span> {{ $item->product->getProductType() }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
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
                    @permission('edit-reception')
                    <button class="btn btn-circle btn-icon-only btn-default"
                            v-on:click.prevent="removePackage(key, package)">
                        <i class="icon-trash"></i>
                    </button>
                    @endauth
                </div>
            </div>
            <div class="portlet-body">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong>Package Type : </strong>
                                <span v-if="package.type == 1 ">Carton</span>
                                <span v-else-if="package.type == 2 ">Palette</span>
                                <span v-else-if="package.type == 3 ">Unit</span>
                            </div>
                            <div class="form-group">
                                <strong>Bin Location : </strong>
                                <span>@{{ package.bin_location }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong>Package State : </strong>
                                <span v-if="package.state == 1">Quarantine</span>
                                <span v-if="package.state == 2">Litigation</span>
                                <span v-if="package.state == 3">Invalid</span>
                                <span v-if="package.state == 4">OK</span>
                            </div>
                            <div class="form-group">
                                <strong for="batch_number">Batch Number : </strong>
                                <span>@{{ package.batch_number }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="portlet blue-chambray box">
                                <div class="portlet-title">
                                    <div class="caption">
                                        Package #@{{ package.id }} - products
                                    </div>
                                    <div class="actions">
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th colspan="7">Product</th>
                                            <th colspan="2">PO</th>
                                            <th colspan="2">SubPackage Type</th>
                                            <th colspan="2">Number of SubPackages</th>
                                            <th colspan="1">Qty</th>
                                            <th colspan="2">Total</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="item in package.items">
                                            <td colspan="7">
                                                <a :href="item.url">@{{ item.designation }}
                                                    - @{{ item.supplier_reference }}</a>
                                            </td>
                                            <td colspan="2">
                                                @{{ item.pivot.po }}
                                            </td>
                                            <td colspan="2">
                                                <span v-if="item.pivot.type == 1">Carton</span>
                                                <span v-else-if="item.pivot.type == 2">Unit</span>
                                            </td>
                                            <td colspan="2">
                                                @{{ item.pivot.subpackages_number }}
                                            </td>
                                            <td colspan="1">
                                                {{--Qty--}}
                                                @{{ item.pivot.qty + ' ' + (item.piece == 1 ? 'Pieces' : item.unit) }}
                                            </td>
                                            <td colspan="1">
                                            <span class="bold">
                                                @{{ (item.pivot.qty * item.pivot.subpackages_number) + ' ' + (item.piece == 1 ? 'Pieces' : item.unit) }}
                                            </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="7"></td>
                                            <td colspan="2"></td>
                                            <td colspan="2">
                                                <span class="bold">
                                                    Total
                                                </span>
                                            </td>
                                            <td colspan="2">
                                                <span class="bold">
                                                    @{{ package.subpackages }}
                                                </span>
                                            </td>
                                            <td colspan="1">
                                               <span class="bold">
                                                   @{{ package.qty }}
                                               </span>
                                            </td>
                                            <td colspan="1">
                                                <span class="bold">
                                                    @{{ package.totalQty }}
                                                </span>
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
                    packages: []
                }
            },
            computed: {
                packagesCount: function () {
                    return this.packages.length
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
                removePackage(key, package) {
                    let self = this;
                    swal({
                            title: "Are you sure you want to delete this package ?",
                            text: "You will not be able to recover this package later!",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Yes, delete it!",
                            closeOnConfirm: false,
                            showLoaderOnConfirm: true
                        },
                        function () {
                            axios.delete('/api/reception/' + '{{$reception->id}}' + '/package/' + package.id)
                                .then(function (response) {
                                    if (response.data.response === 'success') {
                                        self.packages.data.splice(key, 1);
                                        swal("Deleted!", "This package has been deleted.", "success");

                                    } else if (response.data.response === 'error') {
                                        swal("Error !", "This package can't be deleted.", "error");
                                    }
                                });
                        });
                },
                deleteReception() {
                    let self = this;
                    swal({
                            title: "Are you sure you want to delete this reception ?",
                            text: "You will not be able to recover this reception later!",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Yes, delete it!",
                            closeOnConfirm: false,
                            showLoaderOnConfirm: true,
                        },
                        function () {
                            axios.delete('{{route("api.reception.destroy", $reception)}}')
                                .then(function (response) {
                                    if (response.data.response === 'success') {
                                        swal("Deleted!", "This reception has been deleted.", "success");
                                        window.location.href = '{{ route('admin.reception.index') }}';
                                    } else if (response.data.response === 'error') {
                                        swal("An error Occured when Attempting to delete this reception.", "You can't delete this reception.", "warning");
                                    }
                                });
                        });
                }
            }
        })

    </script>
    {{-- For Deliveries --}}
    <script>
        let modalDeliveriesForReception = $('#getDeliveriesModal').find(".modal-body");
        let DeliveriesForReceptionModalHtml = "";
        modalDeliveriesForReception.html('');
        $('#getDeliveriesModal').on("shown.bs.modal", function (event) {
            window.dispatchEvent(new Event('resize'));
            let receptionId = $(event.relatedTarget).data('id') ? $(event.relatedTarget).data('id') : $('#getDeliveriesForReceptionBtn').data('id');
            modalDeliveriesForReception.html('<div style="height: 643px"><img src="{{ asset('assets/global/img/balls.gif') }}" alt="" class="center-block loading"/></div>');
            setTimeout(function () {
                modalDeliveriesForReception.load('/admin/reception/' + receptionId + '/model/form/data', function (response) {
                    DeliveriesForReceptionModalHtml = response;
                    window.dispatchEvent(new Event('resize'));
                    $('#deliveriesForReceptionTable').dataTable({
                        pagingType: 'bootstrap_extended',
                        responsive: true,
                    });
                });
            }, 500);
        });
    </script>
    {{-- For Delivered Stock --}}
    <script>
        let modalDeliveredStock = $('#getDeliveredStockModal').find(".modal-body");
        let DeliveredStockModalHtml = "";
        modalDeliveredStock.html('');
        $('#getDeliveredStockModal').on("shown.bs.modal", function (event) {
            window.dispatchEvent(new Event('resize'));
            let receptionId = $(event.relatedTarget).data('id') ? $(event.relatedTarget).data('id') : $('#getDeliveredStockForReceptionBtn').data('id');
            modalDeliveredStock.html('<div style="height: 643px"><img src="{{ asset('assets/global/img/balls.gif') }}" alt="" class="center-block loading"/></div>');
            setTimeout(function () {
                modalDeliveredStock.load('/admin/reception/' + receptionId + '/stock/delivered/model/form/data', function (response) {
                    DeliveredStockModalHtml = response;
                    window.dispatchEvent(new Event('resize'));
                    $('#deliveredStockForReceptionTable').dataTable({
                        pagingType: 'bootstrap_extended',
                        responsive: true,
                    });
                });
            }, 500);
        });
    </script>
@endsection
