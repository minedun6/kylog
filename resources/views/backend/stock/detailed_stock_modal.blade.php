<div id="client-inventory-spinner" style="height: 643px" class="hidden">
    <img src="{{ asset('assets/global/img/balls.gif') }}" alt="" class="horizontal_center loading"/>
</div>
<div class="row" id="client-edit-wrapper">
    <div class="col-md-12">
        <div class="portlet light" style="box-shadow: none">
            <div class="portlet-body form">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Supplier</label>
                                <select name="supplier" id="supplier" class="form-control select2"
                                        data-placeholder="Select a Supplier ...">
                                    <option value=""></option>
                                    @foreach($suppliers as $key => $supplier)
                                        <option value="{{ $key }}">{{ $supplier }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Filter Date</label>
                                <div class="input-group date date-picker">
                                    {!! Form::text('filter_date', null, ['class' => 'form-control', 'placeholder' => 'dd-mm-yyyy', 'id' => 'filter_date']) !!}
                                    <span class="input-group-btn">
                                            <button class="btn default" type="button">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        </span>
                                </div>
                            </div>
                        </div>
                        <!--/span-->
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn green"
                                    id="filter_btn"
                                    data-disable-with="<i class='fa fa-refresh fa-spin fa-fw'></i> Filtering ...">
                                <span class="fa fa-filter"></span>
                                Filter
                            </button>
                            <button id="filter_reset" class="btn default" data-dismiss="modal">Cancel
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table dataTable no-footer table-bordered table-condensed"
                                   id="stock_by_article_filtred_table" style="width: 100%">
                                <thead>
                                <tr>
                                    <th width="25%">Product Reference</th>
                                    <th width="25%">Designation</th>
                                    <th width="25%">Supplier</th>
                                    <th width="25%">Remaining Qty</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let select2 = $(".select2");
    jQuery().select2 && select2.select2({
        placeholder: $(this).data('placeholder'),
        theme: "bootstrap",
        allowClear: true,
        width: null,
    });

    $(".date-picker").datepicker({
        language: 'fr',
        autoclose: true,
        todayHighlight: true,
        format: 'dd-mm-yyyy',
        endDate: '0d'
    })

    $('#stock_by_article_filtred_table').DataTable({
        pagingType: 'bootstrap_extended',
        responsive: true,
        dom: 'Bfrtip',
        processing: true,
        serverSide: true,
        order: [[0, 'desc']],
        buttons: [],
        ajax: '{{ route("admin.stock.detailed.modal") }}',
        columns: [
            {data: 'product_reference', name: 'product_reference'},
            {data: 'designation', name: 'designation'},
            {data: 'supplier_name', name: 'supplier_name'},
            {data: 'quantity', name: 'quantity'},
        ],
    });

</script>

<script>
    $(document).ready(function () {
        let dataTable = $('#stock_by_article_filtred_table');
        let supplier = $('#supplier');
        let filterDate = $('#filter_date');

        $('#filter_btn').on('click', function (e) {
            e.preventDefault();
            dataTable.DataTable().draw();
        });

        $('#filter_reset').on('click', function (e) {
            e.preventDefault();
            supplier.val('').trigger('change');
            filterDate.val('');
            dataTable.DataTable().draw();
        });

        dataTable.on('preXhr.dt', function (e, settings, data) {
            data.filterDate = filterDate.val();
            data.supplier = supplier.val();
        });

    });
</script>
