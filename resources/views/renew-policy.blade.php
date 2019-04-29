@extends('layouts.masterpage')

@section('title', 'Insurance Renewal Page')

@section('pagestyles')
<link rel="stylesheet" href="{{ URL::asset('assets/stylesheets/motor.css') }}" />
@stop

@section('content')
    @if (session('userData'))
    <section role="main" class="content-body">
        <header class="page-header">
            <h2>MOTOR INSURANCE POLICY</h2>
        
            <div class="right-wrapper pull-right">
                <ol class="breadcrumbs">
                    <li>
                        <a href="/sunu/home">
                            <i class="fa fa-home"></i>
                        </a>
                    </li>
                    <li><span>Motor Insurance</span></li>
                </ol>
        
                <!-- <a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a> -->
            </div>
        </header>
        @if (count($vehicleDetails['policy']) === 0)
        <div class="row">
            <div class="col-md-12 col-lg-12 col-xl-6">
                    <section class="panel">
                        No record found
                    </section>
            </div>
        </div>
        @else    
        <div class="row">
            <div class="col-md-12 col-lg-12 col-xl-6">
                    <section class="panel">
                        <header class="panel-heading">
                            <div class="panel-actions">
                                <a href="#" class="fa fa-caret-down"></a>
                                <a href="#" class="fa fa-times"></a>
                            </div>
                            <h2 class="panel-title">Policy Renewal</h2>
                        </header>
                        <input type="hidden" id="user_id" name="user_id" value="{{ session('userData')['user']['id'] }}" />
				<input type="hidden" id="profile_id" name="profile_id" value="{{ session('userData')['profile']['id'] }}" />
                        <input type="hidden" id="vehicle_details" name="vehicle_details" value="{{json_encode($vehicleDetails)}}" />
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12 policy-renewal">
                                    <div class="icon_section">
                                            <img src="{{ URL::asset('assets/images/icon_car.png') }}" height="35" alt="Joseph Doe" class="img-circle">
                                    </div>
                                    <div class="label_section">
                                        <p>Policy Number</p>
                                        <p>Amount</p>
                                    </div>
                                    <div class="label_section">
                                        <p>{{$vehicleDetails['policy'][count($vehicleDetails['policy']) - 1]['policy_number']}}</p>
                                        <p>{{number_format($vehicleDetails['payment'][count($vehicleDetails['payment']) - 1]['transaction_amount']/100, 2, '.', '')}}</p>
                                    </div>
                                    <div class="label_section">
                                        <p>Expiry Date</p>
                                        <p></p>
                                    </div>
                                    <div class="label_section">
                                        <p>{{$vehicleDetails['policy'][count($vehicleDetails['policy']) - 1]['expiry_date']}}</p>
                                        <p>{{$vehicleDetails['policy'][count($vehicleDetails['policy']) - 1]['cover_type']}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <div>
                                <button id="renew_policy_btn" class="btn btn-primary" onclick="RenewPolicy.processPolicy()">Renew Policy</button>
                            </div>
                        </div>
                        <div class="alert-message">
                            <p class="alert-message-text"></p>
                        </div>
                    </section>
            </div>
        </div>
        <div id ="policy_renewal_details" class="row hide_elements">
            <div class="col-md-12">
                <!-- Payment Success Call Legend -->
                <div id="legend_fail_response">
                    <div class="row">
                        <div class="col-md-12">
                            <h3 id="legendResponseMessage"></h3>
                        </div>
                    </div>
                </div>
                
                <div id="legend_success_response">
                    <div class="row policy_details">
                        <div class="col-md-6">
                            Client Number
                        </div>
                        <div class="col-md-6">
                            <span id="client_number"></span>
                        </div>
                    </div>
                    <div class="row policy_details">
                        <div class="col-md-6">
                            Policy Number
                        </div>
                        <div class="col-md-6">
                            <span id="policy_number"></span>
                        </div>
                    </div>
                    <div class="row policy_details">
                        <div class="col-md-6">
                            Certificate Number
                        </div>
                        <div class="col-md-6">
                            <span id="certificate_number"></span>
                        </div>
                    </div>
                    <div class="row policy_details">
                        <div class="col-md-6">
                            Debit Note Number
                        </div>
                        <div class="col-md-6">
                            <span id="debit_note_number"></span>
                        </div>
                    </div>
                    <div class="row policy_details">
                        <div class="col-md-6">
                            Receipt Number
                        </div>
                        <div class="col-md-6">
                            <span id="receipt_number"></span>
                        </div>
                    </div>
                    <div class="row policy_details">
                        <div class="col-md-6">
                            Expiry Date
                        </div>
                        <div class="col-md-6">
                            <span id="expiry_date"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </section>
    @endif
	
	@section('pagescripts')
    <script src="{{ URL::asset('assets/javascripts/forms/renew-policy.js') }}"></script>
	<script>$(function(){ RenewPolicy.init(); });</script>
	<script src="https://js.paystack.co/v1/inline.js"></script>
	@stop

@stop