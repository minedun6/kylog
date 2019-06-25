<html>
<head>
    <style>
        thead {
            display: table-header-group;
        }
        tfoot {
            display: table-row-group;
        }
        tr {
            page-break-inside: avoid;
        }
    </style>
    <link rel="stylesheet" href="{{ asset("assets/global/plugins/bootstrap/css/bootstrap.css") }}">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body>
<div class="container-fluid">
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
        <div class="col-xs-6 col-xs-offset-3">
            <h3><strong>Company :</strong> {{ $company->name }}</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                <tr>
                    <th width="25%">Product Reference</th>
                    <th width="30%">Product Designation</th>
                    <th width="20%">Quantity In System</th>
                    <th width="25%">Quantity in Warehouse</th>
                </tr>
                </thead>
                <tbody>
                @foreach($products as $product)
                    <tr>
                        <td width="25%">
                            <span class="bold">{{ $product->product_reference }}</span>
                        </td>
                        <td width="30%">
                            <span class="bold">{{ $product->designation }}</span>
                        </td>
                        <td width="20%">
                            {{ abs($product->quantities - $product->delivery_qty) }}
                            {{ ($product->piece ? "Pieces" : $product->unit) }}
                        </td>
                        <td width="25%">
                            <span></span>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <br>
</div>
</body>
</html>
