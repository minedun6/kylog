@extends('backend.layouts.app')

@section ('title', app_name() . ' | Supplier ' . $company->name . ' Details')

@section('page-header')
    <h1>
        Supplier Details
    </h1>
@endsection

@section('content')
    <div class="portlet light ">
        <div class="portlet-title tabbable-line">
            <div class="caption">Client {{ $company->name }}</div>
            @include ('backend.suppliers.partials.tabs')
            <div class="actions">

            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="portlet-body">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <address style="line-height: 25px;">
                        <div class="well">
                            <strong>Client Name : </strong>
                            {!! $company->getName() !!}
                            <br>
                            <strong>Tax Registration Number : </strong>
                            {!! $company->getTRN() !!}
                            <br>
                            <strong>Customs Code : </strong>
                            {!! $company->getCustoms() !!}
                            <br>
                            <strong>Company Address : </strong>
                            {!! $company->getAddress() !!}
                            <br>
                            <strong>Comment : </strong>
                            {!! $company->getComment() !!}
                            <br>
                        </div>
                    </address>
                </div>
            </div>
        </div><!-- /.box-body -->
    </div><!--box-->
@stop

@section('after-scripts')

@stop
