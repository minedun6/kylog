<div id="client-inventory-spinner" style="height: 643px" class="hidden">
    <img src="{{ asset('assets/global/img/balls.gif') }}" alt="" class="horizontal_center loading"/>
</div>
<div class="row" id="client-edit-wrapper">
    <div class="col-md-12">
        <div class="portlet light" style="box-shadow: none">
            <div class="portlet-body form">
                {!! Form::open(['route' => ['admin.inventory.update', $inventory], 'role' => 'form', 'method' => 'PUT']) !!}
                <div class="form-body">
                    <div class="row">
                        <div class="table-scrollable table-scrollable-borderless">
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <td width="30%">Product Reference</td>
                                    <td width="30%">Product Designation</td>
                                    <td width="20%">Quantity In System</td>
                                    <td width="20%">Quantity in Warehouse</td>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($products as $product)
                                    <tr>
                                        <td width="30%">
                                            <span class="bold">{{ $product->reference }}</span>
                                        </td>
                                        <td width="30%">
                                            <span class="bold">{{ $product->designation }}</span>
                                        </td>
                                        <td width="20%">
                                            {{ $product->pivot->system_qty }}
                                            {{ ($product->piece ? "Pieces" : $product->unit) }}
                                        </td>
                                        <td width="20%">
                                            <input type="number" class="form-control edited"
                                                   name="qty[{{$product->id}}]"
                                                   min="0"
                                                   value="{{ $product->pivot->qty }}"
                                            />
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn green"
                            data-disable-with="<i class='fa fa-refresh fa-spin fa-fw'></i> Sauvegarde...">
                        <span class="fa fa-save"></span>
                        Save
                    </button>
                    <button type="button" class="btn default" data-dismiss="modal">Cancel</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>