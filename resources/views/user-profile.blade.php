@extends('layouts.masterpage')

@section('title', 'Home Page')

@section('pagestyles')
<link rel="stylesheet" href="{{ URL::asset('assets/stylesheets/home.css') }}" />
@stop

@section('content')
    @if (session('userData'))
    <section role="main" class="content-body">
        <header class="page-header">
            <h2>User Profile</h2>
            <input type="hidden" id="role_name" value="{{ strtolower(session('userData')['role']['name']) }}" />
            <input type="hidden" id="profile_details" name="profile_details" value="{{json_encode(session('userData')['profile'])}}" />
            <input type="hidden" id="user_details" name="user_details" value="{{json_encode(session('userData')['user'])}}" />
            <div class="right-wrapper pull-right">
                <ol class="breadcrumbs">
                    <li>
                        <a href="/sunu/home">
                            <i class="fa fa-home"></i>
                        </a>
                    </li>
                    <li><span>User Profile</span></li>
                </ol>
        
                <!-- <a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a> -->
            </div>
        </header>

        <div class="panel panel-sign">
                <div class="panel-body">
                    <form action="" method="post" id="form-register">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                            <label>Select your registration category</label>
                            <select id="category" name="category" class="form-control" required="required">
                                <option value="">Choose...</option>
                                <option value="Individual">Individual</option>
                                <option value="Corporate">Corporate</option>
                                @if (strtolower(session('userData')['role']['name']) !== 'broker')
                                <option value="Government">Government</option>
                                @endif
                            </select>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-row" id="titleRow">
                            <div class="form-group col-md-6">
                            <label>Title</label>
                            <select id="title" name="title" class="form-control" required="required">
                                <option value="">Choose...</option>
                            </select>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-row" id="nameRow">
                            <div class="form-group col-md-6">
                            <label class="required">Firstname</label>
                            <input type="text" id="firstname" name="firstname" value="{{ session('userData')['profile']['firstname'] }}" class="form-control" placeholder="First name" required="required">
                            </div>
                            <div class="form-group col-md-6">
                            <label class="required">Lastname</label>
                            <input type="text" id="lastname" name="lastname" value="{{ session('userData')['profile']['lastname'] }}" class="form-control" placeholder="Last name" required="required">
                            </div>
                            <div class="form-group col-md-6">
                            <label>Othernames</label>
                            <input type="text" id="othernames" name="othernames" value="{{ session('userData')['profile']['othernames'] }}" class="form-control" placeholder="Other names">
                            </div>
                            <div class="form-group col-md-6">
                            <label>Date of Birth</label>
                            <input type="text" id="date_of_birth" name="date_of_birth" value="{{ session('userData')['profile']['date_of_birth'] }}" class="form-control datepicker">
                            </div>
                        </div>

                        <div class="form-row" id="companyRow">
                            <div class="form-group col-md-12">
                            <label class="required">Company Name</label>
                            <input type="text" id="company_name" name="company_name" value="{{ session('userData')['profile']['company_name'] }}" class="form-control" placeholder="Company name">
                            </div>
                        </div>

                        <div class="form-row" id="companyRegRow">
                            <div class="form-group col-md-12">
                            <label class="required">Company Reg. #</label>
                            <input type="text" id="company_reg_num" name="company_reg_num" value="{{ session('userData')['profile']['company_reg_num'] }}" class="form-control" placeholder="N/A">
                            </div>
                        </div>
                        
                        <div class="form-row" id="streetRow">
                            <div class="form-group col-md-12">
                            <label class="required">Street Address</label>
                            <input type="text" id="street_address" name="street_address" value="{{ session('userData')['profile']['street_address'] }}" class="form-control" placeholder="street address ..." required="required">
                            </div>
                        </div>

                        <div class="form-row" id="addressRow">
                            <div class="form-group col-md-6">
                            <label class="required">City/Town</label>
                            <select id="city" name="city" value="{{ session('userData')['profile']['city'] }}" class="form-control" required="required">
                                <option value="">Choose...</option>
                            </select>
                            </div>
                            <div class="form-group col-md-6">
                            <label>Local Govt. Area</label>
                            <input type="text" id="lga" name="lga" value="{{ session('userData')['profile']['local_govt_area'] }}" class="form-control" placeholder="lga">
                            </div>
                            <div class="form-group col-md-6">
                            <label class="required">State/Region</label>
                            <select id="state" name="state" class="form-control" required="required">
                                    <option value="">Choose...</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row" id="gsmRow">
                            <div class="form-group col-md-12">
                            <label>GSM Number</label>
                            <input type="text" id="gsm_number" name="gsm_number" value="{{ session('userData')['profile']['gsm_number'] }}" class="form-control" placeholder="gsm number">
                            </div>
                        </div>

                        <div class="form-row" id="officeRow">
                            <div class="form-group col-md-12">
                            <label>Office Number</label>
                            <input type="text" id="office_number" name="office_number" value="{{ session('userData')['profile']['office_number'] }}" class="form-control" placeholder="office number">
                            </div>
                        </div>

                        <div class="form-row" id="occupationRow">
                            <div class="form-group col-md-6">
                            <label class="required" for="occupation">Occupation</label>
                            <select id="occupation" name="occupation" class="form-control" required="required">
                                <option value="">Choose...</option>
                            </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="required" for="sector">Sector</label>
                                <select id="sector" name="sector" class="form-control" required="required">
                                    <option value="">Choose...</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row" id="websiteRow">
                            <div class="form-group col-md-12">
                            <label>Website</label>
                            <input type="text" id="website" name="website" value="{{ session('userData')['profile']['website'] }}" class="form-control" placeholder="website">
                            </div>
                        </div>

                        <div class="form-row" id="contactPersonRow">
                            <div class="form-group col-md-12">
                            <label>Contact Person</label>
                            <input type="text" id="contact_person" name="contact_person" value="{{ session('userData')['profile']['contact_person'] }}" class="form-control" placeholder="">
                            </div>
                        </div>

                        <div class="form-row" id="bankDetailsRow">
                            <div class="form-group col-md-6">
                            <label class="required">Account Number</label>
                            <input type="text" class="form-control" placeholder="Account Number" name="bank_account_number" id="bank_account_number" value="{{ session('userData')['profile']['bank_account_number'] }}" required />
                            </div>
                            <div class="form-group col-md-6">
                                <label for="customer_bank">Bank Name</label>
                                <select id="customer_bank" name="customer_bank" class="form-control" required="required">
                                    <option value="">Choose...</option>
                                </select>
                            </div>
                        </div>

                        @if (strtolower(session('userData')['role']['name']) === 'broker')
                        <div class="form-row" id="agentRow">
                            <div class="form-group col-md-12">
                            <label>Agent Code</label>
                            <input type="text" id="agent_code" name="agent_code" value="{{ session('userData')['profile']['agent_code'] }}" class="form-control" placeholder="agency code or name">
                            </div>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-md-12 text-center">
                                    <button type="button" class="btn btn-primary btn-lg" onclick="Register.updateProfile()">Save Changes</button>
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
    <script src="{{ URL::asset('assets/javascripts/forms/signin.js') }}"></script>
    <script src="{{ URL::asset('assets/javascripts/forms/register.js') }}"></script>
    <script>$(function(){ Register.init(); });</script>
	@stop
@stop