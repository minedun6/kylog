<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">Show Supplier Details {{$supplier->company->name}}</h4>
</div>

<div class="modal-body">
<form role="form">
    <div class="form-body">
        <div class="form-group">
            <strong>
                <label class="uppercase control-label">Supplier Name</label>
            </strong>
            <p class="form-control-static">
                <span class="label label-success">{{ $supplier->company->name }}</span>
            </p>
        </div>
        <!--/span-->
        <div class="form-group">
            <strong>
                <label class="uppercase control-label">Tax Registration Number</label>
            </strong>
            <span class="label label-success">{{ $supplier->company->trn }} </span>
        </div>
        <!--/span-->
        <!--/row-->
        <div class="form-group">
            <strong>
                <label class="uppercase control-label">Customs Code</label>
            </strong>
            <span class="label label-success"> {{ $supplier->company->customs }} </span>
        </div>
        <!--/span-->
        <div class="form-group">
            <strong>
                <label class="uppercase control-label">Supplier Address</label>
            </strong>
            <span class="label label-success"> {{ $supplier->company->address }} </span>
        </div>
        <!--/span-->
        <!--/row-->
        <div class="form-group">
            <strong>
                <label class="uppercase control-label">Comment</label>
            </strong>
            <span class="label label-success"> {{ $supplier->company->comment }} </span>
        </div>
        <!--/span-->
        <div class="form-group">
            <strong>
                <label class="uppercase control-label">Supplier Logo</label>
            </strong>
            <p class="form-control-static">
                <span class="row">
                    <span class="col-md-8 col-md-offset-2">
                        <img class="img-responsive" src="{{ '/uploads/' . $supplier->company->logo }}"
                             alt="{{ $supplier->company->name }}">
                    </span>
                </span>
            </p>
        </div>
        <!--/span-->
        <!--/row-->
    </div>
</form>

    <div class="modal-footer">
        <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
    </div>
</div>