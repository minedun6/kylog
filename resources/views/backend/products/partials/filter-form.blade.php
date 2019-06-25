<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label">Product Reference</label>
            <select name="reference" id="reference" class="form-control select2" data-placeholder="Select a Client ...">
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
                    <option value="{{ $key }}">{{ $product }}</option>
                @endforeach
            </select>
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
</div>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label">Supplier Reference</label>
            <select name="supplier_reference" id="supplier_reference" class="form-control select2" data-placeholder="Select a Supplier Reference ...">
                <option value=""></option>
                @foreach($supplierReferences as $key => $supplierReference)
                    <option value="{{ $supplierReference }}">{{ $supplierReference }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <!--/span-->
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label">Value</label>
            <select name="value" id="value" class="form-control select2" data-placeholder="Select a Value Interval ...">
                <option value=""></option>
                @foreach(config('kylogger.values_intervals') as $key => $interval)
                    <option value="{{ $key }}">{{ $interval }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <!--/span-->
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label">Net Weight</label>
            <select name="net_weight" id="net_weight" class="form-control select2" data-placeholder="Select a Client ...">
                <option value=""></option>
                @foreach(config('kylogger.net_weight_intervals') as $key => $interval)
                    <option value="{{ $key }}">{{ $interval }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <!--/span-->
</div>
<div class="row">
    <div class="col-md-4 col-md-offset-8 col-xs-6 col-xs-offset-6">
        <button class="btn default btn-md" id="product_search"><i class="fa fa-filter"></i> Search</button>
        <button class="btn red btn-outline btn-md" id="product_reset"><i class="fa fa-times-circle-o"></i> Reset Filters</button>
    </div>
</div>
<br><br><br>

