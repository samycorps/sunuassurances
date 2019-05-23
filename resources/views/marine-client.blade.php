@extends('layouts.masterpage')

@section('title', 'Marine Insurance Page')

@section('pagestyles')
<link rel="stylesheet" href="{{ URL::asset('assets/stylesheets/motor.css') }}" />
@stop
@section('content')
    @if (session('userData'))
    <section role="main" class="content-body">
        <header class="page-header">
            <h2>MARINE INSURANCE POLICY</h2>
        
            <div class="right-wrapper pull-right">
                <ol class="breadcrumbs">
                    <li>
                        <a href="/portal/home">
                            <i class="fa fa-home"></i>
                        </a>
                    </li>
                    <li><span>Marine Insurance</span></li>
                </ol>
            </div>
        </header>

        <div class="row" id="innerContainer">
			<div class="col-md-12 col-lg-12 col-xl-6">
                    <!-- Image and text -->
                <input type="hidden" id="profile_details" name="profile_details" value="{{json_encode(session('userData')['profile'])}}" />
                <input type="hidden" id="user_details" name="user_details" value="{{json_encode(session('userData')['user'])}}" />
                

                <!-- Setup new Policy -->
                <input type="hidden" id="profile_user_id" name="user_id" value="{{ session('userData')['user']['id'] }}" />
                                
                <div class="alert-message">
                    <p class="alert-message-text">
                        Note: Clients are not able to register marine insurance, please see an agent. Thank you
                    </p>
                </div>

            </div>
        </div>
    </section>
    @endif

    @section('pagescripts')
    
    @stop

@stop