@extends('backend.layouts.app')

{{-- This template is responsible for showing the reception packages and the packages items --}}

@section ('title')
    {{ app_name() }} | Support Tickets Management
@endsection

@section('page-header')
    <h1>
        Support Tickets Management
    </h1>
@endsection

@section('content')

    <div class="portlet light">
        <div class="portlet-title">
            <div class="caption">Support Ticket Details</div>
            <div class="actions">
                <a href="" class="btn btn-success tooltips" title="Mark ticket as complete">
                    <i class="fa fa-check"></i>
                </a>
                <a href="" class="btn btn-warning tooltips" title="Edit ticket details">
                    <i class="fa fa-edit"></i>
                </a>
                <a href="" class="btn btn-danger tooltips" title="Delete Ticket">
                    <i class="fa fa-trash"></i>
                </a>
            </div>
        </div>
        <div class="portlet-body form">
            <div class="form-body">
                <div class="row">
                </div>
            </div>
        </div>
    </div>

@endsection
