<html>
<head>
    <style>
        .page-break {
            page-break-after: always;
        }
        table thead tr {
            page-break-inside: avoid;
        }
        table tbody tr {
            page-break-inside: avoid;
        }
    </style>
    <link rel="stylesheet" href="{{ asset("assets/global/plugins/bootstrap/css/bootstrap.css") }}">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-xs-5">
            <div class="well">
                <address class="bold">
                    Kylog Logistics <br/>
                    Zone Industrielle -2040 -Rades –Tunisie <br/>
                    M.F.: 1167633G/A/M/000 <br/>
                    R.C. : B24160942010 – C.D. : 1167633 G <br/>
                    Tél : (216) 71 449 712 / Fax: (216) 71 449 734 <br/>
                </address>
            </div>
        </div>
        <div class="col-xs-5 col-xs-offset-2" style="margin-top: 3%;">
            <img src="{{ asset('img/app/kylog.png') }}" class="img-responsive center-block"/>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-6">
            <div class="well">
                <address>
                    <h5>
                        <strong>Delivery Num : </strong>
                        {{ $delivery->delivery_number }}
                    </h5>
                    <h5>
                        <strong>Supplier(s) : </strong>
                        {!! $delivery->getSuppliers() !!}
                    </h5>
                    <h5>
                        <strong>Client : </strong>
                        {{ $delivery->client->name }}
                    </h5>
                    <h5>
                        <strong>Delivery Order Date : </strong>
                        {!! $delivery->getDeliveryOrderDate() !!}
                    </h5>
                </address>
            </div>
        </div>
        <div class="col-xs-6">
            <div class="well">
                <address>
                    <h5>
                        <strong>Destination : </strong>
                        <span>
                            {!! $delivery->getDestinationForReport() !!}
                        </span>
                    </h5>
                    <h5>
                        <strong>Delivery Outside Working Hours : </strong>
                        {!! $delivery->getDeliveryOutsideWorkingHoursForReport() !!}
                    </h5>
                    <h5>
                        <strong>Final Destination : </strong>
                        {!! $delivery->final_destination ?? '<span class="label label-danger">' . config('kylogger.value_not_defined') . '</span>' !!}
                    </h5>
                    <h5>
                        <strong>PO : </strong>
                        {!! $delivery->po ?? '<span class="label label-danger">' . config('kylogger.value_not_defined') . '</span>' !!}
                    </h5>
                </address>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th width="20%">Product Reference</th>
                    <th width="45%">Product</th>
                    <th width="10%"></th>
                    <th width="10%">Packages</th>
                    <th width="15%">Qty</th>
                </tr>
                </thead>
                <tbody>
                @foreach($delivery->packageItems->unique('product_id') as $item)
                    <tr>
                        <td width="20%">{{ $item->product->reference }}</td>
                        <td width="45%">{{ $item->product->designation }} - {{ $item->product->supplier_reference }}</td>
                        <td width="10%"></td>
                        <td width="10%">{{ $delivery->packageItems()->where('product_id', $item->product->id)->get()->count() }} {{ $item->package->getPackageType() }}</td>
                        <td width="15%">{{ $delivery->packageItems()->where('product_id', $item->product->id)->get()->sum(function($i) {return $i->pivot->qty;}) }} {{ $item->product->getProductType() }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td width="20%"></td>
                    <td width="45%">
                        <span class="bold">
                        </span>
                    </td>
                    <td width="10%">
                        <span class="bold">
                            Total
                        </span>
                    </td>
                    <td width="10%">
                        <span class="bold">
                            {{ $delivery->packageItems->count() }}
                        </span>
                    </td>
                    <td width="15%">
                        <span class="bold">
                            {{ $delivery->packageItems->sum(function ($i) {return $i->pivot->qty;}) }}
                        </span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-5">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th class="bold">Entrepôt Kylog</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="case">
                        <br><br><br>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="col-xs-5 col-xs-offset-2">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th class="bold">Entrepôt Acquéreur </th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="case">
                        <br><br><br>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
