@extends('backend.layouts.app')

{{-- This template is responsible for creating the reception packages and the packages items --}}

@section ('title')
    Kylogger | Inventory Management
@endsection

@section('page-header')
    <h1>
        Select Quantities from Packages
    </h1>
@endsection

@section('content')
    <div class="portlet light">
        <div class="portlet-title">
            <div class="caption">Fill in the appropriate quantities for each product</div>
            <div class="actions">
                <a onclick="buildEmptyInventoryLink()" class="btn btn-circle btn-default"><i class="fa fa-file-pdf-o"></i> Print Empty Inventory</a>
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(['route' => 'admin.inventory.save']) !!}
            <div class="form-body">
                <div class="table-scrollable table-scrollable-borderless">
                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <td width="30%">Product Reference</td>
                            <td width="30%">Product Designation</td>
                            <td width="20%">Quantity In System</td>
                            <td width="20%">Quantity in Warehouse</td>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td width="30%">
                                    <span class="bold">{{ $product->product_reference }}</span>
                                </td>
                                <td width="30%">
                                    <span class="bold">{{ $product->designation }}</span>
                                </td>
                                <td width="20%">
                                    {{ abs($product->quantities - $product->delivery_qty) }}
                                    {{ ($product->piece ? "Pieces" : $product->unit) }}
                                </td>
                                <td width="20%">
                                    <input type="number" class="form-control input-small"
                                           name="qty[]"
                                           min="0"/>
                                </td>
                            </tr>
                            <input type="hidden" name="product[]" value="{{ $product->product_id }}">
                            <input type="hidden" name="in_system[]"
                                   value="{{ $product->quantities - $product->delivery_qty }}">
                            <input type="hidden" name="company" value="{{ $company->id }}">
                        @endforeach
                        </tbody>
                    </table>
                </div>
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

@section('after-scripts')
    <script>
        let buildEmptyInventoryLink = function() {
            window.location.href = '{{ route('admin.inventory.empty.pdf') }}' + '?company=' + '{{ $company->id }}';
        };
    </script>
@endsection