<div class="row">
    <div class="col-md-10">
        <div class="form-group {{ $errors->has('subject') ? 'has-error' : '' }}">
            <label for="subject" class="col-sm-3 control-label">
                <b> Subject </b>
            </label>
            <div class="col-md-9">
                {!! Form::text('subject', old('subject'), ['class' => 'form-control', 'id' => 'subject']) !!}
                {!! $errors->first('subject', '<span class="help-block">:message</span>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-10">
        <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
            <label for="description" class="col-md-3 control-label">
                <b> Description </b>
            </label>
            <div class="col-md-9">
                {!! Form::textarea('description', old('description'), ['class' => 'form-control editor', 'rows' => 10, 'id' => 'description']) !!}
                {!! $errors->first('description', '<span class="help-block">:message</span>') !!}
            </div>
        </div>
    </div>


    <div class="col-md-10">
        <div class="form-group {{ $errors->has('priority_id') ? 'has-error' : '' }}">
            <label for="priority" class="col-md-3 control-label">
                <b>Priority</b>
            </label>
            <div class="col-md-9">
                <select class="form-control select2" name="priority_id" id="priority"
                        data-placeholder="Select Priority ...">
                    <option value=""></option>
                    @foreach($priorities as $priority)
                        <option value="{{ $priority->id }}">{{ $priority->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="col-md-10">
            <div class="form-group {{ $errors->has('category_id') ? 'has-error' : '' }}">
                <label for="category" class="col-md-3 control-label">
                    <b>Category</b>
                </label>
                <div class="col-md-9">
                    <select class="form-control select2" name="category_id" id="category"
                            data-placeholder="Select Category ...">
                        <option value=""></option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

</div>
