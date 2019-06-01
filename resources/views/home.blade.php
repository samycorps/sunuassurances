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
                <div class="col-md-4 col-lg-4 col-xl-4">
                    <div class="div-table" onclick="Home.gotoMenu('motor')">
                        <div class="div-table-row">
                            <div class="div-table-col">
                                <img class="icon" src="{{ URL::asset('assets/images/icon_car.png') }}" alt="Motor" />
                            </div>
                            <div class="div-table-col title">
                                Motor
                            </div>
                        </div>
                    </div>
                </div>
            @if(strtolower(session('userData')['role']['name'])==='agent')
                <div class="col-md-4 col-lg-4 col-xl-4 col-md-offset-1">
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
        </div>

    </section>
    @endif

    @section('pagescripts')
    <script src="{{ URL::asset('assets/javascripts/forms/home.js') }}"></script>
	<script>$(function(){ Home.init(); });</script>
	@stop
@stop