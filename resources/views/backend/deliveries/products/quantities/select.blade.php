@extends('backend.layouts.app')

{{-- This template is responsible for creating the reception packages and the packages items --}}

@section ('title')
    {{ app_name() }} | Select Quantities from Packages
@endsection

@section('after-styles')
    <link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet"
          type="text/css"/>
@endsection

@section('page-header')
    <h1>
        Select Quantities from Packages
    </h1>
@endsection

@section('content')
    <div class="portlet light">
        <div class="portlet-title">
            <div class="caption">Select Quantities for "{{ $product->designation }} - {{ $product->supplier_reference }}
                " needed for the delivery ...
            </div>
            <div class="actions"></div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(['route' => ['admin.delivery.packages.saveQty', $delivery]]) !!}
            <div class="form-body">
                @foreach($packages as $package)
                    @foreach($package->packageItems()->wherePivot('product_id', $product->id)->groupBy('product_id')->get() as $packageItem)
                        <h4 class="page-header">Package #{{ $package->id }}  {{ $package->getPackageType() }}
                            <span class="label bg-{{ config("kylogger.package_types.{$package->state}.color") }}">{{ config("kylogger.package_types.{$package->state}.text") }}</span>
                        </h4>
                        <div class="table-scrollable table-scrollable-borderless">
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <td colspan="4">Reception Date</td>
                                    <td colspan="4">Supplier</td>
                                    <td colspan="4">Client</td>
                                    <td colspan="3">PO</td>
                                    <td colspan="3">Batch Number</td>
                                    <td colspan="3">Quantity</td>
                                    <td colspan="3">Total Qty</td>

                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td colspan="4">{{ $package->reception->getReceptionDate() }}</td>
                                    <td colspan="4">{{ $package->reception->supplier->name }}</td>
                                    <td colspan="4">{{ $package->reception->client->name }}</td>
                                    <td colspan="3">
                                        <input type="text" class="form-control input-small"
                                               name="po[]"
                                        />
                                    </td>
                                    <td colspan="3">
                                        <input type="text" class="form-control input-small"
                                               name="batch_number[]"
                                        />
                                    </td>
                                    <td colspan="3">
                                        <input type="number" class="form-control input-small"
                                               name="qty[]"
                                               min="1"
                                               max="@{{ $product->getRemaining($delivery, $package) }}"/>

                                        <input type="hidden" name="package_item_id[]"
                                               value="{{ $packageItem->pivot->id }}">


                                        <input type="hidden" name="product" value="{{ $product->id }}">

                                        <input type="hidden" name="packages[]" value="{{ $package->id }}">
                                    </td>
                                    <td colspan="3">
                                        {{ $package->getRemaining($product) }}
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                @endforeach
            </div>
            <div class="form-actions right">
                <button type="submit" class="btn blue btn-outline"
                        data-disable-with="<i class='fa fa-refresh fa-spin fa-fw'></i> Sauvegarde...">
                    <span class="fa fa-save"></span>
                    Save
                </button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
