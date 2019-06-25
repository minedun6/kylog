<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="form-group">
            <label for="type">Type</label>
            <select name="type" class="form-control select2" v-model="type">
                <option value="1">Container</option>
                <option value="2">Truck</option>
                <option value="3">Other</option>
            </select>
        </div>
    </div>
</div>

<transition name="fadeInUp">
    <div class="row" v-if="type == 1">
        <div class="col-md-10 col-md-offset-1">
            <div class="form-group">
                <label for="declaration_type">Declaration Type</label>
                {!! Form::text('declaration_type', null, ['class' => 'form-control', 'placeholder' => 'Declaration Type ...' ]) !!}
            </div>
            <div class="form-group">
                <label for="declaration_number">Declaration Number</label>
                {!! Form::text('declaration_number', null, ['class' => 'form-control', 'placeholder' => 'Declaration Number']) !!}
            </div>
            <div class="form-group">
                <label for="declaration_date">Declaration Date</label>
                <div class="input-group date date-picker">
                    {!! Form::text('declaration_date', (isset($reception) && !is_null($reception->declaration_date)) ? $reception->getDeclarationDate() : null , ['class' => 'form-control', 'placeholder' => 'Declaration Number', 'v-datepicker' => 'this']) !!}
                    <span class="input-group-btn">
                    <button class="btn default" type="button">
                        <i class="fa fa-calendar"></i>
                    </button>
                </span>
                </div>
            </div>
            <div class="form-group">
                <label for="container_number">Container Number</label>
                {!! Form::text('container_number', null, ['class' => 'form-control', 'placeholder' => 'Container Number ...']) !!}
            </div>
        </div>
    </div>
</transition>

<transition name="fadeInUp">
    <div class="row" v-if="type == 2">
        <div class="col-md-10 col-md-offset-1">
            <div class="form-group">
                <label for="driver">Driver</label>
                {!! Form::text('driver', null, ['class' => 'form-control', 'placeholder' => 'John Doe ...']) !!}
            </div>
            <div class="form-group">
                <label for="registration_number">Plate</label>
                {!! Form::text('registration_number', null, ['class' => 'form-control', 'placeholder' => 'XXXTNXXXX']) !!}
            </div>
        </div>
    </div>
</transition>

<transition name="fadeInUp">
    <div class="row" v-if="type == 3">
        <div class="col-md-10 col-md-offset-1">
            <div class="form-group">
                <label for="other">Other</label>
                {!! Form::text('other', null, ['class' => 'form-control', 'placeholder' => 'Other ...']) !!}
            </div>
        </div>
    </div>
</transition>

