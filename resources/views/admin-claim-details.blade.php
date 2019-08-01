@extends('layouts.masterpage')

@section('title', 'Claims Details Page')

@section('pagestyles')
<link rel="stylesheet" href="{{ URL::asset('assets/stylesheets/home.css') }}" />
@stop

@section('content')
    @if (session('userData'))
    <section role="main" class="content-body">
        <header class="page-header">
            <h2>Dashboard</h2>
            <input type="hidden" id="user_id" name="user_id" value="{{ session('userData')['user']['id'] }}" />
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
                    <i class="fa fa-money fa-2x"></i> Claims Details (Status: <span id="claim_status"></span>)
                </div>
                <div class="panel-body">
                    <form id="claimform" name="claimform">
                        <div class="row col-md-12">
                            <p class="claim-view-title">Client Details </p>
                            <hr/>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="vehicleTransactionDetailsId">Claim No</label>
                                <label id="claim_no" class="form-control"></label>
                            </div>
                            <div class="col-md-4">
                                <label for="policy_no">Policy No</label>
                                <label id="policy_no" class="form-control"></label>
                            </div>
                            <div class="col-md-4">
                                <label for="registration_no">Registration No</label>
                                <label id="registration_no" class="form-control"></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="firstname">Firstname</label>
                                <label id="firstname" class="form-control"></label>
                            </div>
                            <div class="col-md-4">
                                <label for="othernames">Othernames</label>
                                <label id="othernames" class="form-control"></label>
                            </div>
                            <div class="col-md-4">
                                <label for="lastname">Lastname</label>
                                <label id="lastname" class="form-control"></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="address">Address</label>
                                <label id="address" class="form-control"></label>
                            </div>
                            <div class="col-md-4">
                                <label for="city">City</label>
                                <label id="city" class="form-control"></label>
                            </div>
                            <div class="col-md-4">
                                <label for="state">State</label>
                                <label id="state" class="form-control"></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="email_address">Email address</label>
                                <label id="email_address" class="form-control"></label>
                            </div>
                            <div class="col-md-4">
                                <label for="phone">Phone Number</label>
                                <label id="phone" class="form-control"></label>
                            </div>
                            <div class="col-md-4">
                                <label for="date_of_birth">Office Number</label>
                                <label id="date_of_birth" class="form-control"></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="occupation">Occupation</label>
                                <label id="occupation" class="form-control"></label>
                            </div>
                            <div class="col-md-4">
                                
                            </div>
                            <div class="col-md-4">
                                
                            </div>
                        </div>
                        <div class="row col-md-12">
                            <p class="claim-view-title">Vehicle Registration Details </p>
                            <hr/>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="vehicle_make_model">Make/Model</label>
                                <label id="vehicle_make_model" class="form-control"></label>
                            </div>
                            <div class="col-md-4">
                                <label for="vehicle_body">Body</label>
                                <label id="vehicle_body" class="form-control"></label>
                            </div>
                            <div class="col-md-4">
                                <label for="vehicle_color">Color</label>
                                <label id="vehicle_color" class="form-control"></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="vehicle_engine_number">Engine Number</label>
                                <label id="vehicle_engine_number" class="form-control"></label>
                            </div>
                            <div class="col-md-4">
                                <label for="vehicle_chasis_number">Chasis Number</label>
                                <label id="vehicle_chasis_number" class="form-control"></label>
                            </div>
                            <div class="col-md-4">
                                
                            </div>
                        </div>
                        <div class="row col-md-12">
                            <p class="claim-view-title">Driver Details </p>
                            <hr/>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="driver_fullname">Driver Fullname</label>
                                <label id="driver_fullname" class="form-control"></label>
                            </div>
                            <div class="col-md-4">
                                <label for="driver_age">Driver Age</label>
                                <label id="driver_age" class="form-control"></label>
                            </div>
                            <div class="col-md-4">
                                <label for="driving_license_in_force">Driver License in force ?</label>
                                <label id="driving_license_in_force" class="form-control"></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="driving_license_category">Driver License Category</label>
                                <label id="driving_license_category" class="form-control"></label>
                            </div>
                            <div class="col-md-4">
                                <label for="driving_license_number">Driver License Number</label>
                                <label id="driving_license_number" class="form-control"></label>
                            </div>
                            <div class="col-md-4">
                                <label for="driving_license_endorsed">Driver License Endorsed ?</label>
                                <label id="driving_license_endorsed" class="form-control"></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="date_of_issue">Date of issue</label>
                                <label id="date_of_issue" class="form-control"></label>
                            </div>
                            <div class="col-md-4">
                                <label for="date_of_expiry">Date of Expiry</label>
                                <label id="date_of_expiry" class="form-control"></label>
                            </div>
                            <div class="col-md-4">
                                <label for="place_of_issue">Place of issue</label>
                                <label id="place_of_issue" class="form-control"></label>
                            </div>
                        </div> 
                        <div class="row">
                            <div class="col-md-4">
                                <label for="learners_permit">Learners permit ?</label>
                                <label id="learners_permit" class="form-control"></label>
                            </div>
                            <div class="col-md-4">
                                <label for="learners_permit_number">Learners permit number</label>
                                <label id="learners_permit_number" class="form-control"></label>
                            </div>
                            <div class="col-md-4">
                                <label for="learners_permit_period">Learners permit period</label>
                                <label id="learners_permit_period" class="form-control"></label>
                            </div>
                        </div>
                        <div class="row col-md-12">
                            <p class="claim-view-title">Incident Details </p>
                            <hr/>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="discover_loss_fullname">Who discovered this loss </label>
                                <label id="discover_loss_fullname" class="form-control"></label>
                            </div>
                            <div class="col-md-4">
                                <label for="discover_loss_date">Date </label>
                                <label id="discover_loss_date" class="form-control"></label>
                            </div>
                            <div class="col-md-4">
                                <label for="persons_count_insured_vehicle">No. of persons in insured vehicle </label>
                                <label id="persons_count_insured_vehicle" class="form-control"></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="persons_count_other_vehicle">No. of persons in other vehicle </label>
                                <label id="persons_count_other_vehicle" class="form-control"></label>
                            </div>
                            <div class="col-md-4">
                                <label for="police_report_station_address">Address of Police Report Station </label>
                                <label id="police_report_station_address" class="form-control"></label>
                            </div>
                            <div class="col-md-4">
                                <label for="police_report_statement">Full Statement of Theft/Fire Incident </label>
                                <textarea id="police_report_statement" class="form-control" readonly="readonly"></textarea>
                            </div>
                        </div>  
                        <div class="row col-md-12">
                            <p class="claim-view-title">Vehicle Details </p>
                            <hr/>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="damaged_parts_report">Damaged Parts Report</label>
                                <textarea id="damaged_parts_report" class="form-control" readonly="readonly"></textarea>
                            </div>
                            <div class="col-md-4">
                                <label for="present_vehicle_location">Present Vehicle Location</label>
                                <label id="present_vehicle_location" class="form-control"></label>
                            </div>
                            <div class="col-md-4">
                                <label for="repairs_estimate">Repairs Estimate</label>
                                <label id="repairs_estimate" class="form-control"></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="repairer_name">Repairer Name</label>
                                <label id="repairer_name" class="form-control"></label>
                            </div>
                            <div class="col-md-4">
                                <label for="repairer_address">Repairer Address</label>
                                <label id="repairer_address" class="form-control"></label>
                            </div>
                            <div class="col-md-4">
                                <label for="damaged_parts_inventory">Damaged Parts Inventory</label>
                                <textarea id="repairer_address" class="form-control" readonly="readonly"></textarea>
                            </div>
                        </div> 
                        <div class="row col-md-12">
                            <p class="claim-view-title">Accident Details </p>
                            <h5>Third parties involved in the accident</h5>
                            <hr/>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="third_party_fullname">Fullname</label>
                                <label id="third_party_fullname" class="form-control"></label>
                            </div>
                            <div class="col-md-4">
                                <label for="third_party_address">Address</label>
                                <label id="third_party_address" class="form-control"></label>
                            </div>
                            <div class="col-md-4">
                                <label for="third_party_injury_type">Injury Type</label>
                                <label id="third_party_injury_type" class="form-control"></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="third_party_vehicle_make">Vehicle Make</label>
                                <label id="third_party_vehicle_make" class="form-control"></label>
                            </div>
                            <div class="col-md-4">
                                <label for="third_party_reg_num">Registration Number</label>
                                <label id="third_party_reg_num" class="form-control"></label>
                            </div>
                            <div class="col-md-4">
                                <label for="third_party_year_of_make">Year of Make</label>
                                <label id="third_party_year_of_make" class="form-control"></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="third_party_vehicle_location">Vehicle Location</label>
                                <label id="third_party_vehicle_location" class="form-control"></label>
                            </div>
                            <div class="col-md-4">
                                <label for="third_party_owner_insured">Is Owner insured ?</label>
                                <label id="third_party_owner_insured" class="form-control"></label>
                            </div>
                            <div class="col-md-4">
                                <label for="third_party_policy_number">Policy Number</label>
                                <label id="third_party_policy_number" class="form-control"></label>
                            </div>
                        </div> 
                        <div class="row">
                            <div class="col-md-4">
                                <label for="third_party_insurer_name">Insurer Name</label>
                                <label id="third_party_insurer_name" class="form-control"></label>
                            </div>
                            <div class="col-md-4">
                                <label for="third_party_insurer_address">Insurer Address</label>
                                <label id="third_party_insurer_address" class="form-control"></label>
                            </div>
                            <div class="col-md-4">
                                
                            </div>
                        </div>
                        <div class="row col-md-12">
                            <p class="claim-view-title">Pictures Uploaded </p>
                            <hr/>
                        </div>
                        <div class="row" id="accident_image_row">
                        </div>
                        <div class="row col-md-12">
                            <p class="claim-view-title">Admin Section Details </p>
                            <h5>Change claim status and notes</h5>
                            <hr/>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label" for="claim_status_select">Claim Status</label>
                                <select id="claim_status_select" name="claim_status_select" class="form-control" >
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-10">
                                <label class="form-label" for="claim_status_notes">Claim Status Notes</label>
                                <textarea id="claim_status_notes" name="claim_status_notes" class="form-control claim-notes">
                                </textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-10">
                                <button type="button" id="update_claim_btn" class="btn btn-primary btn-claim">Update Claim</button>
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
    <script src="{{ URL::asset('assets/javascripts/forms/utility.js') }}"></script>
    <script>$(function(){ Utility.init(); });</script>
    <script src="{{ URL::asset('assets/javascripts/forms/claims.js') }}"></script>
    <script>$(function(){ AdminClaim.init(); });</script>
	@stop
@stop