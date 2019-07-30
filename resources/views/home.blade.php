@extends('layouts.masterpage')

@section('title', 'Home Page')

@section('pagestyles')
<link rel="stylesheet" href="{{ URL::asset('assets/stylesheets/home.css') }}" />
@stop

@section('content')
    @if (session('userData'))
    <section role="main" class="content-body">
        <header class="page-header">
            <h2>Dashboard</h2>
            <input type="hidden" id="role_name" value="{{ strtolower(session('userData')['role']['name']) }}" />
            <div class="right-wrapper pull-right">
                <ol class="breadcrumbs">
                    <li>
                        <a href="/portal/home">
                            <i class="fa fa-home"></i>
                        </a>
                    </li>
                    <li><span>Dashboard</span></li>
                </ol>
        
                <!-- <a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a> -->
            </div>
        </header>
        <div class="row">
                <div class="col-md-4 col-lg-4 col-xl-4 bottom-margin">
                    <div class="div-table" onclick="Home.gotoMenu('{{in_array(strtolower(session('userData')['role']['name']), array('agent', 'client')) ? 'motor' : 'logs'}}')">
                        <div class="div-table-row">
                            <div class="div-table-col">
                                <img class="icon" src="{{ URL::asset('assets/images/icon_car.png') }}" alt="Motor" />
                            </div>
                            <div class="div-table-col title">
                                @if(in_array(strtolower(session('userData')['role']['name']), array("agent", "client")))
                                    Motor
                                @else 
                                    Request Logs
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @if(strtolower(session('userData')['role']['name'])==='agent')
                <div class="col-md-4 col-lg-4 col-xl-4 bottom-margin">
                        <div class="div-table" onclick="Home.gotoMenu('marine')">
                            <div class="div-table-row">
                                <div class="div-table-col">
                                    <img class="icon" src="{{ URL::asset('assets/images/icon_marine.png') }}" alt="Motor" />
                                </div>
                                <div class="div-table-col title">
                                    Marine
                                </div>
                            </div>
                        </div>
                </div>
            @endif
                <div class="col-md-4 col-lg-4 col-xl-4 bottom-margin">
                    <div class="div-table" onclick="Home.gotoMenu('claims')">
                        <div class="div-table-row">
                            <div class="div-table-col">
                                <img class="icon" src="{{ URL::asset('assets/images/icon_claims.jpg') }}" alt="Motor" />
                            </div>
                            <div class="div-table-col title">
                                Claims
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-lg-4 col-xl-4 bottom-margin">
                    <div class="div-table" onclick="Home.gotoMenu('payment')">
                        <div class="div-table-row">
                            <div class="div-table-col">
                                <img class="icon" src="{{ URL::asset('assets/images/icon_transaction-details.jpg') }}" alt="Motor" />
                            </div>
                            <div class="div-table-col title">
                                View Policies
                            </div>
                        </div>
                    </div>
                </div>
                @if(strtolower(session('userData')['role']['name'])==='agent')
                <div class="col-md-4 col-lg-4 col-xl-4 bottom-margin">
                    <div class="div-table" onclick="Home.gotoMenu('profile-kyc')">
                        <div class="div-table-row">
                            <div class="div-table-col">
                                <img class="icon" src="{{ URL::asset('assets/images/icon_create_profile.png') }}" alt="Motor" />
                            </div>
                            <div class="div-table-col title">
                                Create Profile
                            </div>
                        </div>
                    </div>
                </div>
                @endif
        </div>

    </section>
    @endif

    @section('pagescripts')
    <script src="{{ URL::asset('assets/javascripts/forms/home.js') }}"></script>
	<script>$(function(){ Home.init(); });</script>
	@stop
@stop