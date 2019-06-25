<div id="reception-inventory-spinner" style="height: 643px" class="hidden">
    <img src="{{ asset('assets/global/img/balls.gif') }}" alt="" class="horizontal_center loading"/>
</div>
<div class="row" id="reception-edit-wrapper">
    <div class="col-md-12">
        <div class="portlet light" style="box-shadow: none">
            <table class="table dataTable no-footer table-bordered table-condensed" id="deliveriesForReceptionTable"
                   style="width: 100%">
                <thead>
                <tr>
                    <th>Delivery Number</th>
                    <th>Supplier</th>
                    <th>Client</th>
                    <th>Delivery Order Date</th>
                    <th>Bl Date</th>
                    <th>Destination</th>
                    <th>Final Destination</th>
                    <th>PO</th>
                </tr>
                </thead>
                <tbody>
                @foreach($deliveries as $delivery)
                    <tr>
                        <td>{!! $delivery->getDeliveryLink() !!}</td>
                        <td>{!! $delivery->getSuppliers() !!}</td>
                        <td>{!! $delivery->getClient() !!}</td>
                        <td>{!! $delivery->getDeliveryOrderDate() !!}</td>
                        <td>{!! $delivery->getBLDate() !!}</td>
                        <td>{!! $delivery->getDestination() !!}</td>
                        <td>{!! $delivery->getFinalDestination() !!}</td>
                        <td>{!! $delivery->getPo() !!}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>