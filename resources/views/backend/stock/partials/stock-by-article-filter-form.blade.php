<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label">Product Reference</label>
            <select name="product_reference" id="product_reference" class="form-control select2"
                    data-placeholder="Select Product Reference ...">
                <option value=""></option>
                @foreach($references as $key => $reference)
                    <option value="{{ $reference }}">{{ $reference }}</option>
                @endforeach
            </select>
        </div>
    </div>
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
</div>
<!--/row-->
<div class="row">
    <div class="col-lg-offset-9 col-md-offset-9 col-sm-offset-8 col-xs-offset-2">
        <button class="btn default btn-md" id="detailed_stock_btn"><i class="fa fa-filter"></i> Search</button>
        <button class="btn red btn-outline btn-md" id="detailed_stock_reset"><i class="fa fa-times-circle-o"></i> Reset
            Filters
        </button>
    </div>
</div>
<br><br><br>
