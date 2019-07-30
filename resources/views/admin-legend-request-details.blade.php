@extends('layouts.masterpage')

@section('title', 'Request Details Page')

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
        
            </div>
        </header>
        <div class="panel panel-sign">
                <div class="panel-heading">
                    <i class="fa fa-money fa-2x"></i> Legend Request Details (Status: <span id="transaction_status"></span>)
                </div>
                <div class="panel-body">
                    <form id="legendform" name="legendform">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="vehicleTransactionDetailsId">Transaction ID</label>
                                <input type="text" class="form-control" placeholder="" name="vehicleTransactionDetailsId" id="vehicleTransactionDetailsId" value="" readonly/>
                            </div>
                            <div class="col-md-4">
                                <label for="payment_reference">Payment Reference</label>
                                <input type="text" class="form-control" placeholder="" name="payment_reference" id="payment_reference" value="" readonly/>
                            </div>
                            <div class="col-md-4">
                                <label for="title_id">Title ID</label>
                                <input type="text" class="form-control" placeholder="" name="title_id" id="title_id" value="" required/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="firstname">Firstname</label>
                                <input type="text" class="form-control" placeholder="" name="firstname" id="firstname" value="" required/>
                            </div>
                            <div class="col-md-4">
                                <label for="othernames">Othernames</label>
                                <input type="text" class="form-control" placeholder="" name="othernames" id="othernames" value="" required/>
                            </div>
                            <div class="col-md-4">
                                <label for="lastname">Lastname</label>
                                <input type="text" class="form-control" placeholder="" name="lastname" id="lastname" value="" required/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="address">Address</label>
                                <input type="text" class="form-control" placeholder="" name="address" id="address" value="" required/>
                            </div>
                            <div class="col-md-4">
                                <label for="city">City</label>
                                <input type="text" class="form-control" placeholder="" name="city" id="city" value="" required/>
                            </div>
                            <div class="col-md-4">
                                <label for="state">State</label>
                                <input type="text" class="form-control" placeholder="" name="state" id="state" value="" required/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="email_address">Email address</label>
                                <input type="text" class="form-control" placeholder="" name="email_address" id="email_address" value="" required/>
                            </div>
                            <div class="col-md-4">
                                <label for="gsm_number">GSM Number</label>
                                <input type="text" class="form-control" placeholder="" name="gsm_number" id="gsm_number" value="" required/>
                            </div>
                            <div class="col-md-4">
                                <label for="office_number">Office Number</label>
                                <input type="text" class="form-control" placeholder="" name="office_number" id="office_number" value="" required/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="website">Website</label>
                                <input type="text" class="form-control" placeholder="" name="website" id="website" value="" required/>
                            </div>
                            <div class="col-md-4">
                                <label for="contact_person">Contact Person</label>
                                <input type="text" class="form-control" placeholder="" name="contact_person" id="contact_person" value="" required/>
                            </div>
                            <div class="col-md-4">
                                <label for="date_of_birth">Date of birth</label>
                                <input type="text" class="form-control" placeholder="" name="date_of_birth" id="date_of_birth" value="" required/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="company_reg_num">Company Reg. No</label>
                                <input type="text" class="form-control" placeholder="" name="company_reg_num" id="company_reg_num" value="" required/>
                            </div>
                            <div class="col-md-4">
                                <label for="tin_number">Tin Number</label>
                                <input type="text" class="form-control" placeholder="" name="tin_number" id="tin_number" value="" required/>
                            </div>
                            <div class="col-md-4">
                                <label for="occupation">Occupation</label>
                                <input type="text" class="form-control" placeholder="" name="occupation" id="occupation" value="" required/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="sector">Sector</label>
                                <input type="text" class="form-control" placeholder="" name="company_reg_num" id="company_reg_num" value="" required/>
                            </div>
                            <div class="col-md-4">
                                <label for="client_class">Client class</label>
                                <input type="text" class="form-control" placeholder="" name="client_class" id="client_class" value="" required/>
                            </div>
                            <div class="col-md-4">
                                <label for="risk_class">Risk Class</label>
                                <input type="text" class="form-control" placeholder="" name="risk_class" id="risk_class" value="" required/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="policy_class">Policy Class</label>
                                <input type="text" class="form-control" placeholder="" name="policy_class" id="policy_class" value="" required/>
                            </div>
                            <div class="col-md-4">
                                <label for="cover_type">Cover Type</label>
                                <input type="text" class="form-control" placeholder="" name="cover_type" id="cover_type" value="" required/>
                            </div>
                            <div class="col-md-4">
                                <label for="policy_type">Policy Type</label>
                                <input type="text" class="form-control" placeholder="" name="policy_type" id="policy_type" value="" required/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="vehicle_plate_number">Plate Number</label>
                                <input type="text" class="form-control" placeholder="" name="vehicle_plate_number" id="vehicle_plate_number" value="" required/>
                            </div>
                            <div class="col-md-4">
                                <label for="engine_number">Engine Number</label>
                                <input type="text" class="form-control" placeholder="" name="engine_number" id="engine_number" value="" required/>
                            </div>
                            <div class="col-md-4">
                                <label for="chasis_number">Chasis Number</label>
                                <input type="text" class="form-control" placeholder="" name="chasis_number" id="chasis_number" value="" required/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="model">Model</label>
                                <input type="text" class="form-control" placeholder="" name="model" id="model" value="" required/>
                            </div>
                            <div class="col-md-4">
                                <label for="body">Body</label>
                                <input type="text" class="form-control" placeholder="" name="body" id="body" value="" required/>
                            </div>
                            <div class="col-md-4">
                                <label for="color">Color</label>
                                <input type="text" class="form-control" placeholder="" name="color" id="color" value="" required/>
                            </div>
                        </div> 
                        <div class="row">
                            <div class="col-md-4">
                                <label for="cubic_capacity">Cubic Capacity</label>
                                <input type="text" class="form-control" placeholder="" name="cubic_capacity" id="cubic_capacity" value="" required/>
                            </div>
                            <div class="col-md-4">
                                <label for="number_of_seat">Number of Seat</label>
                                <input type="text" class="form-control" placeholder="" name="number_of_seat" id="number_of_seat" value="" required/>
                            </div>
                            <div class="col-md-4">
                                <label for="year_of_make">Year of Make</label>
                                <input type="text" class="form-control" placeholder="" name="year_of_make" id="year_of_make" value="" required/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="year_of_purchase">Year of Purchase</label>
                                <input type="text" class="form-control" placeholder="" name="year_of_purchase" id="year_of_purchase" value="" required/>
                            </div>
                            <div class="col-md-4">
                                <label for="mode_of_payment">Mode of Payment</label>
                                <input type="text" class="form-control" placeholder="" name="mode_of_payment" id="mode_of_payment" value="" required/>
                            </div>
                            <div class="col-md-4">
                                <label for="currency">Currency</label>
                                <input type="text" class="form-control" placeholder="" name="currency" id="currency" value="" required/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="bank_id">Bank ID</label>
                                <input type="text" class="form-control" placeholder="" name="bank_id" id="bank_id" value="" required/>
                            </div>
                            <div class="col-md-4">
                                <label for="account_number">Account Number</label>
                                <input type="text" class="form-control" placeholder="" name="account_number" id="account_number" value="" required/>
                            </div>
                            <div class="col-md-4">
                                <label for="bvn">BVN</label>
                                <input type="text" class="form-control" placeholder="" name="bvn" id="bvn" value="" required/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="company_bank">Company Bank</label>
                                <input type="text" class="form-control" placeholder="" name="company_bank" id="company_bank" value="" required/>
                            </div>
                            <div class="col-md-4">
                                <label for="effective_date">Effective Date</label>
                                <input type="text" class="form-control" placeholder="" name="effective_date" id="effective_date" value="" required/>
                            </div>
                            <div class="col-md-4">
                                <label for="expiry_date">Expiry Date</label>
                                <input type="text" class="form-control" placeholder="" name="expiry_date" id="expiry_date" value="" required/>
                            </div>
                        </div>      
                    </form>
                </div>
        </div>

    </section>
    @endif

    @section('pagescripts')
    <script src="{{ URL::asset('assets/javascripts/forms/utility.js') }}"></script>
    <script>$(function(){ Utility.init(); });</script>
    <script src="{{ URL::asset('assets/javascripts/forms/legend_request.js') }}"></script>
    <script>$(function(){ LegendRequest.init(); });</script>
	@stop
@stop