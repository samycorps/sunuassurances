@extends('layouts.outerpage')

@section('title', 'SignIn Page')

@section('content')
	<!-- start: page -->
    <section class="body-sign">
        <div class="center-sign">
            <a href="/" class="logo pull-left">
                <img src="assets/images/sunu_logo_long2.jpg" height="50" alt="SUNU Portal" />
            </a>

            <div class="panel panel-sign">
                <div class="panel-title-sign mt-xl text-right">
                    <h2 class="title text-uppercase text-bold m-none"><i class="fa fa-user"></i> Sign In</h2>
                </div>
                <div class="panel-body">
                    <form method="post" id="form-signin">
                        <div class="form-group mb-lg">
                            <label>Username</label>
                            <div class="input-group input-group-icon">
                                <input id="username" name="username" type="text" class="form-control input-lg" />
                                <span class="input-group-addon">
                                    <span class="icon icon-lg">
                                        <i class="fa fa-user"></i>
                                    </span>
                                </span>
                            </div>
                        </div>

                        <div class="form-group mb-lg">
                            <div class="clearfix">
                                <label class="pull-left">Password</label>
                                <a href="pages-recover-password.html" class="pull-right">Lost Password?</a>
                            </div>
                            <div class="input-group input-group-icon">
                                <input id="userpassword" name="userpassword" type="password" class="form-control input-lg" />
                                <span class="input-group-addon">
                                    <span class="icon icon-lg">
                                        <i class="fa fa-lock"></i>
                                    </span>
                                </span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-8">
                                <div class="checkbox-custom checkbox-default">
                                    <input id="RememberMe" name="rememberme" type="checkbox"/>
                                    <label for="RememberMe">Remember Me</label>
                                </div>
                            </div>
                            <div class="col-sm-4 text-right">
                                <button type="submit" class="btn btn-primary hidden-xs">Sign In</button>
                                <button type="submit" class="btn btn-primary btn-block btn-lg visible-xs mt-lg">Sign In</button>
                            </div>
                        </div>

                        <div class="alert-message">
                            <p class="alert-message-text"></p>
                        </div>

                        <p class="text-center">Don't have an account yet? Sign Up!</p>

                        <div class="mb-xs text-center">
                            <div class="item">
                            <a href="signup/direct"><img class="signup_images" src="assets/images/icon_direct.jpg"></a>
                            <span class="caption">Direct</span>
                            </div>
                            <div class="item">
                            <a href="signup/agent"><img class="signup_images" src="assets/images/icon_brokers.jpg"></a>
                            <span class="caption">Agent/Broker</span>
                            </div>
                        </div>


                    </form>
                </div>
            </div>

            <p class="text-center text-muted mt-md mb-md">&copy; Copyright 2018. SUNU Assurance Nigeria Plc. All Rights Reserved.</p>
        </div>
    </section>
    <!-- end: page -->

    @section('pagescripts')
    <script src="{{ URL::asset('assets/javascripts/forms/signin.js') }}"></script>
    <script>$(function(){ SignIn.init(); });</script>

@stop