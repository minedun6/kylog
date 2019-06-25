<div class="row">
    <!--/span-->
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label">Product</label>
            <select name="product" id="product" class="form-control select2" data-placeholder="Select a Product ...">
                <option value=""></option>
                @foreach($products as $key => $product)
                    <option value="{{ $product->id }}">{{ $product->designation }} - {{ $product->supplier_reference }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <!--/span-->
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label">Reception</label>
            <select name="reception_reference" id="reception_reference" class="form-control select2" data-placeholder="Select Reception Reference ...">
                <option value=""></option>
                @foreach($receptionReferences as $key => $receptionReference)
                    <option value="{{ $receptionReference }}">{{ $receptionReference }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <!--/span-->
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label">Status</label>
            <select name="reception_status" id="reception_status" class="form-control select2" data-placeholder="Select Reception Status ...">
                <option value=""></option>
                @foreach(config('kylogger.reception_states') as $key => $status)
                    <option value="{{ $key + 1 }}">{{ $status['text'] }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <!--/span-->
</div>
<!--/row-->
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label">Reception Date</label>
            <div id="reception_date" data-display-range="0"
                 class="form-control pull-right tooltips btn green date-range">
                <i class="icon-calendar"></i>&nbsp;
                <span class="thin uppercase"></span>&nbsp;
                <i class="fa fa-angle-down"></i>
            </div>
        </div>
    </div>
    <!--/span-->
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label">Supplier</label>
            <select name="supplier" id="supplier" class="form-control select2" data-placeholder="Select a Supplier ...">
                <option value=""></option>
                @foreach($suppliers as $key => $supplier)
                    <option value="{{ $key }}">{{ $supplier }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <!--/span-->
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label">Client</label>
            <select name="client" id="client" class="form-control select2" data-placeholder="Select a Client ...">
                <option value=""></option>
                @foreach($clients as $key => $client)
                    <option value="{{ $key }}">{{ $client }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <!--/span-->
</div>
<div class="row">
    <div class="col-lg-offset-9 col-md-offset-9 col-sm-offset-8 col-xs-offset-2">
        <button class="btn default btn-md" id="detailed_stock_btn"><i class="fa fa-filter"></i> Search</button>
        <button class="btn red btn-outline btn-md" id="detailed_stock_reset"><i class="fa fa-times-circle-o"></i> Reset Filters</button>
    </div>
</div>
<br><br><br>
