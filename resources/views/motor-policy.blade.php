@extends('layouts.masterpage')

@section('title', 'Motor Insurance Page')

@section('content')
    @if (session('userData'))
    <section role="main" class="content-body">
        <header class="page-header">
            <h2>MOTOR INSURANCE POLICY</h2>
        
            <div class="right-wrapper pull-right">
                <ol class="breadcrumbs">
                    <li>
                        <a href="/portal/home">
                            <i class="fa fa-home"></i>
                        </a>
                    </li>
                    <li><span>Motor Insurance</span></li>
                </ol>
        
                <!-- <a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a> -->
            </div>
        </header>
        <div class="row">
			<div class="col-md-12 col-lg-12 col-xl-6">
                    <div class="tab-pane" id="tab5">
                        <!-- Payment Success Call Legend -->
                        @if (!strpos($policyDetails , 'Policy Number'))
                        <div id="legend_fail_response">
                            <div class="row">
                                <div class="col-md-12">
                                    <h3 id="legendResponseMessage">
                                        {{ $policyDetails }}
                                    </h3>
                                </div>
                            </div>
                        </div>
                        @else
                        <div id="legend_success_response">
                            <div class="row policy_details">
                                <div class="col-md-6">
                                    Client Number
                                </div>
                                <div class="col-md-6">
                                    <span id="client_number"></span>
                                    {{ $vehicleDetails['registration_number'] }}
                                </div>
                            </div>
                            <div class="row policy_details">
                                <div class="col-md-6">
                                    Policy Number
                                </div>
                                <div class="col-md-6">
                                    <span id="policy_number"></span>
                                    {{ strpos($policyDetails , 'Policy Number') }}
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
                        @endif
                    </div>
            </div>
        </div>
    </section>
    @endif
    
@stop