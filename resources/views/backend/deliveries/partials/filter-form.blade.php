<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="delivery_number" class="control-label">Delivery Number</label>
            <select name="delivery_number" id="delivery_number" class="form-control select2"
                    data-placeholder="Select a Delivery Number ..."
                    data-tags="true" multiple>
                @foreach($deliveriesNumbers as $key => $deliveryNumber)
                    <option value="{{ $key }}">{{ $deliveryNumber }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <!--/span-->
    <div class="col-md-4">
        <div class="form-group">
            <label for="supplier" class="control-label">Supplier</label>
            <select name="supplier" id="supplier" class="form-control select2"
                    data-placeholder="Select a Supplier ..."
                    data-tags="true" multiple>
                @foreach($suppliers as $key => $supplier)
                    <option value="{{ $key }}">{{ $supplier }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <!--/span-->
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
</div>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label">Delivery Order Date</label>
            <div id="delivery_order_date" data-display-range="0"
                 class="form-control pull-right tooltips btn default date-range">
                <i class="icon-calendar"></i>&nbsp;
                <span class="thin uppercase"></span>&nbsp;
                <i class="fa fa-angle-down"></i>
            </div>
        </div>
    </div>
    <!--/span-->
    <div class="col-md-4">
        <div class="form-group">
            <label for="destination" class="control-label">Destination</label>
            <select name="destination" id="destination" class="form-control select2"
                    data-placeholder="Select a Destination ...">
                <option value=""></option>
                @foreach($destinations as $key => $destination)
                <option value="{{ $key }}">{{ $destination['text'] }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <!--/span-->
</div>
<div class="row">
    <div class="col-lg-offset-9 col-md-offset-9 col-sm-offset-8 col-xs-offset-2">
        <button class="btn default btn-md" id="delivery_search_btn"><i class="fa fa-filter"></i> Search</button>
        <button class="btn red btn-outline btn-md" id="delivery_reset_btn"><i class="fa fa-times-circle-o"></i> Reset
            Filters
        </button>
    </div>
</div>
<br><br><br>
