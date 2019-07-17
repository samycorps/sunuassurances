@extends('layouts.masterpage')

@section('title', 'Home Page')

@section('pagestyles')
<link rel="stylesheet" href="{{ URL::asset('assets/stylesheets/home.css') }}">
<link rel="stylesheet" href="{{ URL::asset('assets/vendor/jquery-datatables-bs3/assets/css/datatables.css') }}" >
@stop

@section('content')
    @if (session('userData'))
    <section role="main" class="content-body">
        <header class="page-header">
            <h2>User Claims </h2>
            <input type="hidden" id="role_name" value="{{ strtolower(session('userData')['role']['name']) }}" />
            <input type="hidden" id="profile_details" name="profile_details" value="{{json_encode(session('userData')['profile'])}}" />
            <input type="hidden" id="user_details" name="user_details" value="{{json_encode(session('userData')['user'])}}" />
            <input type="hidden" id="profile_id" name="profile_id" value="{{ session('userData')['profile']['id'] }}" />
            <div class="right-wrapper pull-right">
                <ol class="breadcrumbs">
                    <li>
                        <a href="/portal/home">
                            <i class="fa fa-home"></i>
                        </a>
                    </li>
                    <li><span>Agent Claims</span></li>
                </ol>
        
                <!-- <a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a> -->
            </div>
        </header>

        <div class="panel panel-sign">
                <div class="panel-heading">
                    <i class="fa fa-money fa-2x"></i> Claims Information
                </div>
                <div class="panel-body">
                    <div class="tabs">
                        <ul class="nav nav-tabs tabs-primary">
                            <li class="active">
                                <a href="#submit-claim" data-toggle="tab">Submit a Claim</a>
                            </li>
                            <li>
                                <a href="#view-claims" data-toggle="tab">View Claims</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div id="submit-claim" class="tab-pane active">
                                <section class="card">
                                    <header class="card-header">
                                        <div class="card-actions">
                                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                                        </div>
                                    </header>
                                    <div class="card-body">
                                    <div id="rootwizard">
                                        <div class="container">
                                            <ul>
                                                <li><a href="#tab1" data-toggle="tab">Client Details</a></li>
                                                <li><a href="#tab2" data-toggle="tab">Vehicle Registration</a></li>
                                                <li><a href="#tab3" data-toggle="tab">Driver Details</a></li>
                                                <li><a href="#tab4" data-toggle="tab">Incident Details</a></li>
                                                <li><a href="#tab5" data-toggle="tab">Vehicle Details</a></li>
                                                <li><a href="#tab6" data-toggle="tab">Accident Details</a></li>
                                                <li><a href="#tab7" data-toggle="tab">Upload Picture</a></li>
                                            </ul>
                                        </div>
                                        <div class="tab-content">
                                            <div class="tab-pane" id="tab1">
                                                <form id="tab1form" name="tab1form">
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
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <h5>Client details</h5>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label class="form-label" for="policy_number">Policy number</label>
                                                            <input type="text" class="form-control" placeholder="Policy Number" name="policy_number" id="policy_number" value="" required/>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <label class="form-label" for="firstname">Firstname</label>
                                                            <input type="text" class="form-control" placeholder="Firstname" name="firstname" id="firstname" value="" required/>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label" for="lastname">Lastname</label>
                                                            <input type="text" class="form-control" placeholder="Lastname" name="lastname" id="lastname" value="" required/>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label" for="othername">Othername</label>
                                                            <input type="text" class="form-control" placeholder="Othername" name="othername" id="othername" value="" required/>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <label class="form-label" for="address">Address</label>
                                                            <input type="text" class="form-control" placeholder="Address" name="address" id="address" value="" required/>
                                                        </div>
                                                        <div class="col-md-4">
                                                        <label class="form-label" for="city">City</label>
                                                            <select id="city" name="city" class="form-control" required="required">
												            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <label class="form-label" for="state">State</label>
                                                            <select id="state" name="state" class="form-control" required="required">
												            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <label class="form-label" for="phone">Phone No</label>
                                                            <input type="number" class="form-control" placeholder="Phone" name="phone" id="phone" value="" required/>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label" for="email_address">Email</label>
                                                            <input type="text" class="form-control" placeholder="Email" name="email_address" id="email_address" value="" required/>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label" for="date_of_birth">Date of Birth</label>
                                                            <input type="text" id="date_of_birth" name="date_of_birth" class="form-control datepicker" required>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <label class="form-label" for="occupation">Occupation</label>
                                                            <select id="occupation" name="occupation" class="form-control" required="required">
												            </select>
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
                                                        <div class="col-md-6">
                                                            <h5>Vehicle registration details</h5>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <label class="form-label" for="vehicle_make_model">Make/Model</label>
                                                            <select id="vehicle_make_model" name="vehicle_make_model" class="form-control" required="required">
                                                            <option value="">Choose...</option>
                                                        </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label" for="vehicle_body">Body</label>
                                                            <select id="vehicle_body" name="vehicle_body" class="form-control" required="required">
                                                                <option value="">Choose...</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label" for="vehicle_color">Color</label>
                                                            <select id="vehicle_color" name="vehicle_color" class="form-control" required="required">
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <label class="form-label" for="vehicle_reg_num">Registration No</label>
                                                            <input type="text" class="form-control" placeholder="Registration Number" name="vehicle_reg_num" id="vehicle_reg_num" required />
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label" for="vehicle_engine_number">Engine Number</label>
                                                            <input type="text" class="form-control" placeholder="Engine Number" name="vehicle_engine_number" id="vehicle_engine_number" required />
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label" for="vehicle_chasis_number">Chasis Number</label>
                                                            <input type="text" class="form-control" placeholder="Chasis Number" name="vehicle_chasis_number" id="vehicle_chasis_number" required />
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="tab-pane" id="tab3">
                                                <form id="tab3form" name="tab3form">
                                                    <div class="row">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <h5>Driver details</h5>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label" for="driver_fullname">Fullname</label>
                                                            <input type="text" class="form-control" placeholder="Fullname" name="driver_fullname" id="driver_fullname" required />
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label" for="driver_age">Age</label>
                                                            <input type="number" class="form-control" placeholder="Age" name="driver_age" id="driver_age" required />
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <label class="form-label" for="driving_license_in_force">Is Driving Licence in force ?</label>
                                                            <select id="driving_license_in_force" name="driving_license_in_force" class="form-control" required="required">
                                                            <option value="">Choose...</option>
                                                            <option value="yes">YES</option>
                                                            <option value="no">NO</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label" for="driving_license_category">If Yes, which category?</label>
                                                            <!-- <select id="driving_license_category" name="driving_license_category" class="form-control" required="required">
                                                            <option value="">Choose...</option>
                                                            </select> -->
                                                            <input type="text" class="form-control" placeholder="Driver's License Category" name="driving_license_category" id="driving_license_category" />
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <label class="form-label" for="driving_license_number">Driver's License No.</label>
                                                            <input type="text" class="form-control" placeholder="Driver's License No" name="driving_license_number" id="driving_license_number" required />
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label" for="driving_license_endorsed">	Has it been endorsed ?</label>
                                                            <select id="driving_license_endorsed" name="driving_license_endorsed" class="form-control" required="required">
                                                            <option value="yes">YES</option>
                                                            <option value="no">NO</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <label class="form-label" for="date_of_issue">Date of Issue</label>
                                                            <input type="text" class="form-control datepicker" placeholder="Date of Issue" name="date_of_issue" id="date_of_issue" required />
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label" for="date_of_expiry">Date of Expiry</label>
                                                            <input type="text" class="form-control datepicker2" placeholder="Date of expiry" name="date_of_expiry" id="date_of_expiry" required />
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label" for="place_of_issue">Place of Issue</label>
                                                            <input type="text" class="form-control" placeholder="Place of issue" name="place_of_issue" id="place_of_issue" required />
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                        <label class="form-label" for="learners_permit">Is it a Learner's Permit ?</label>
                                                            <select id="learners_permit" name="learners_permit" class="form-control" required="required">
                                                            <option value="">Choose...</option>
                                                            <option value="yes">YES</option>
                                                            <option value="no">NO</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label" for="learners_permit_number">If Yes, Number</label>
                                                            <input type="text" class="form-control" placeholder="Permit Number" name="learners_permit_number" id="learners_permit_number" />
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label" for="learners_permit_period">Period</label>
                                                            <input type="text" class="form-control" placeholder="Period" name="learners_permit_period" id="learners_permit_period" />
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="tab-pane" id="tab4">
                                                <form id="tab4form" name="tab4form">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <h5>Particulars of theft / fire incident</h5>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <label class="form-label"  for="discover_loss_fullname">Who discovered this loss ?</label>
                                                            <input type="text" class="form-control" placeholder="" name="discover_loss_fullname" id="discover_loss_fullname" required />
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label" for="discover_loss_date">Date</label>
                                                            <input type="text" class="form-control datepicker" placeholder="" name="discover_loss_date" id="discover_loss_date" required />
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label" for="discover_loss_date">Time</label>
                                                            <input type="text" class="form-control" placeholder="" name="discover_loss_date" id="discover_loss_date" required />
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <label class="form-label" for="persons_count_insured_vehicle">No. of persons in insured vehicle</label>
                                                            <input type="number" class="form-control" placeholder="" name="persons_count_insured_vehicle" id="persons_count_insured_vehicle" required />
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label" for="persons_count_other_vehicle">No. of persons in other vehicle</label>
                                                            <input type="number" class="form-control" placeholder="" name="persons_count_other_vehicle" id="persons_count_other_vehicle" required />
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label class="form-label" for="police_report_station_address">Address of Police Report Station</label>
                                                            <input type="text" class="form-control" placeholder="" name="police_report_station_address" id="police_report_station_address" required />
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label" for="police_report_statement">Full Statement of Theft/Fire Incident</label>
                                                            <textarea id="police_report_statement" name="police_report_statement" class="form-control" rows="3" required="required"></textarea>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <!-- damage to insured vehicle -->
                                            <div class="tab-pane" id="tab5">
                                                <form id="tab5form" name="tab5form">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <h5>Damage to insured vehicle</h5>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label class="form-label" for="damaged_parts_report">Full details of damaged Parts</label>
                                                            <textarea id="damaged_parts_report" name="damaged_parts_report" class="form-control" rows="3" required="required"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <label class="form-label" for="present_vehicle_location">Present Vehicle Location</label>
                                                            <input type="text" class="form-control" placeholder="" name="present_vehicle_location" id="present_vehicle_location" required />
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label" for="repairs_estimate">Rough Estimate of Repairs</label>
                                                            <input type="text" class="form-control" placeholder="" name="repairs_estimate" id="repairs_estimate" pattern="^\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" required />
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label" for="repairer_name">Repairer's name</label>
                                                            <input type="text" class="form-control" placeholder="" name="repairer_name" id="repairer_name" required />
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label class="form-label" for="repairer_address">Repairer's Full Address</label>
                                                            <input type="text" class="form-control" placeholder="" name="repairer_address" id="repairer_address" required />
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label" for="damaged_parts_inventory">Inventory of damaged parts	</label>
                                                            <textarea id="damaged_parts_inventory" name="damaged_parts_inventory" class="form-control" rows="3" required="required"></textarea>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>

                                            <!-- third parties involved in the accident -->
                                            <div class="tab-pane" id="tab6">
                                                <form id="tab6form" name="tab6form">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <h5>Third parties involved in the accident</h5>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label class="form-label" for="third_party_fullname">Fullname</label>
                                                            <input type="text" class="form-control" placeholder="" name="third_party_fullname" id="third_party_fullname" required />
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label" for="third_party_address">Address</label>
                                                            <input type="text" class="form-control" placeholder="" name="third_party_address" id="third_party_address" required />
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <label class="form-label" for="third_party_injury_type">Type of property/Injury</label>
                                                            <input type="text" class="form-control" placeholder="" name="third_party_injury_type" id="third_party_injury_type" required />
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label" for="third_party_vehicle_make">If Vehicle, Make</label>
                                                            <select id="third_party_vehicle_make" name="third_party_vehicle_make" class="form-control" required="required">
                                                                <option value="">Choose...</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label" for="third_party_reg_num">Registration No</label>
                                                            <input type="text" class="form-control" placeholder="" name="third_party_reg_num" id="third_party_reg_num" required />
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <label class="form-label" for="third_party_year_of_make">Year of Make</label>
                                                            <select id="third_party_year_of_make" name="third_party_year_of_make" class="form-control" required="required">
                                                                <option value="">Choose...</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label" for="third_party_vehicle_location">Present Location of Vehicle</label>
                                                            <input type="text" class="form-control" placeholder="" name="third_party_vehicle_location" id="third_party_vehicle_location" required />
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label" for="third_party_owner_insured">Is Owner insured ?</label>
                                                            <select id="third_party_owner_insured" name="third_party_owner_insured" class="form-control" required="required">
                                                                <option value="">Choose...</option>
                                                                <option value="yes">YES</option>
                                                                <option value="no">NO</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <label class="form-label" for="third_party_policy_number">If Yes, Policy No</label>
                                                            <input type="text" class="form-control" placeholder="" name="third_party_policy_number" id="third_party_policy_number" />
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label" for="third_party_insurer_name">Name of Insurer</label>
                                                            <input type="text" class="form-control" placeholder="" name="third_party_insurer_name" id="third_party_insurer_name" />
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label" for="third_party_insurer_address">Address of Insurer</label>
                                                            <input type="text" class="form-control" placeholder="" name="third_party_insurer_address" id="third_party_insurer_address" />
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>

                                            <!-- upload picture evidences -->
                                            <div class="tab-pane" id="tab7">
                                                <h5>Note: Upload picture evidences, please select one picture at a time.</h5>
                                                <form action="{{ url('api/saveImages/'.session('userData')['profile']['id']) }}"
                                                    class="dropzone"
                                                    id="my-awesome-dropzone">
                                                </form>
                                            </div>
                                            <ul class="pager wizard">
                                                <li class="previous first" style="display:none;"><a href="#">First</a></li>
                                                <li class="previous"><a href="#">Previous</a></li>
                                                <li class="next last" style="display:none;"><a href="#">Last</a></li>
                                                <li class="next"><a href="#">Next</a></li>
                                            </ul>
                                            <div class="alert-message">
                                                <p class="alert-message-text"></p>
                                            </div>
                                            <div class="row hide_elements claim_btn" id="submit_claim_section">
                                                <div class="col-md-12">
                                                    <button type="button" id="submit_claim_btn" class="btn btn-primary">Submit Claim</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                    </section>
                            </div>
                            <div id="view-claims" class="tab-pane">
                                <section class="card">
                                    <header class="card-header">
                                        <div class="card-actions">
                                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                                        </div>
                                    </header>
                                    <div class="card-body">
                                            @include('client-claims-view')
                                    </div>
                                    </section>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </section>
    @endif

    @section('pagescripts')
    <!-- Specific Page Vendor -->
	<script src="{{ URL::asset('assets/vendor/jquery-datatables/media/js/jquery.dataTables.js') }}"></script>
	<script src="{{ URL::asset('assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js') }}"></script>
	<script src="{{ URL::asset('assets/vendor/jquery-datatables-bs3/assets/js/datatables.js') }}"></script>
    <script src="{{ URL::asset('assets/javascripts/tables/examples.datatables.default.js') }}"></script>
    <script src="{{ URL::asset('assets/javascripts/tables/examples.datatables.row.with.details.js') }}"></script>
    <script src="{{ URL::asset('assets/javascripts/tables/examples.datatables.tabletools.js') }}"></script>
    <script src="{{ URL::asset('assets/vendor/dropzone/dropzone.js') }}"></script>
    <script src="{{ URL::asset('assets/vendor/jquery-serialize-json/jquery.serializejson.js') }}"></script>
    <script src="{{ URL::asset('assets/vendor/bootstrap-typeahead/bootstrap3-typeahead.min.js') }}"></script>
    <script src="{{ URL::asset('assets/javascripts/forms/utility.js') }}"></script>
    <script>$(function(){ Utility.init(); });</script>
    <script src="{{ URL::asset('assets/javascripts/forms/payment.js') }}"></script>
    <script src="{{ URL::asset('assets/javascripts/forms/claims.js') }}"></script>
    <script>$(function(){ Claim.init(); });</script>
	@stop
@stop