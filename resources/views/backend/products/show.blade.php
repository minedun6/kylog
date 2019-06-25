@extends('backend.layouts.app')

{{-- This template is responsible for showing the reception packages and the packages items --}}

@section ('title')
    {{ app_name() }} | Products Management
@endsection

@section('page-header')
    <h1>
        Products Management
    </h1>
@endsection

@section('content')

    <div class="portlet light">
        <div class="portlet-title">
            <div class="caption">Product Details</div>
            <div class="actions">
                {!! $product->getEditButton() !!}
            </div>
        </div>
        <div class="portlet-body form">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-5 col-md-offset-1">
                        <div class="well">
                            <strong>Product Reference : </strong>
                            {!! $product->getProductReference() !!}
                            <br><br>
                            <strong>Product Supplier Reference : </strong>
                            {!! $product->getProductSupplierReference() !!}
                            <br><br>
                            <strong>Product Designation : </strong>
                            {!! $product->getDesgination() !!}
                            <br><br>
                            <strong>Code SAP : </strong>
                            {!! $product->getSAP() !!}
                            <br><br>
                            <strong>Product Supplier : </strong>
                            {!! $product->getSupplier() !!}
                            <br><br>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="well">
                            <strong>Product Value : </strong>
                            {!! $product->getValue() !!}
                            <br><br>
                            <strong>Product Net Weight: </strong>
                            {!! $product->getNetWeight() !!}
                            <br><br>
                            <strong>Product Gross Weight : </strong>
                            {!! $product->getBrutWeight() !!}
                            <br><br>
                            <strong>Count By Piece ? : </strong>
                            {!! $product->getCountByPiece() !!}
                            <br><br>
                            <strong>Product Unit : </strong>
                            <span class="label label-info">{!! $product->getUnit() !!}</span>
                            <br><br>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="well">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th style="text-align: center">Key</th>
                                    <th style="text-align: center">Value</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    @if(isset($product) && !is_null($product->getCustomAttributes()))
                                        @foreach($product->getCustomAttributes() as $k => $v)
                                            <td style="text-align: center"><span class="bold">{{ $k }}</span></td>
                                            <td style="text-align: center"><span class="bold">{{ $v }}</span></td>
                                        @endforeach
                                    @else
                                        <td colspan="2" style="text-align: center">
                                            <p class="bold">No custom Key/Value defined for this product</p>
                                        </td>
                                    @endif
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
