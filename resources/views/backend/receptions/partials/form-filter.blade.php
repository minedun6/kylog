<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label">Reception Reference</label>
            <select name="reception_reference" id="reception_reference" class="form-control select2"
                    data-placeholder="Select a Reception Reference ...">
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
            <label class="control-label">Supplier</label>
            <select name="reception_supplier" id="reception_supplier" class="form-control select2"
                    data-placeholder="Select a Supplier ..."
                    data-tags="true" multiple>
                @foreach($suppliers as $key => $supplier)
                    <option value="{{ $key }}">{{ $supplier }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <!--/span-->
    @if (!isset($company))
        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label">Client</label>
                <select name="reception_client" id="reception_client" class="form-control select2"
                        data-placeholder="Select a Client ..."
                        data-tags="true" multiple>
                    @foreach($clients as $key => $client)
                        <option value="{{ $key }}">{{ $client }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @endif
    <!--/span-->
</div>
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
            <label class="control-label">Container Number</label>
            <select name="container_number" id="container_number" class="form-control select2"
                    data-placeholder="Select a Container Number ..."
                    data-tags="true" multiple>
                @foreach($containerNumbers as $key => $containerNumber)
                    @if(!empty($containerNumber))
                        <option value="{{ $containerNumber }}">{{ $containerNumber }}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>
    <!--/span-->
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label">Reception Status</label>
            <select name="reception_status" id="reception_status" class="form-control select2"
                    data-placeholder="Select a Reception Status ...">
                <option value=""></option>
                @foreach($receptionStatuses as $key => $receptionStatus)
                    <option value="{{ $key }}">{{ $receptionStatus['text'] }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <!--/span-->
</div>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="product" class="control-label">Product</label>
            <select name="product" id="product" class="form-control select2"
                    data-placeholder="Select a Product ...">
                <option value=""></option>
                @foreach($products as $key => $product)
                    <option value="{{ $key }}">{{ $product }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-offset-9 col-md-offset-9 col-sm-offset-8 col-xs-offset-2">
        <button class="btn default btn-md" id="reception_search"><i class="fa fa-filter"></i> Search</button>
        <button class="btn red btn-outline btn-md" id="reception_reset"><i class="fa fa-times-circle-o"></i> Reset
            Filters
        </button>
    </div>
</div>
<br><br><br>
