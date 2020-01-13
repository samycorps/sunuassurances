@extends('layouts.masterpage')

@section('title', 'Home Page')

@section('pagestyles')
<link rel="stylesheet" href="{{ URL::asset('assets/stylesheets/home.css') }}">
<link rel="stylesheet" href="{{ URL::asset('assets/vendor/jquery-datatables-bs3/assets/css/datatables.css') }}" >
<link rel="stylesheet" href="{{ URL::asset('assets/vendor/simple-pagination/simplePagination.css') }}" >
@stop

@section('content')
    @if (session('userData'))
    <section role="main" class="content-body">
        <header class="page-header">
            <h2>Administrative Reports </h2>
            <input type="hidden" id="role_name" value="{{ strtolower(session('userData')['role']['name']) }}" />
            <input type="hidden" id="profile_details" name="profile_details" value="{{json_encode(session('userData')['profile'])}}" />
            <input type="hidden" id="user_details" name="user_details" value="{{json_encode(session('userData')['user'])}}" />
            <input type="hidden" id="profile_id" name="profile_id" value="{{ session('userData')['profile']['id'] }}" />
            <div class="right-wrapper pull-right">
                <ol class="breadcrumbs">
                    <li>
                        <a href="/portal/home">
                            <i class="fa fa-home"></i>
                        </a>
                    </li>
                    <li><span>Administrative Reports</span></li>
                </ol>
        
                <!-- <a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a> -->
            </div>
        </header>

        <div class="panel panel-sign">
                <div class="panel-heading">
                <i class="fa fa-arrow-circle-right fa-2x" aria-hidden="true"></i> Reports Type Categories
                </div>
                <div class="panel-body">
                    <div class="tabs">
                        <ul class="nav nav-tabs tabs-primary">
                            <li class="active">
                                <a href="#sales" data-toggle="tab">Sales Reports</a>
                            </li>
                            <li>
                                <a href="#customer" data-toggle="tab">Customer Reports</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div id="sales" class="tab-pane active">
                                <section class="card">
                                    <header class="card-header">
                                        <div class="card-actions">
                                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                                        </div>
                                        <div class="row bottom-margin">
                                            <div class="form-group col-md-6">
                                                <label class="form-label">Report type</label>
                                                <select id="category_sales" name="category_sales" class="form-control" required="required" onchange="Report.onCategoryChange()">
                                                    <option value="">Choose...</option>
                                                    <option value="salesbydate">All Sales by Date</option>
                                                    <option value="salesbyproducts"> All Sales by Products</option>
                                                    <option value="salesbyagents"> All Sales by Agents</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                        <div class="col-md-3">
                                            <label for="start_date">Start Date</label>
                                            <input type="text" id="start_date" name="start_date" class="form-control datepicker"
                                            required="required" readonly="readonly" />
                                        </div>
                                        <div class="col-md-3">
                                            <label for="end_date">End Date</label>
                                            <input type="text" id="end_date" name="end_date" class="form-control datepicker"
                                            required="required" readonly="readonly" />
                                        </div>
                                        <div class="col-md-2">
                                            <label for="">Rows/Page</label>
                                            <select id="rows_per_page" name="rows_per_page" class="form-control" required="required">
                                                <option value="20">20</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="">Filter</label>
                                            <select id="filter_by" name="filter_by" class="form-control" required="required">
                                                <option value="all">All</option>
                                                <option value="success">Success</option>
                                                <option value="failure">Fail</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-primary btn-lg" onclick="Report.getSalesReport()">Search</button>
                                        </div>
                                    </div>   
                                    </header>
                                    <div class="card-body">
                                        @include('admin-reports-sales')
                                        @include('admin-reports-sales-products')
                                        @include('admin-reports-sales-agents')
                                    </div>
                                    </section>
                            </div>
                            <div id="customer" class="tab-pane">
                                <section class="card">
                                        <header class="card-header">
                                            <div class="card-actions">
                                                <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                                                <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                                            </div>
                                            <div class="row bottom-margin">
                                                <div class="form-group col-md-6">
                                                    <label class="form-label">Report type</label>
                                                    <select id="category_customer" name="category_customer" class="form-control" required="required" onchange="Report.onCategoryChange()">
                                                        <option value="">Choose...</option>
                                                        <option value="activepolicies">All Customers with Active Insurance Policies</option>
                                                        <option value="expiringpolicies"> All Customers with Expiring Insurance Policies</option>
                                                        <option value="expiredpolicies"> All Customers with Expired Policies</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 hide_element">
                                                    <label for="">Expiry Days</label>
                                                    <select id="expiry_days" name="expiry_days" class="form-control" required="required">
                                                    </select>
                                                </div>
                                                <div class="col-md-6 pull-right">
                                                    <button type="button" class="btn btn-primary btn-lg" onclick="Report.getPolicyReport()">Search</button>
                                                </div>
                                            </div>    
                                        </header>
                                        <div class="card-body">
                                        @include('admin-reports-customers-active')
                                        </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    @endif

    @section('pagescripts')
    <!-- Specific Page Vendor -->
	<script src="{{ URL::asset('assets/vendor/jquery-datatables/media/js/jquery.dataTables.js') }}"></script>
	<script src="{{ URL::asset('assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js') }}"></script>
	<script src="{{ URL::asset('assets/vendor/jquery-datatables-bs3/assets/js/datatables.js') }}"></script>
    <script src="{{ URL::asset('assets/javascripts/tables/examples.datatables.default.js') }}"></script>
    <script src="{{ URL::asset('assets/javascripts/tables/examples.datatables.row.with.details.js') }}"></script>
    <script src="{{ URL::asset('assets/javascripts/tables/examples.datatables.tabletools.js') }}"></script>
    <script src="{{ URL::asset('assets/vendor/simple-pagination/jquery.simplePagination.js') }}"></script>
    <script src="{{ URL::asset('assets/javascripts/forms/utility.js') }}"></script>
	<script>$(function(){ Utility.init(); });</script>
    <script src="{{ URL::asset('assets/javascripts/forms/reports.js') }}"></script>
    <script>$(function(){ Report.init(); });</script>
	@stop
@stop