<html>
<head>
    <style>
        .page-break {
            page-break-after: always;
        }

        h3 {
            line-height: 0.8em !important;
        }
    </style>
    <link rel="stylesheet" href="{{ asset("assets/global/plugins/bootstrap/css/bootstrap.css") }}">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body>
<div class="container">
    @foreach($reception->packages as $package)
        <div class="row">
            <div class="page-header text-center">
                <h1>Package Number : {{ $package->getPackageId() }}</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6">
                <h1>SubPackages : {{ $package->getPackageNumbers() }}</h1>
            </div>
            <div class="col-xs-6">
                <h1>Pcs : {{ $package->getTotalQty() }}</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6">
                <div class="form-group">

                </div>
                <div class="form-group">

                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">

                </div>
                <div class="form-group">

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-7">
                <div class="well">
                    <address>
                        <h3>
                            <strong>Supplier : </strong>
                            {!! $reception->supplier->name !!}
                        </h3>
                        <h3>
                            <strong>Client : </strong>
                            {!! $reception->client->name !!}
                        </h3>
                        <h3>
                            <strong>Invoice Number : </strong>
                            {!! $reception->invoice_number !!}
                        </h3>
                        <h3>
                            <strong>Invoice Date : </strong>
                            {!! $reception->getInvoiceDate() !!}
                        </h3>
                        <h3>
                            <strong>Reception Date : </strong>
                            {!! $reception->getReceptionDate() !!}
                        </h3>
                    </address>
                </div>
            </div>
            <div class="col-xs-5">
                <div class="well">
                    <address>
                        <h3>
                            <strong>Package Type : </strong>
                            <span>{!! $package->getPackageType() !!}</span>
                        </h3>
                        <h3>
                            <strong>Package State : </strong>
                            <span>{!! $package->getState() !!}</span>
                        </h3>
                        <h3>
                            <strong>PO : </strong>
                            <span>{!! $reception->po !!}</span>
                        </h3>
                        <h3>
                            <strong>Batch Number : </strong>
                            <span>{!! $package->batch_number !!}</span>
                        </h3>
                    </address>
                </div>
            </div>
        </div>
        <div class="row"></div>

        <div class="row">
            <div class="col-xs-12">
                <table class="table table-striped table-hover">
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
                    @foreach($package->packageItems as $item)
                        <tr>
                            <td colspan="7">
                                {{ $item->designation }}
                            </td>
                            <td colspan="2">
                                {{ $item->pivot->po }}
                            </td>
                            <td colspan="2">
                                @if ($item->pivot->type == 1)
                                    Carton
                                @elseif($item->pivot->type == 2)
                                    Unit
                                @endif
                            </td>
                            <td colspan="2">
                                {{ $item->pivot->subpackages_number }}
                            </td>
                            <td colspan="1">
                                {{--Qty--}}
                                {{ $item->pivot->qty }}
                            </td>
                            <th colspan="1">
                                {{ $item->pivot->qty * $item->pivot->subpackages_number }}
                            </th>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="7"></td>
                        <th colspan="2"></th>
                        <th colspan="2">
                            Total
                        </th>
                        <th colspan="2">
                            {!! $package->getPackageNumbers() !!}
                        </th>
                        <th colspan="1">
                            {!! $package->getQty() !!}
                        </th>
                        <th colspan="1">
                            {!! $package->getTotalQty() !!}
                        </th>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="page-break"></div>
</div>
@endforeach
</body>
</html>
