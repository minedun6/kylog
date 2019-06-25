<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {{ Form::label('supplier', 'Supplier') }}
            {{ Form::select('supplier_id', $suppliers->pluck('name', 'id'), null ,['class' => 'form-control select2']) }}
        </div>
        <div class="form-group">
            {{ Form::label('reference', 'Reference') }}
            {{ Form::text('reference', null, ['class' => 'form-control', 'placeholder' => 'Type a Reference ...']) }}
        </div>
        <div class="form-group">
            {{ Form::label('supplier_reference', 'Supplier Reference') }}
            {{ Form::text('supplier_reference', null, ['class' => 'form-control', 'placeholder' => 'Type a Supplier Reference ...']) }}
        </div>
        <div class="form-group">
            {{ Form::label('sap', 'Code SAP') }}
            {{ Form::text('sap', null, ['class' => 'form-control', 'placeholder' => 'Type a Code SAP ...']) }}
        </div>
        <div class="form-group">
            {{ Form::label('designation', 'Designation') }}
            {{ Form::text('designation', null, ['class' => 'form-control', 'placeholder' => 'Type a Designation ...']) }}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {{ Form::label('value', 'Value') }}
            {{ Form::text('value', null, ['class' => 'form-control', 'placeholder' => 'Type a Value ...']) }}
        </div>
        <div class="form-group">
            {{ Form::label('net_weight', 'Net Weight') }}
            {{ Form::number('net_weight', null, ['class' => 'form-control', 'min' => '1', 'max' => '1000', 'value' => '1']) }}
        </div>
        <div class="form-group">
            {{ Form::label('brut_weight', 'Gross Weight') }}
            {{ Form::number('brut_weight', null, ['class' => 'form-control', 'min' => '1', 'max' => '1000', 'value' => '1']) }}
        </div>
        <div class="form-group">
            {{ Form::label('piece', 'Count by Pieces ?') }}
            <label class="mt-checkbox mt-checkbox-outline">
                <input type="checkbox" name="piece" value="1" v-model="piece"/>
                <span></span>
            </label>
        </div>
        <transition name="fadeInUp">
            <div class="form-group" v-if="!piece">
                {{ Form::label('unit', 'Unit of Measure') }}
                {{ Form::text('unit', null, ['class' => 'form-control', 'placeholder' => 'Type a Unit of Measure ...']) }}
            </div>
        </transition>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        {{-- Form Input Repeater --}}
        <div class="mt-repeater">
            <h3 class="mt-repeater-title">Custom Attributes</h3>
            <div data-repeater-list="custom_attributes">
                @if(isset($product) && !is_null($product->getCustomAttributes()))
                    @foreach($product->getCustomAttributes() as $k => $v)
                        <div data-repeater-item class="mt-repeater-item">
                            <!-- jQuery Repeater Container -->
                            <div class="row mt-repeater-row">
                                <div class="col-md-6">
                                    <div class="mt-repeater-input">
                                        <label class="control-label">Key</label>
                                        <br/>
                                        <input type="text" name="key" class="form-control" value="{{ $k }}"/>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="mt-repeater-input">
                                        <label class="control-label">Value</label>
                                        <br/>
                                        <input type="text" name="value" class="form-control" value="{{ $v }}"/>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="mt-repeater-input">
                                        <a href="javascript:;" data-repeater-delete
                                           class="btn btn-danger mt-repeater-delete">
                                            <i class="fa fa-close"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div data-repeater-item class="mt-repeater-item">
                        <!-- jQuery Repeater Container -->
                        <div class="row mt-repeater-row">
                            <div class="col-md-6">
                                <div class="mt-repeater-input">
                                    <label class="control-label">Key</label>
                                    <br/>
                                    <input type="text" name="key" class="form-control" placeholder="Custom Key ..."/>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="mt-repeater-input">
                                    <label class="control-label">Value</label>
                                    <br/>
                                    <input type="text" name="value" class="form-control"
                                           placeholder="Custom Value ..."/>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="mt-repeater-input">
                                    <a href="javascript:;" data-repeater-delete
                                       class="btn btn-danger mt-repeater-delete">
                                        <i class="fa fa-close"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <a href="javascript:;" data-repeater-create class="btn btn-success mt-repeater-add">
                <i class="fa fa-plus"></i> Add Custom Attribute
            </a>
            {{-- Form Input Repeater --}}
        </div>
    </div>
</div>
