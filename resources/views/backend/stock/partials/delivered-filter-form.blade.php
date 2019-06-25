<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="delivery_number" class="control-label">Delivery Number</label>
            <select name="delivery_number" id="delivery_number" class="form-control select2"
                    data-placeholder="Select Delivery Number ..." data-tags="true" multiple>
                @foreach($deliveriesNumbers as $key => $deliveryNumber)
                    <option value="{{ $key }}">{{ $deliveryNumber }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <!--/span-->
    <div class="col-md-4">
        <div class="form-group">
            <label for="product" class="control-label">Product</label>
            <select name="product" id="product" class="form-control select2" data-placeholder="Select a Product ...">
                <option value=""></option>
                @foreach($products as $key => $product)
                    <option value="{{ $product->id }}">{{ $product->designation }} - {{ $product->supplier_reference }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="supplier" class="control-label">Supplier</label>
            <select name="supplier" id="supplier" class="form-control select2" data-placeholder="Select a Supplier ..."
                    data-tags="true" multiple>
                @foreach($suppliers as $key => $supplier)
                    <option value="{{ $key }}">{{ $supplier }}</option>
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
            <label for="client" class="control-label">Client</label>
            <select name="client" id="client" class="form-control select2"
                    data-placeholder="Select a Client ..."
                    data-tags="true" multiple>
                @foreach($clients as $key => $client)
                    <option value="{{ $key }}">{{ $client }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <!--/span-->
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label">Reception Date</label>
            <div id="reception_date" data-display-range="0"
                 class="form-control pull-right tooltips btn green" id="reception_date">
                <i class="icon-calendar"></i>&nbsp;
                <span class="thin uppercase"></span>&nbsp;
                <i class="fa fa-angle-down"></i>
            </div>
        </div>
    </div>
    <!--/span-->
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label">Delivery Order Date</label>
            <div id="delivery_order_date" data-display-range="0"
                 class="form-control pull-right tooltips btn green" id="delivery_order_date">
                <i class="icon-calendar"></i>&nbsp;
                <span class="thin uppercase"></span>&nbsp;
                <i class="fa fa-angle-down"></i>
            </div>
        </div>
    </div>
    <!--/span-->
</div>
<!--/row-->
<div class="row">
    <div class="col-lg-offset-9 col-md-offset-9 col-sm-offset-8 col-xs-offset-2">
        <button class="btn default btn-md" id="delivered_stock_btn"><i class="fa fa-filter"></i> Search</button>
        <button class="btn red btn-outline btn-md" id="delivered_stock_reset"><i class="fa fa-times-circle-o"></i> Reset
            Filters
        </button>
    </div>
</div>
<!--/row-->
<br><br><br>