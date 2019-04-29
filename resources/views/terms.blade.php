@extends('layouts.masterpage')

@section('title', 'Home Page')

@section('pagestyles')
<link rel="stylesheet" href="{{ URL::asset('assets/stylesheets/home.css') }}">
@stop

@section('content')
    @if (session('userData'))
    <section role="main" class="content-body">
        <header class="page-header">
            <h2>Terms and Conditions</h2>
            <input type="hidden" id="role_name" value="{{ strtolower(session('userData')['role']['name']) }}" />
            <input type="hidden" id="profile_details" name="profile_details" value="{{json_encode(session('userData')['profile'])}}" />
            <input type="hidden" id="user_details" name="user_details" value="{{json_encode(session('userData')['user'])}}" />
            <input type="hidden" id="profile_id" name="profile_id" value="{{ session('userData')['profile']['id'] }}" />
            <div class="right-wrapper pull-right">
                <ol class="breadcrumbs">
                    <li>
                        <a href="/">
                            <i class="fa fa-home"></i>
                        </a>
                    </li>
                    <li><span>Terms &amp; Conditions</span></li>
                </ol>
        
                <!-- <a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a> -->
            </div>
        </header>

        <div class="panel panel-sign">
                <div class="panel-heading">
                    <i class="fa fa-info-circle fa-2x"></i> Terms &amp; Conditions
                </div>
                <div class="panel-body">
                    
                </div>
            </div>
    </section>
    @endif

    @section('pagescripts')
    <!-- Specific Page Vendor -->
	@stop
@stop