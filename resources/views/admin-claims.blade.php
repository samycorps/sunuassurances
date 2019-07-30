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
            <h2>Client Policy Claims Logs </h2>
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
                    <li><span>Administrative Claims Mgt</span></li>
                </ol>
        
                <!-- <a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a> -->
            </div>
        </header>

        <div class="panel panel-sign">
                <div class="panel-heading">
                    <i class="fa fa-money fa-2x"></i> Claims Request Information
                </div>
                <div class="panel-body">
                    <div class="tabs">
                        <ul class="nav nav-tabs tabs-primary">
                            <li class="active">
                                <a href="#submit-claim" data-toggle="tab">Claims Logs</a>
                            </li>
                            <li>
                                <a href="#view-claims" data-toggle="tab">New Tab</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div id="submit-claim" class="tab-pane active">
                                @include('admin-claims-view')
                            </div>
                            <div id="view-claims" class="tab-pane">
                                <section class="card">
                                    <header class="card-header">
                                        <div class="card-actions">
                                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                                        </div>
                                    </header>
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
    <script src="{{ URL::asset('assets/vendor/dropzone/dropzone.js') }}"></script>
    <script src="{{ URL::asset('assets/vendor/jquery-serialize-json/jquery.serializejson.js') }}"></script>
    <script src="{{ URL::asset('assets/vendor/simple-pagination/jquery.simplePagination.js') }}"></script>
    <script src="{{ URL::asset('assets/javascripts/forms/utility.js') }}"></script>
    <script>$(function(){ Utility.init(); });</script>
    <script src="{{ URL::asset('assets/javascripts/forms/admin.js') }}"></script>
    <script>$(function(){ Admin.init(); });</script>
	@stop
@stop