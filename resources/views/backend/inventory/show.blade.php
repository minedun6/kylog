@extends('backend.layouts.app')

@section ('title', app_name() . ' | Inventory')

@section('page-header')
    <h1>
        View Inventory of {{ $inventory->company->name }} dated at {{ $inventory->created_at->format('d-m-Y H:i') }}
    </h1>
@endsection

@section('content')
    <div class="portlet light ">
        <div class="portlet-title">
            <div class="caption">Inventory Details</div>

            <div class="actions">
                <a href="{{ route('admin.inventory.excel', $inventory) }}" class="btn green-haze btn-outline">
                    <span class="fa fa-file-excel-o"></span>
                </a>
                <a href="{{ route('admin.inventory.pdf', $inventory) }}" class="btn red-mint btn-outline">
                    <span class="fa fa-file-pdf-o"></span>
                </a>
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="portlet-body">
            <div class="table-scrollable table-scrollable-borderless">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <td width="30%">Product Reference</td>
                        <td width="30%">Product Designation</td>
                        <td width="15%">Quantity In System</td>
                        <td width="15%">Quantity in Warehouse</td>
                        <td width="10%">Difference</td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($inventory->items as $product)
                        <tr>
                            <td width="30%">
                                <span class="bold">{{ $product->reference }}</span>
                            </td>
                            <td width="30%">
                                <span class="bold">{{ $product->designation }}</span>
                            </td>
                            <td width="15%">
                                {{ abs($product->pivot->system_qty) }} {{ $product->getUnit() }}
                            </td>
                            <td width="15%">
                                {{ abs($product->pivot->qty) }} {{ $product->getUnit() }}
                            </td>
                            <td width="10%">
                                {{ abs($product->pivot->system_qty - $product->pivot->qty) }} {{ $product->getUnit() }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div><!-- /.box-body -->
    </div><!--box-->
@stop