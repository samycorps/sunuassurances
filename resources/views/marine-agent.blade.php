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
                <nav class="navbar navbar-light bg-light" id="topbar">
                        <a class="navbar-brand" href="#" id="newPolicy" onclick="Marine.setActive()">
                        <i class="fa fa-plus"></i>
                            New Policy
                        </a>
						<!-- <a class="navbar-brand" href="#" id="renewPolicy"  onclick="Marine.setActive()">
                            <i class="fa fa-user"></i>
                            Renew Policy
                        </a> -->
                </nav>

                <!-- Setup new Policy -->
                <input type="hidden" id="profile_user_id" name="user_id" value="{{ session('userData')['user']['id'] }}" />
                <div id="newPolicySection" class="hide_elements">
                    <form action="" method="post" id="form-new">
                        <div class="form-group mb-lg">
                            <label>Search Profile</label>
                            <div class="input-group input-group-icon">
                                <input id="vehicle_reg_num" name="vehicle_reg_num" type="text" class="form-control input-lg typeahead" data-provide="typeahead" autocomplete="off" />
                                <span class="input-group-addon">
                                    <span class="icon icon-lg">
                                        <i class="fa fa-user"></i>
                                    </span>
                                </span>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Renew Policy Section-->
                <div id="renewPolicySection" class="hide_elements">
                    <form action="" method="post" id="form-renewal">
                        <div class="form-row">
                        </div>
                    </form>
                </div>
                <div class="alert-message">
                    <p class="alert-message-text"></p>
                </div>
                <div id="rootwizard">
                    <div class="container">
                        <ul>
                            <li><a href="#tab1" data-toggle="tab">Cargo Details</a></li>
                            <li><a href="#tab2" data-toggle="tab">Review Details</a></li>
                            <li><a href="#tab3" data-toggle="tab">Payment Options</a></li>
                            <li><a href="#tab4" data-toggle="tab">Confirmation</a></li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane" id="tab1">
                            <form id="tab1form" name="tab1form">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Choose Marine Cargo Type</label>
                                    <select id="cargo_type" name="cargo_type" class="form-control" required="required" >
                                        <option value="">Choose...</option>
                                        <option value="single_transit">Single Transit</option>
                                        <option value="open_cover">Open Cover</option>
                                        </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="insured_company_name">Insured Company Name</label>
                                    <input type="text" class="form-control" placeholder="Insured Company Name" name="insured_company_name" id="insured_company_name" required />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="start_period">Start Period</label>
                                    <input type="text" id="start_period" name="start_period" class="form-control datepicker"
                                    required="required" />
                                </div>
                                <div class="col-md-4">
                                    <label for="end_period">End Period</label>
                                    <input type="text" id="end_period" name="end_period" class="form-control datepicker"
                                    required="required" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Cover Type</label>
                                    <select id="cover_type" name="cover_type" class="form-control" required="required" >
                                        <option value="">Choose...</option>
                                        </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="sum_insured">Sum Insured</label>
                                    <input type="text" class="form-control" placeholder="Sum Insured" name="sum_insured" id="sum_insured" pattern="^\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" required/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Basic Rate</label>
                                    <select id="basic_rate" name="basic_rate" class="form-control" required="required">
                                        <option value="">Choose...</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label>Currency Type</label>
                                    <select id="currency_type" name="currency_type" class="form-control" required="required">
                                        <option value="">Choose...</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Location</label>
                                    <select id="location" name="location" class="form-control" required="required">
                                        <option value="">Choose...</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label>Vessel Reg. No</label>
                                    <input type="text" id="vessel_reg_num" name="vessel_reg_num" class="form-control"
                                    required="required" />
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Vogage From</label>
                                    <input type="text" id="voyage_from" name="voyage_from" class="form-control"
                                    required="required" />
                                </div>
                                <div class="col-md-6">
                                    <label>Voyage To</label>
                                    <input type="text" id="voyage_to" name="voyage_to" class="form-control"
                                    required="required" />
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Vessel Name</label>
                                    <input type="text" id="vessel_name" name="vessel_name" class="form-control"
                                    required="required" />
                                </div>
                                <div class="col-md-6">
                                    <label>Packing Type</label>
                                    <select id="packing_type" name="packing_type" class="form-control" required="required">
                                        <option value="">Choose...</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Conditions</label>
                                    <input type="text" id="conditions" name="conditions" class="form-control"
                                    required="required" />
                                </div>
                                <div class="col-md-6">
                                    <label>Excess</label>
                                    <input type="text" id="excess" name="excess" class="form-control" pattern="^\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency"
                                    required="required" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Conveyance</label>
                                    <textarea id="conveyance" name="conveyance" class="form-control" rows="3"
                                    required="required"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label>Description</label>
                                    <textarea id="description" name="description" class="form-control" rows="3"
                                    required="required"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Term of Insurance</label>
                                    <textarea id="term_of_insurance" name="term_of_insurance" class="form-control" rows="3"
                                    required="required"></textarea>
                                </div>
                            </div>
                            </form>
                        </div>
                        <div class="tab-pane marine" id="tab2">
                            <!-- Transaction Details -->
                            <div>
                                <div class="row col-md-12">
                                    <h3>Transaction Details</h3>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="display_label">Fullname / Company</label>
                                        <span id="fullname_span" class="display_span">Fullname / Company</span> 
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="display_label">Marine Cargo Type</label>
                                        <span id="cargo_type_span" class="display_span">Marine Cargo Type</span> 
                                    </div>
                                    <div class="col-md-6">
                                        <label class="display_label">Insured Company Name</label>
                                        <span id="insured_company_name_span" class="display_span">Insured Company Name</span> 
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="display_label">Start Period</label>
                                        <span id="start_period_span" class="display_span">Marine Cargo Type</span> 
                                    </div>
                                    <div class="col-md-6">
                                        <label class="display_label">End Period</label>
                                        <span id="end_period_span" class="display_span">Insured Company Name</span> 
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="display_label">Cover Type</label>
                                        <span id="cover_type_span" class="display_span">Marine Cargo Type</span> 
                                    </div>
                                    <div class="col-md-6">
                                        <label class="display_label">Sum Insured</label>
                                        <span id="sum_insured_span" class="display_span">Insured Company Name</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="display_label">Cover Type</label>
                                        <span id="cover_type_span" class="display_span">Marine Cargo Type</span> 
                                    </div>
                                    <div class="col-md-6">
                                        <label class="display_label">Premium</label>
                                        <span id="premium_span" class="display_span"></span>  
                                    </div>
                                </div>
                            </div> <!-- Container -->
                        </div>
                        <div class="tab-pane" id="tab3">
                            <div>
                                    <!-- <div class="row col-md-12">
                                        <h4>Select Payment Option</h4>
                                    </div> -->
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Choose Preferred Payment Method</label>
                                            <select id="motor_payment_method" name="motor_payment_method" class="form-control" required="required" >
                                                <option value="">Choose...</option>
                                                <option value="CADVICE">Credit Note</option>
                                                <option value="EPAY">Instant Payment</option>
                                                </select>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab4">
                                <div class="loading_icon hide_elements">
                                    <img src="{{ URL::asset('assets/images/Loading_icon.gif') }}"/>
                                </div>
                            <div id="legend_fail_response">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3 id="legendResponseMessage"></h3>
                                    </div>
                                </div>
                            </div>
                            <div id="legend_success_response" class="hide">
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
                        <ul class="pager wizard">
                            <li class="previous first" style="display:none;"><a href="#">First</a></li>
                            <li class="previous"><a href="#">Previous</a></li>
                            <li class="next last" style="display:none;"><a href="#">Last</a></li>
                            <li class="next"><a href="#">Next</a></li>
                        </ul>
                    </div>
                </div> <!-- End of Wizard-->

            </div>
        </div>
    </section>
    @endif

    @section('pagescripts')
    <script src="{{ URL::asset('assets/vendor/jquery-serialize-json/jquery.serializejson.js') }}"></script>
    <script src="{{ URL::asset('assets/vendor/bootstrap-typeahead/bootstrap3-typeahead.min.js') }}"></script>
    <script src="{{ URL::asset('assets/javascripts/forms/utility.js') }}"></script>
	<script>$(function(){ Utility.init(); });</script>
    <script src="{{ URL::asset('assets/javascripts/forms/marine.js') }}"></script>
	<script>$(function(){ Marine.init(); });</script>
	<script src="https://js.paystack.co/v1/inline.js"></script>
    @stop

@stop