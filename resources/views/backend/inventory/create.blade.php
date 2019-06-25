@extends('backend.layouts.app')

@section ('title')
    {{ app_name() }} | Inventory
@endsection

@section('after-styles')
    <link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet"
          type="text/css"/>
@endsection

@section('page-header')
    <h1>
        Inventory
    </h1>
@endsection

@section('content')
    <div class="portlet light">
        <div class="portlet-title">
            <div class="caption">Add New Inventory</div>

            <div class="actions">
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="portlet-body form">
            <form action="{{ route('admin.inventory.product.pick') }}">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-6">
                            <div class="form-group">
                                <label for="company" class="control-label">Select Either Supplier/Client</label>
                                <select name="company" id="company" class="form-control select2"
                                        v-model="selectedCompany">
                                    @foreach($companies as $key => $group)
                                        <optgroup label="{{ config('kylogger.companies_type.' . $key) }}">
                                            @foreach($group as $company)
                                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn blue" :disabled="selectedCompany == null"
                            data-disable-with="<i class='fa fa-refresh fa-spin fa-fw'></i> Processing...">Next
                        <span class="fa fa-long-arrow-right"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('after-scripts')
    <script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/backend/init.js') }}" type="text/javascript"></script>
    <script>
        const app = new Vue({
            el: '#app',
            data() {
                return {
                    selectedCompany: null
                }
            }
        })
        $('#company').on('change', function () {
            app['selectedCompany'] = $(this).val();
        });
    </script>
@endsection
