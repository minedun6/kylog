<div id="reception-inventory-spinner" style="height: 643px" class="hidden">
    <img src="{{ asset('assets/global/img/balls.gif') }}" alt="" class="horizontal_center loading"/>
</div>
<div class="row" id="reception-edit-wrapper">
    <div class="col-md-12">
        <div class="portlet light" style="box-shadow: none">
            <table class="table dataTable no-footer table-bordered table-condensed" id="deliveredStockForReceptionTable"
                   style="width: 100%">
                <thead>
                <tr>
                    <th>Delivery Number</th>
                    <th>Package </th>
                    <th>Reference</th>
                    <th>Designation</th>
                    <th>Delivery_order_date</th>
                    <th>Reception_date</th>
                    <th>Supplier</th>
                    <th>Client</th>
                    <th>Delivered Qty</th>
                </tr>
                </thead>
                <tbody>
                @foreach($stock as $stockItem)
                    <tr>
                        <td>{{ $stockItem->delivery_number }}</td>
                        <td>#{{ $stockItem->package_id }}</td>
                        <td>{{ $stockItem->reference }}</td>
                        <td>{{ $stockItem->designation }}</td>
                        <td>{{ \Carbon\Carbon::parse($stockItem->delivery_order_date)->format('Y-m-d') }}</td>
                        <td>{{ $stockItem->reception_date }}</td>
                        <td>
                            <span class="label label-primary">{{ $stockItem->supplier_name }}</span>
                        </td>
                        <td>
                            <span class="label label-primary">{{ $stockItem->client_name }}</span>
                        </td>
                        <td>
                            {{ $stockItem->qty . ' ' . ($stockItem->piece ? "Pieces" : $stockItem->unit) }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>