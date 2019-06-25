<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="supplier">Supplier</label>
            {!! Form::select('supplier[]', $suppliers->pluck('name', 'id') , isset($delivery) ? $delivery->suppliers->pluck('id')->toArray() : null , ['class' => 'form-control select2', 'data-placeholder' => 'select a supplier ...', 'data-tags' => true, 'multiple' => true]) !!}
        </div>

        <div class="form-group">
            <label for="client">Client</label>
            {!! Form::select('client', $clients->pluck('name', 'id') ,isset($delivery) ? $delivery->client_id : null, ['class' => 'form-control select2', 'data-placeholder' => 'select a client ...']) !!}
        </div>

        <div class="form-group">
            <label for="po">Delivery Order Date</label>
            <div class="input-group date date-picker">
                {!! Form::text('delivery_order_date', isset($delivery) ? $delivery->getDeliveryOrderDate() : null, ['class' => 'form-control', 'placeholder' => 'dd-mm-yyyy']) !!}
                <span class="input-group-btn">
                    <button class="btn default" type="button">
                        <i class="fa fa-calendar"></i>
                    </button>
                </span>
            </div>
        </div>

        <div class="form-group">
            <label for="invoice_number">Delivery Preparation Date</label>
            <div class="input-group date date-picker">
                {!! Form::text('delivery_preparation_date', isset($delivery) ? $delivery->getPreparationOrderDate() : null, ['class' => 'form-control', 'placeholder' => 'dd-mm-yyyy']) !!}
                <span class="input-group-btn">
                    <button class="btn default" type="button">
                        <i class="fa fa-calendar"></i>
                    </button>
                </span>
            </div>
        </div>

    </div>
    <div class="col-md-6">

        <div class="form-group">
            <label for="final_destination">Final Destination</label>
            <div class="input-icon">
                <i class="icon-directions"></i>
                {!! Form::text('final_destination', null, ['class' => 'form-control', 'placeholder' => 'Choose a destination ...']) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="po">PO</label>
            {!! Form::text('po', null, ['class' => 'form-control', 'placeholder' => 'PO']) !!}
        </div>

        <div class="form-group">
            <label for="planned_arrival_date">BL Date</label>
            <div class="input-group date date-picker">
                {!! Form::text('bl_date', isset($delivery) ? $delivery->getBLDate() : null, ['class' => 'form-control', 'placeholder' => 'dd-mm-yyyy']) !!}
                <span class="input-group-btn">
                    <button class="btn default" type="button">
                        <i class="fa fa-calendar"></i>
                    </button>
                </span>
            </div>
        </div>

        <div class="form-group">
            <label for="status">Destination</label>
            <select name="destination" id="destination" class="form-control select2"
                    data-placeholder="Select a Destination ...">
                <option value=""></option>
                @foreach(config('kylogger.destinations') as $key => $destination)
                    @if (isset($delivery))
                        <option value="{{ $key }}" {{ $delivery->destination == $key ? "selected" : "" }}>{{ $destination['text'] }}</option>
                    @else
                        <option value="{{ $key }}">{{ $destination['text'] }}</option>
                    @endif
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="delivery_outside_working_hours">Delivery Outside Working Hours ?</label>
            <label class="mt-checkbox mt-checkbox-outline">
                <input type="checkbox" id="delivery_outside_working_hours" name="delivery_outside_working_hours"
                       value="1" {{ isset($delivery) && $delivery->delivery_outside_working_hours ? 'checked' : '' }}/>
                <span></span>
            </label>
        </div>
    </div>
    @if(isset($delivery))
        <input type="hidden" name="delivery_number" value="{{ $delivery->delivery_number }}">
    @endif
</div>
