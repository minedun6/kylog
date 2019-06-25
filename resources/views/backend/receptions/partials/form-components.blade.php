<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="supplier">Supplier</label>
            {!! Form::select('supplier', $suppliers->pluck('name', 'id') , isset($reception) ? $reception->supplier_id : null , ['class' => 'form-control select2']) !!}
        </div>

        <div class="form-group">
            <label for="client">Client</label>
            {!! Form::select('client', $clients->pluck('name', 'id') ,isset($reception) ? $reception->client_id : null, ['class' => 'form-control select2']) !!}
        </div>

        <div class="form-group">
            <label for="po">PO</label>
            {!! Form::text('po', null, ['class' => 'form-control', 'placeholder' => 'PO']) !!}
        </div>

        <div class="form-group">
            <label for="invoice_number">Invoice Number</label>
            {!! Form::text('invoice_number', null, ['class' => 'form-control', 'placeholder' => 'INV258412154']) !!}
        </div>

        <div class="form-group">
            <label for="invoice_date">Invoice Date</label>
            <div class="input-group date date-picker">
                {!! Form::text('invoice_date', isset($reception) ? $reception->getInvoiceDate() : null, ['class' => 'form-control', 'placeholder' => 'dd-mm-yyyy']) !!}
                <span class="input-group-btn">
                    <button class="btn default" type="button">
                        <i class="fa fa-calendar"></i>
                    </button>
                </span>
            </div>
            <!-- /input-group -->
        </div>

    </div>
    <div class="col-md-6">

        <div class="form-group">
            <label for="reception_date">Reception Date</label>
            <div class="input-group date date-picker">
                {!! Form::text('reception_date', isset($reception) ? $reception->getReceptionDate() : null, ['class' => 'form-control', 'placeholder' => 'dd-mm-yyyy']) !!}
                <span class="input-group-btn">
                    <button class="btn default" type="button">
                        <i class="fa fa-calendar"></i>
                    </button>
                </span>
            </div>
        </div>

        <div class="form-group">
            <label for="planned_arrival_date">Planned Arrival Date</label>
            <div class="input-group date date-picker">
                {!! Form::text('planned_arrival_date', isset($reception) ? $reception->getPlannedArrivalDate() : null, ['class' => 'form-control', 'placeholder' => 'dd-mm-yyyy']) !!}
                <span class="input-group-btn">
                    <button class="btn default" type="button">
                        <i class="fa fa-calendar"></i>
                    </button>
                </span>
            </div>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            {!! Form::select('status', ['1' => 'In Transit', '2' => 'Received'] ,null, ['class' => 'form-control select2']) !!}
        </div>

        <div class="form-group">
            <label for="returns">Return ?</label>
            <label class="mt-checkbox mt-checkbox-outline">
                <input type="checkbox" id="returns" name="returns"
                       value="1" {{ (isset($reception) && $reception->returns) ? 'checked' : '' }} />
                <span></span>
            </label>
        </div>

        <div class="form-group">
            <label for="reservations">Reservations</label>
            {!! Form::text('reservations', null, ['class' => 'form-control', 'placeholder' => 'lorem ipsum ...']) !!}
        </div>

    </div>

</div>
