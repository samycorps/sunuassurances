@extends('layouts.masterpage')

@section('title', 'Motor Insurance Page')

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
                    <!-- Image and text -->
                <nav class="navbar navbar-light bg-light" id="topbar">
                        <a class="navbar-brand" href="#" id="newClient" onclick="MotorBroker.setActive()">
                        <i class="fa fa-plus"></i>
                            New Policy
                        </a>
                        <!-- <a class="navbar-brand" href="#" id="existingClient"  onclick="MotorBroker.setActive()">
                            <i class="fa fa-user"></i>
                            Existing Client
						</a> -->
						<a class="navbar-brand" href="#" id="renewPolicy"  onclick="MotorBroker.setActive()">
                            <i class="fa fa-user"></i>
                            Renew Policy
                        </a>
                </nav>

                <!-- Setup new Profile -->
				<input type="hidden" id="profile_user_id" name="user_id" value="{{ session('userData')['user']['id'] }}" />
				<section class="panel">
					<header class="panel-heading">
						<div class="panel-actions">
							<a href="#" class="fa fa-caret-down"></a>
							<a href="#" class="fa fa-times"></a>
						</div>

						<h2 class="panel-title">Profile &amp; Type of Insurance</h2>
					</header>
					<div class="panel-body">
						<div id="newProfileSection" class="">
							<div class="form-group mb-lg">
								<label>Search Profile</label>
								<div class="input-group input-group-icon">
									<input id="profile_kyc" name="profile_kyc" type="text" class="form-control input-lg typeahead" data-provide="typeahead" autocomplete="off" />
									<span class="input-group-addon">
										<span class="icon icon-lg">
											<i class="fa fa-user"></i>
										</span>
									</span>
								</div>
							</div>
							<div class="form-check">
								<input type="checkbox" class="form-check-input" id="new_additional_policy" name="new_additional_policy" onclick="MotorBroker.toggleExistingClientNumber()" >
								<label class="form-check-label" for="new_additional_policy">Create additional policy for existing policy holder</label>
							</div>
							<!-- <div class="form-row" id="policyRow">
								<div class="form-group col-md-12 padding-left-zero">
									<label>Select Motor Insurance Policy Option</label>
									<select id="insurance_policy_type" name="insurance_policy_type" class="form-control" required="required" onchange="MotorBroker.showHidePolicySection()">
										<option value="">Choose...</option>
										<option value="new">New Policy</option>
										<option value="renewal">Renew an Expiring Policy</option>
									</select>
								</div>
							</div> -->
							<!-- <div class="alert-message">
								<p class="alert-message-text"></p>
							</div> -->
						</div>
						<!-- End Setup new profile-->
					</div>
				</section>
                

                <!-- Setup Profile Selection for Existing Clients -->
                <div id="existingClientSection" class="hide_elements">
                    <div class="form-row" id="profileRow">
                        <div class="form-group col-md-12">
                            <label>Select Client Profile</label>
                            <select id="client_profile_id" name="client_profile_id" class="form-control" required="required" onchange="MotorBroker.setCustomerProfile()">
                                    <option value="">Choose...</option>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- End Setup Profile Selection for Existing Clients -->
            </div>
        </div>

        <!-- Start Wizard -->
		<div class="row" id="newAndAdditionalPolicySection">
			<div class="col-md-12 col-lg-12 col-xl-6">
                <input type="hidden" id="user_role_loggedIn" name="user_role_loggedIn" value="{{ session('userData')['role']['name'] }}" />
				<input type="hidden" id="user_id" name="user_id" value="{{ session('userData')['user']['id'] }}" />
				<input type="hidden" id="profile_id" name="profile_id" value="{{ session('userData')['profile']['id'] }}" />
				<input type="hidden" id="title" name="title" value="{{ session('userData')['profile']['title'] }}" />
				<input type="hidden" id="customer_firstname" name="customer_firstname" value="{{ session('userData')['profile']['firstname'] }}" />
				<input type="hidden" id="customer_lastname" name="customer_lastname" value="{{ session('userData')['profile']['lastname'] }}" />
				<input type="hidden" id="customer_othernames" name="customer_othernames" value="{{ session('userData')['profile']['othernames'] }}" />
				<input type="hidden" id="company_name" name="company_name" value="{{ session('userData')['profile']['company_name'] }}" />
				<input type="hidden" id="street_address" name="street_address" value="{{ session('userData')['profile']['street_address'] }}" />
				<input type="hidden" id="city" name="city" value="{{ session('userData')['profile']['city'] }}" />
				<input type="hidden" id="local_govt_area" name="local_govt_area" value="{{ session('userData')['profile']['local_govt_area'] }}" />
				<input type="hidden" id="state" name="state" value="{{ session('userData')['profile']['state'] }}" />
				<input type="hidden" id="date_of_birth" name="date_of_birth" value="{{ session('userData')['profile']['date_of_birth'] }}" />
				<input type="hidden" id="customer_email" name="customer_email" value="{{ session('userData')['user']['email_address'] }}" />
				<input type="hidden" id="customer_gsm_number" name="customer_gsm_number" value="{{ session('userData')['profile']['gsm_number'] }}" />
				<input type="hidden" id="customer_office_number" name="customer_office_number" value="{{ session('userData')['profile']['office_number'] }}" />
				<input type="hidden" id="occupation" name="occupation" value="{{ session('userData')['profile']['occupation'] }}" />
				<input type="hidden" id="tin_number" name="tin_number" value="{{ session('userData')['profile']['tin_number'] }}" />
				<input type="hidden" id="fax_number" name="fax_number" value="{{ session('userData')['profile']['fax_number'] }}" />
				<input type="hidden" id="website" name="website" value="{{ session('userData')['profile']['website'] }}" />
				<input type="hidden" id="company_reg_num" name="company_reg_num" value="{{ session('userData')['profile']['company_reg_num'] }}" />
				<input type="hidden" id="agent_code" name="agent_code" value="{{ session('userData')['profile']['agent_code'] }}" />
				<input type="hidden" id="user_category" name="user_category" value="{{ session('userData')['user']['user_category'] }}" />
				<input type="hidden" id="currency" name="currency" value="NGN" />
                <input type="hidden" id="policy_class" name="policy_class" value="001" />
                <input type="hidden" id="contact_person" name="contact_person" value="{{ session('userData')['profile']['contact_person'] }}" />
                <input type="hidden" id="bank_account_number" name="bank_account_number" value="{{ session('userData')['profile']['bank_account_number'] }}" />
                <input type="hidden" id="customer_bank" name="customer_bank" value="{{ session('userData')['profile']['customer_bank'] }}" />
				<section class="panel">
					<header class="panel-heading">
						<div class="panel-actions">
							<a href="#" class="fa fa-caret-down"></a>
							<a href="#" class="fa fa-times"></a>
						</div>

						<h2 class="panel-title">My Profile</h2>
					</header>
					<div class="panel-body">
						<div id="rootwizard">
							<div class="container">
								<ul>
									<li><a href="#tab1" data-toggle="tab">Registration</a></li>
									<li><a href="#tab2" data-toggle="tab">Vehicle Details</a></li>
									<li><a href="#tab3" data-toggle="tab">Amount Due</a></li>
									<li><a href="#tab4" data-toggle="tab">Payment</a></li>
									<li><a href="#tab5" data-toggle="tab">Confirmation</a></li>
								</ul>
							</div>
							<div class="tab-content">
								<div class="tab-pane" id="tab1">
									<form id="tab1form" name="tab1form">
										<div class="form-group mb-lg hide_elements" id="existingPolicyDiv">
											<label>Exisiting Policy Number</label>
											<div class="input-group input-group-icon">
												<input id="existing_policy_number" name="existing_policy_number" type="text" class="form-control input-lg" />
												<span class="input-group-addon">
													<span class="icon icon-lg">
														<i class="fa fa-file-o"></i>
													</span>
												</span>
											</div>
										</div>
										<div class="form-group mb-lg hide_elements" id="existingClientNumberDiv">
											<label>Exisiting Client Number</label>
											<div class="input-group input-group-icon">
												<input id="existing_client_number" name="existing_client_number" type="text" class="form-control input-lg" />
												<span class="input-group-addon">
													<span class="icon icon-lg">
														<i class="fa fa-user"></i>
													</span>
												</span>
											</div>
										</div>
										<div class="form-group mb-lg">
											<label>Registration Number</label>
											<div class="input-group input-group-icon">
												<input id="vehicle_reg_num" name="vehicle_reg_num" type="text" class="form-control input-lg" autocomplete="off" />
												<span class="input-group-addon">
													<span class="icon icon-lg">
														<i class="fa fa-car"></i>
													</span>
												</span>
											</div>
                                        </div>
                                        <div class="loading_icon hide_elements">
                                                <img src="{{ URL::asset('assets/images/Loading_icon.gif') }}"/>
                                        </div>
										<div class="alert-message">
											<p class="alert-message-text"></p>
										</div>
									</form>
								</div>
								<div class="tab-pane" id="tab2">
									<form id="tab2form" name="tab2form">
										<div class="row">
											<div class="col-md-12 text-error">
												&nbsp;Kindly confirm and ensure that the information supplied in this section is accurate
												<br/>
												&nbsp;All fields are mandatory
											</div>
										</div>
										<div class="row">
											<div class="col-md-4">
												<label for="vehicle_make_model">Make/Model</label>
												<select id="vehicle_make_model" name="vehicle_make_model" class="form-control" required="required">
												<option value="">Choose...</option>
												</select>
											</div>
											<div class="col-md-4">
												<label for="vehicle_body">Body</label>
												<select id="vehicle_body" name="vehicle_body" class="form-control" required="required">
													<option value="">Choose...</option>
												</select>
											</div>
											<div class="col-md-4">
												<label for="vehicle_color">Color</label>
												<select id="vehicle_color" name="vehicle_color" class="form-control" required="required">
												</select>
											</div>
										</div>
										<div class="row">
											<div class="col-md-4">
												<label for="vehicle_cubic">Cubic Capacity</label>
												<input type="text" class="form-control" placeholder="Cubic Capacity" name="vehicle_cubic" id="vehicle_cubic" value="2.0" required/>
											</div>
											<div class="col-md-4">
												<label for="vehicle_engine_number">Engine Number</label>
												<input type="text" class="form-control" placeholder="Engine Number" name="vehicle_engine_number" id="vehicle_engine_number" required />
											</div>
											<div class="col-md-4">
												<label for="vehicle_chasis_number">Chasis Number</label>
												<input type="text" class="form-control" placeholder="Chasis Number" name="vehicle_chasis_number" id="vehicle_chasis_number" required />
											</div>
										</div>
										<div class="row">
											<div class="col-md-4">
												<label for="vehicle_number_of_seats">No of seats</label>
												<input type="text" class="form-control" placeholder="No of Seats" name="vehicle_number_of_seats" id="vehicle_number_of_seats" value="5" required />
											</div>
											<div class="col-md-4">
												<label for="vehicle_year_make">Year of Make</label>
												<select id="vehicle_year_make" name="vehicle_year_make" class="form-control" required="required">
													<option value="">Choose...</option>
												</select>
											</div>
											<div class="col-md-4">
												<label for="vehicle_year_purchase">Purchase Year</label>
												<select id="vehicle_year_purchase" name="vehicle_year_purchase" class="form-control" required="required">
													<option value="">Choose...</option>
												</select>
											</div>
										</div>
										<div class="row">
											<div class="col-md-4">
												<label for="vehicle_purchase_price">Purchase Price/Value</label>
												<input type="text" class="form-control" placeholder="Purchase Price/Value" name="vehicle_purchase_price" id="vehicle_purchase_price" pattern="^\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" required/>
											</div>
											<div class="col-md-4">
												<label for="vehicle_purchase_state">State of Purchase</label>
												<select id="vehicle_purchase_state" name="vehicle_purchase_state" class="form-control" required="required">
													<option value="">Choose...</option>
												</select>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<h3>Other Details</h3>
											</div>
										</div>
										<div class="row">
											<div class="col-md-4">
												<label for="vehicle_effective_date">Effective Date</label>
												<input type="text" id="vehicle_effective_date" name="vehicle_effective_date" class="form-control datepicker" readonly="readonly"
												required="required" />
                                            </div>
                                            <div class="col-md-4">
												<label for="vehicle_expiry_date">Expiry Date</label>
												<input type="text" id="vehicle_expiry_date" name="vehicle_expiry_date" class="form-control"
												required="required" readonly="readonly" />
											</div>
										</div>
									</form>
								</div>
								<div class="tab-pane" id="tab3">
									<form id="tab3form" name="tab3form">
										<div class="row">
											<div class="col-md-12">
												<label>Select your class of insurance</label>
												<select id="insurance_class" name="insurance_class" class="form-control" required="required" onchange="Motor.onClassChange()">
													<option value="">Choose...</option>
													<option value="third_party">3rd Party</option>
													<option value="third_party_fire_theft">3rd Party Fire and Theft</option>
													<option value="comprehensive">Comprehensive</option>
												</select>
											</div>
										</div>
										<div id="comprehensive_types_row" class="row hide">
											<div class="col-md-12">
												<label>Additional Options</label>
												<select id="comprehensive_type" name="comprehensive_type" class="form-control" required="required" onchange="Motor.onClassChange()">
													<option value="">Choose...</option>
													<option value="quartely">Quaterly</option>
													<option value="half_year">Half Year</option>
													<option value="full_year">Full Year</option>
													</select>
											</div>
										</div>
										<div id="comprehensive_addons_row" class="row hide">
											<div class="col-md-12">
												<div class="form-check">
													<input class="form-check-input" type="checkbox" value="" id="comprehensive_addons_flood" onclick="Motor.onClassChange()">
													<label class="form-check-label" for="comprehensive_addons_flood">
														Flood Extension +0.5%
													</label>
												</div>
												<div class="form-check">
													<input class="form-check-input" type="checkbox" value="" id="comprehensive_addons_riot" onclick="Motor.onClassChange()">
													<label class="form-check-label" for="comprehensive_addons_riot">
														Riot +0.5%
													</label>
												</div>
												<div class="form-check">
													<input class="form-check-input" type="checkbox" value="" id="comprehensive_addons_excess" onclick="Motor.onClassChange()">
													<label class="form-check-label" for="comprehensive_addons_excess">
														Excess Buy Back +0.5%
													</label>
												</div>
												<div id="comprehensive_addons_tracking" class="form-check hide">
													<input class="form-check-input" type="checkbox" value="" id="comprehensive_addons_tracking_option" onclick="Motor.onClassChange()">
													<label class="form-check-label" for="comprehensive_addons_tracking_option">
														Tracking
													</label>
												</div>
											</div>
										</div>
										<div id="third_party_message" class="row hide">
											<div class="col-md-12">
												<p class="text-error">
													Period of insurance is one year and it covers damage to 3rd party vehicle. For more details see terms and conditions.
												</p>
											</div>
										</div>
										<div id="comprehensive_message" class="row hide">
											<div class="col-md-12">
												<p class="text-error">
													Period of insurance is one year and it covers insured and damage to 3rd party vehicle. For more details see terms and conditions.
												</p>
											</div>
										</div>
										<div class="row coverage_details">
											<div class="col-md-6">
												Total Coverage Amount
											</div>
											<div class="col-md-6">
												<span >NGN &nbsp;</span> <p id="coverage_amount"></p>
											</div>
										</div>
									</form>
								</div>
								<div class="tab-pane" id="tab4">
									<div class="row">
										<div class="col-md-12">
											Customer Payment Information
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											Transaction Reference:
										</div>
										<div class="col-md-6">
												<p id="transactionReference"></p>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											Transaction Amount:
										</div>
										<div class="col-md-6">
												<p id="transactionAmount"></p>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											Transaction Date:
										</div>
										<div class="col-md-6">
												<p id="transactionDate"></p>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<p id="paymentSwitchStatus">
											</p>
										</div>
									</div>
								</div>
								<div class="tab-pane" id="tab5">
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
											<div class="col-md-12 pull-right">
											<button class="btn btn-primary" onclick="Motor.printPage()"><i class="fa fa-print"></i>&nbsp; Print</button>
											</div>
										</div>
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
						</div>
					</div>
				</section>
			</div>
		</div>
		<!-- End Wizard-->
    </section>
    @endif

	@section('pagescripts')
	<script src="{{ URL::asset('assets/vendor/jquery-serialize-json/jquery.serializejson.js') }}"></script>
    <script src="{{ URL::asset('assets/vendor/bootstrap-typeahead/bootstrap3-typeahead.min.js') }}"></script>
    <script src="{{ URL::asset('assets/javascripts/forms/utility.js') }}"></script>
	<script>$(function(){ Utility.init(); });</script>
    <!-- <script src="{{ URL::asset('assets/javascripts/forms/register.js') }}"></script>
    <script>$(function(){ Register.init(); });</script> -->
    <script src="{{ URL::asset('assets/javascripts/forms/motor.js') }}"></script>
    <script>$(function(){ Motor.init(); });</script>
    <script>$(function(){ MotorBroker.init(); });</script>
    <script src="https://js.paystack.co/v1/inline.js"></script>
    @stop
@stop