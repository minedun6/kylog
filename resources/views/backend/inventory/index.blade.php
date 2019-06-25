@extends('backend.layouts.app')

@section ('title')
    {{ app_name() }} | Inventory
@endsection

@section('after-styles')
@endsection

@section('page-header')
    <h1>
        Inventory Management
    </h1>
@endsection

@section('content')
    <div class="portlet light ">
        <div class="portlet-title">
            <div class="caption">Inventories</div>

            <div class="actions">
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="portlet-body">
            <div class="modal fade in" id="editInventoryModal" tabindex="-1" role="basic" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <h4 class="modal-title">Edit Inventory Items</h4>
                        </div>
                        <div class="modal-body">

                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            {!! $dataTable->table() !!}
        </div><!-- /.box-body -->
    </div><!--box-->
@endsection

@section('after-scripts')
    <script>
        $.fn.dataTable.ext.buttons.createInventory = {
            text: '<i class="fa fa-plus"></i> Add Inventory',
            className: 'btn xs default',
            action: function (e, dt, node, config) {
                window.location = "{{ route('admin.inventory.create') }}";
            }
        };
    </script>
    {!! $dataTable->scripts() !!}
    <script>
        let modalEditInventory = $('#editInventoryModal').find(".modal-body");
        console.log(modalEditInventory);
        let InventoryEditModalHtml = "";
        modalEditInventory.html('');
        $('#editInventoryModal').on("shown.bs.modal", function (event) {
            window.dispatchEvent(new Event('resize'));
            let inventoryId = $(event.relatedTarget).attr('data-id') ? $(event.relatedTarget).attr('data-id') : $('#editInventoryBtn').attr('data-id');
            modalEditInventory.html('<div style="height: 643px"><img src="{{ asset('assets/global/img/balls.gif') }}" alt="" class="center-block loading"/></div>');
            setTimeout(function () {
                modalEditInventory.load('/admin/inventory/' + inventoryId + '/model/form/data', function (response) {
                    InventoryEditModalHtml = response;
                    window.dispatchEvent(new Event('resize'));
                });
            }, 500);
        });
    </script>
@endsection

