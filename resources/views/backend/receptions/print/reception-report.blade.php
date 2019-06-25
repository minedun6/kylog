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
        <div class="text-center">
            <h2>Receipt : <strong>{{ $reception->reference }}</strong></h2>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-4 col-xs-offset-1">
            <h4>Supplier : <strong>{{ $reception->supplier->name }}</strong></h4>
        </div>
        <div class="col-xs-4 col-xs-offset-3">
            <h4>Reception Date : <strong>{!! $reception->getReceptionDate() !!}</strong></h4>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                <tr>
                    <th colspan="7">Product Designation</th>
                    <th colspan="2">Supplier Reference</th>
                    {{--<th colspan="2">Delivery</th>--}}
                    <th colspan="2">Packages Qty</th>
                    <th colspan="1">SubPackages Qty</th>
                    <th colspan="2">Items Qty</th>
                </tr>
                </thead>
                <tbody>
                @foreach($receptionItems as $k => $product)
                    <tr>
                        <td colspan="7">
                            {{ $product->product->designation }}
                        </td>
                        <td colspan="2">
                            {{ $product->product->supplier_reference }}
                        </td>
                        <td colspan="2">
                            {{ $reception->countPackagesContainingProduct($product->package, $product->product) }}
                        </td>
                        <td colspan="1">
                            {{ $product->subpackages_total }}
                        </td>
                        <td colspan="2">
                            {{ $product->pcs_total }}
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="7"></td>
                    <td colspan="2">
                        <span>
                            <strong>Total</strong>
                        </span>
                    </td>
                    <td colspan="2">
                        <span>
                            <strong>{{ $receptionItems->sum('subpackages_total') }}</strong>
                        </span>
                    </td>
                    <td colspan="1">
                        <span>
                            <strong>{{ $receptionItems->sum('qty_total') }}</strong>
                        </span>
                    </td>
                    <td colspan="2">
                        <span>
                            <strong> {{ $receptionItems->sum('pcs_total') }} </strong>
                        </span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-xs-4 col-xs-offset-4">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th class="bold">Reserves on Reception</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="case">{{ $reception->reserves }}
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
