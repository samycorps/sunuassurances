@extends('layouts.outerpage')

@section('title', 'SignIn Page')

@section('content')
	<!-- start: page -->
    <section class="body-sign signup">
        <div class="center-sign">
            <a href="/portal" class="logo pull-left">
                <img src="{{ URL::asset('assets/images/sunu_logo_long2.jpg') }}" height="50" alt="SUNU Portal" />
            </a>

            <div class="panel panel-sign">
                <div class="panel-title-sign mt-xl text-right">
                    <h2 class="title text-uppercase text-bold m-none"><i class="fa fa-user"></i> Sign Up</h2>
                </div>
                <div class="panel-body">
                    <form action="" method="post" id="form-register">
                        <input type="hidden" id="user_role" name="user_role" value="{{ $role }}" />
                        <div class="row col-md-12">
                            <p class="form-label"></p>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                            <label class="form-label">Select your registration category</label>
                            <select id="category" name="category" class="form-control" required="required" onchange="Register.onCategoryChange()">
                                <option value="">Choose...</option>
                                <option value="Individual">Individual</option>
                                <option value="Corporate">Corporate</option>
                                @if ($role !== 'agent')
                                <option value="Government">Government</option>
                                @endif
                            </select>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-row hide_elements" id="titleRow">
                            <div class="form-group col-md-6">
                            <label class="form-label">Title</label>
                            <select id="title" name="title" class="form-control" required="required">
                                <option value="">Choose...</option>
                            </select>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-row hide_elements" id="nameRow">
                            <div class="form-group col-md-6">
                            <label class="required">Firstname</label>
                            <input type="text" id="firstname" name="firstname" class="form-control" placeholder="First name" required="required">
                            </div>
                            <div class="form-group col-md-6">
                            <label class="required">Lastname</label>
                            <input type="text" id="lastname" name="lastname" class="form-control" placeholder="Last name" required="required">
                            </div>
                            <div class="form-group col-md-6">
                            <label class="form-label">Othernames</label>
                            <input type="text" id="othernames" name="othernames" class="form-control" placeholder="Other names">
                            </div>
                            <div class="form-group col-md-6">
                            <label class="form-label">Date of Birth</label>
                            <input type="text" id="date_of_birth" name="date_of_birth" class="form-control datepicker">
                            </div>
                        </div>

                        <div class="form-row hide_elements" id="companyRow">
                            <div class="form-group col-md-12">
                            <label class="required">Company Name</label>
                            <input type="text" id="company_name" name="company_name" class="form-control" placeholder="Company name">
                            </div>
                        </div>

                        <div class="form-row hide_elements" id="companyRegRow">
                            <div class="form-group col-md-12">
                            <label class="required">Company Reg. #</label>
                            <input type="text" id="company_reg_num" name="company_reg_num" class="form-control" placeholder="N/A">
                            </div>
                        </div>

                        <div class="form-row hide_elements" id="emailRow">
                            <div class="form-group col-md-12">
                            <label class="required">Email <span class="form-span">(kindly ensure you enter correct email address in order to receive policy related alerts)</span></label>
                            <input type="email" id="email_address" name="email_address" class="form-control" placeholder="Email" required="required">
                            </div>
                        </div>
                        
                        <div class="form-row hide_elements" id="streetRow">
                            <div class="form-group col-md-12">
                            <label class="required">Street Address</label>
                            <input type="text" id="street_address" name="street_address" class="form-control" placeholder="street address ..." required="required">
                            </div>
                        </div>

                        <div class="form-row hide_elements" id="addressRow">
                            <div class="form-group col-md-6">
                            <label class="required">City/Town</label>
                            <select id="city" name="city" class="form-control" required="required">
                                <option value="">Choose...</option>
                            </select>
                            </div>
                            <div class="form-group col-md-6">
                            <label class="form-label">Local Govt. Area</label>
                            <input type="text" id="lga" name="lga" class="form-control" placeholder="lga">
                            </div>
                            <div class="form-group col-md-6">
                            <label class="required">State/Region</label>
                            <select id="state" name="state" class="form-control" required="required">
                                    <option value="">Choose...</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row hide_elements" id="tinRow">
                            <div class="form-group col-md-6">
                            <label>TIN Number</label>
                            <input type="number" id="tin_number" name="tin_number" class="form-control" placeholder="tin number" required="required">
                            </div>
                        </div>

                        <div class="form-row hide_elements" id="gsmRow">
                            <div class="form-group col-md-12">
                            <label class="form-label">GSM Number <span class="form-span">(kindly ensure you enter correct phone number in order to receive policy related alerts)</span></label>
                            <input type="number" id="gsm_number" name="gsm_number" class="form-control" placeholder="gsm number" required="required">
                            </div>
                        </div>

                        <div class="form-row hide_elements" id="officeRow">
                            <div class="form-group col-md-12">
                            <label>Office Number</label>
                            <input type="number" id="office_number" name="office_number" class="form-control" placeholder="office number">
                            </div>
                        </div>

                        <div class="form-row hide_elements" id="occupationRow">
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

                        <!-- <div class="form-row hide_elements" id="websiteRow">
                            <div class="form-group col-md-12">
                            <label>Website</label>
                            <input type="text" id="website" name="website" class="form-control" placeholder="website">
                            </div>
                        </div> -->

                        <div class="form-row hide_elements" id="contactPersonRow">
                            <div class="form-group col-md-12">
                            <label class="form-label">Contact Person</label>
                            <input type="text" id="contact_person" name="contact_person" class="form-control" placeholder="">
                            </div>
                        </div>

                        <!-- <div class="form-row hide_elements" id="bankDetailsRow">
                            <div class="form-group col-md-6">
                            <label class="required">Account Number</label>
                            <input type="text" class="form-control" placeholder="Account Number" name="bank_account_number" id="bank_account_number" maxlength="10" required />
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label" for="customer_bank">Bank Name</label>
                                <select id="customer_bank" name="customer_bank" class="form-control" required="required">
                                    <option value="">Choose...</option>
                                </select>
                            </div>
                        </div> -->

                        @if ($role === 'agent')
                        <div class="form-row" id="agentRow">
                            <div class="form-group col-md-12">
                            <label>Agent Code</label>
                            <input type="text" id="agent_code" name="agent_code" class="form-control" placeholder="agency code or name">
                            </div>
                        </div>
                        @endif

                        <div class="row col-md-12">
                            <hr/>
                            <h4>Preferred SignIn Credentials</h4>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                            <label class="required">Username</label>
                            <input type="text" id="username" name="username" class="form-control" placeholder="Username" required="required">
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                            <label class="required">Password</label>
                            <input type="password" id="userpassword" name="userpassword" class="form-control" placeholder="password" required="required">
                            </div>
                            <div class="form-group col-md-6">
                            <label class="required">Confirm Password</label>
                            <input type="password" id="confirmpassword" name="confirmpassword" class="form-control" placeholder="password" required="required">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-primary btn-lg">Sign Up</button>
                                <!-- <button type="submit" class="btn btn-primary btn-block btn-lg  mt-lg">Sign Up</button> -->
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="alert-message">
                <p class="alert-message-text"></p>
            </div>
            <p class="text-center text-muted mt-md mb-md">&copy; Copyright 2018. SUNU Assurance Nigeria Plc. All Rights Reserved.</p>
        </div>
    </section>
    <!-- end: page -->

@section('pagescripts')
    <script src="{{ URL::asset('assets/javascripts/forms/utility.js') }}"></script>
    <script src="{{ URL::asset('assets/javascripts/forms/signin.js') }}"></script>
    <script src="{{ URL::asset('assets/javascripts/forms/register.js') }}"></script>
    <script>$(function(){ Register.init(); });</script>
    
@stop