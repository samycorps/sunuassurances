@extends('layouts.masterpage')

@section('title', 'KYC Profile')

@section('pagestyles')
<link rel="stylesheet" href="{{ URL::asset('assets/stylesheets/home.css') }}" />
@stop

@section('content')
    @if (session('userData'))
    <section role="main" class="content-body">
        <header class="page-header">
            <h2>KYC Profile</h2>
            <input type="hidden" id="role_name" value="{{ strtolower(session('userData')['role']['name']) }}" />
            <input type="hidden" id="profile_details" name="profile_details" value="{{json_encode(session('userData')['profile'])}}" />
            <input type="hidden" id="user_details" name="user_details" value="{{json_encode(session('userData')['user'])}}" />
            <input type="hidden" id="page_name" name="page_name" value="kyc_profile"/>
            <div class="right-wrapper pull-right">
                <ol class="breadcrumbs">
                    <li>
                        <a href="/portal/home">
                            <i class="fa fa-home"></i>
                        </a>
                    </li>
                    <li><span>KYC Profile</span></li>
                </ol>
            </div>
        </header>

        <div class="panel panel-sign">
                <div class="panel-body">
                    <nav class="navbar navbar-light bg-light" id="topbar">
                            <!-- <a class="navbar-brand" href="#" id="newClient" onclick="MotorBroker.setActive()">
                            <i class="fa fa-plus"></i>
                                New Profile
                            </a>
                            <a class="navbar-brand" href="#" id="renewPolicy"  onclick="MotorBroker.setActive()">
                                <i class="fa fa-user"></i>
                                Existing Profile
                            </a> -->
                            <!-- <a class="navbar-brand" href="#" id="existingClient"  onclick="MotorBroker.setActive()">
                                <i class="fa fa-user"></i>
                                View Profiles
                            </a> -->
                    </nav>
                    <form action="" method="post" id="form-register">
                        <div class="form-group mb-lg">
                            <label>Search Profile</label>
                            <div class="input-group input-group-icon">
                                <input id="profile_kyc" name="profile_kyc" type="text" class="form-control input-lg typeahead" data-provide="typeahead" autocomplete="off" onblur="Register.setProfileDetails()" />
                                <span class="input-group-addon">
                                    <span class="icon icon-lg">
                                        <i class="fa fa-user"></i>
                                    </span>
                                </span>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                            <label>Select your registration category</label>
                            <select id="category" name="category" class="form-control" required="required" onchange="Register.onCategoryChange()">
                                <option value="">Choose...</option>
                                <option value="Individual">Individual</option>
                                <option value="Corporate">Corporate</option>
                                <option value="Government">Government</option>
                            </select>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-row" id="titleRow">
                            <div class="form-group col-md-6">
                            <label>Title</label>
                            <select id="profile_title" name="profile_title" class="form-control" required="required">
                                <option value="">Choose...</option>
                            </select>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-row" id="nameRow">
                            <div class="form-group col-md-6">
                            <label class="required">Firstname</label>
                            <input type="text" id="profile_firstname" name="profile_firstname" value="" class="form-control" placeholder="First name" required="required">
                            </div>
                            <div class="form-group col-md-6">
                            <label class="required">Lastname</label>
                            <input type="text" id="profile_lastname" name="profile_lastname" value="" class="form-control" placeholder="Last name" required="required">
                            </div>
                            <div class="form-group col-md-6">
                            <label>Othernames</label>
                            <input type="text" id="profile_othernames" name="profile_othernames" value="" class="form-control" placeholder="Other names">
                            </div>
                            <div class="form-group col-md-6">
                            <label>Date of Birth</label>
                            <input type="text" id="profile_date_of_birth" name="profile_date_of_birth" value="" class="form-control datepicker">
                            </div>
                        </div>

                        <div class="form-row" id="companyRow">
                            <div class="form-group col-md-12">
                            <label class="required">Company Name</label>
                            <input type="text" id="profile_company_name" name="profile_company_name" value="" class="form-control" placeholder="Company name">
                            </div>
                        </div>

                        <div class="form-row" id="companyRegRow">
                            <div class="form-group col-md-12">
                            <label class="required">Company Reg. #</label>
                            <input type="text" id="profile_company_reg_num" name="profile_company_reg_num" value="" class="form-control" placeholder="N/A">
                            </div>
                        </div>
                        
                        <div class="form-row" id="streetRow">
                            <div class="form-group col-md-12">
                            <label class="required">Street Address</label>
                            <input type="text" id="profile_street_address" name="profile_street_address" value="" class="form-control" placeholder="street address ..." required="required">
                            </div>
                        </div>

                        <div class="form-row" id="addressRow">
                            <div class="form-group col-md-6">
                            <label class="required">City/Town</label>
                            <select id="profile_city" name="profile_city" value="" class="form-control" required="required">
                                <option value="">Choose...</option>
                            </select>
                            </div>
                            <div class="form-group col-md-6">
                            <label>Local Govt. Area</label>
                            <input type="text" id="profile_lga" name="profile_lga" value="" class="form-control" placeholder="lga">
                            </div>
                            <div class="form-group col-md-6">
                            <label class="required">State/Region</label>
                            <select id="profile_state" name="profile_state" class="form-control" required="required">
                                    <option value="">Choose...</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row hide_elements" id="tinRow">
                            <div class="form-group col-md-6">
                            <label>TIN Number</label>
                            <input type="number" id="profile_tin_number" name="profile_tin_number" class="form-control" placeholder="tin number" required="required">
                            </div>
                        </div>

                        <div class="form-row" id="gsmRow">
                            <div class="form-group col-md-6">
                            <label>GSM Number</label>
                            <input type="number" id="profile_gsm_number" name="profile_gsm_number" value="" class="form-control" placeholder="gsm number" required="required">
                            </div>
                            <div class="form-group col-md-6">
                            <label>Office Number</label>
                            <input type="number" id="profile_office_number" name="profile_office_number" value="" class="form-control" placeholder="office number">
                            </div>
                        </div>

                        <div class="form-row" id="emailRow">
                            <div class="form-group col-md-6">
                            <label>Email Address</label>
                            <input type="email" id="profile_email_address" name="profile_email_address" value="" class="form-control" placeholder="email address" required="required">
                            </div>
                        </div>

                        <div class="form-row" id="occupationRow">
                            <div class="form-group col-md-6">
                            <label class="required" for="occupation">Occupation</label>
                            <select id="profile_occupation" name="profile_occupation" class="form-control" required="required">
                                <option value="">Choose...</option>
                            </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="required" for="sector">Sector</label>
                                <select id="profile_sector" name="profile_sector" class="form-control" required="required">
                                    <option value="">Choose...</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row" id="websiteRow">
                            <div class="form-group col-md-12">
                            <label>Website</label>
                            <input type="text" id="profile_website" name="profile_website" value="" class="form-control" placeholder="website">
                            </div>
                        </div>

                        <div class="form-row" id="contactPersonRow">
                            <div class="form-group col-md-12">
                            <label>Contact Person</label>
                            <input type="text" id="profile_contact_person" name="profile_contact_person" value="" class="form-control" placeholder="">
                            </div>
                        </div>

                        <div class="form-row" id="bankDetailsRow">
                            <div class="form-group col-md-6">
                            <label class="required">Account Number</label>
                            <input type="text" class="form-control" placeholder="Account Number" name="profile_bank_account_number" id="profile_bank_account_number" maxlength="10" value="" required />
                            </div>
                            <div class="form-group col-md-6">
                                <label for="profile_customer_bank">Bank Name</label>
                                <select id="profile_customer_bank" name="profile_customer_bank" class="form-control" required="required">
                                    <option value="">Choose...</option>
                                </select>
                            </div>
                        </div>

                        @if (strtolower(session('userData')['role']['name']) === 'broker')
                        <div class="form-row" id="agentRow">
                            <div class="form-group col-md-12">
                            <label>Agent Code</label>
                            <input type="text" id="agent_code" name="agent_code" value="" class="form-control" placeholder="agency code or name">
                            </div>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-md-12 text-center">
                                    <button type="button" class="btn btn-primary btn-lg" onclick="Register.registerProfile()">Save Profile</button>
                            </div>
                        </div>
                        <div class="alert-message">
                            <p class="alert-message-text"></p>
                        </div>
                    </form>
                </div>
            </div>
    </section>
    @endif

    @section('pagescripts')
    <script src="{{ URL::asset('assets/vendor/bootstrap-typeahead/bootstrap3-typeahead.min.js') }}"></script>
    <script src="{{ URL::asset('assets/javascripts/forms/utility.js') }}"></script>
	<script>$(function(){ Utility.init(); });</script>
    <script src="{{ URL::asset('assets/javascripts/forms/signin.js') }}"></script>
    <script src="{{ URL::asset('assets/javascripts/forms/register.js') }}"></script>
    <script>$(function(){ Register.init(); });</script>
	@stop
@stop