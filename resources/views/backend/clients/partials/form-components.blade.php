<div class="form-group">
    <label for="">Client Name</label>
    {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'John Doe ...']) !!}
</div>

<div class="form-group">
    <label for="">Client Address</label>
    {!! Form::text('address', null, ['class' => 'form-control', 'placeholder' => '4406 Harley Vincent Drive Warrensville Heights, OH 44128']) !!}
</div>

<div class="form-group">
    <label for="">Tax Registration Number</label>
    {!! Form::text('trn', null, ['class' => 'form-control', 'placeholder' => '4406674293']) !!}
</div>

<div class="form-group">
    <label for="">Client Customs Code</label>
    {!! Form::text('customs', null, ['class' => 'form-control', 'placeholder' => '4156045199']) !!}
</div>

<div class="form-group">
    <label for="">Comment</label>
    {!! Form::textarea('comment', null, ['class' => 'form-control', 'rows' => '3']) !!}
</div>

<div class="fileinput {{ (isset($company)) ? 'fileinput-exists' : 'fileinput-new' }}" data-provides="fileinput" data-name="logo">
    @if(isset($company))
        <input type="hidden" name="logo" value="{{ $company->logo }}">
    @endif
    <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
        <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image"/>
    </div>
    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">
        @if(isset($company))
            <img src="{{ '/uploads/' . $company->logo }}"/>
        @endif
    </div>
    <div>
    <span class="btn default btn-file">
        <span class="fileinput-new"> Select Logo </span>
        <span class="fileinput-exists"> Change </span>
        {{ Form::file('logo', null) }}
    </span>
        <a href="#" class="btn red fileinput-exists" data-dismiss="fileinput">Remove</a>
    </div>
</div>
