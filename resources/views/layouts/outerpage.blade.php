<!doctype html>
<html class="fixed">
	<head>

		<!-- Basic -->
		<meta charset="UTF-8">

		<meta name="keywords" content="SUNU Assurances Nigeria - Insurance Portal" />
		<meta name="description" content="SUNU Assurances Nigeria - Insurance Portal">
		<meta name="author" content="SUNU">

		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

		<!-- Web Fonts  -->
		<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

		<!-- Vendor CSS -->
		<link rel="stylesheet" href="{{ URL::asset('assets/vendor/bootstrap/css/bootstrap.css') }}" />
		<link rel="stylesheet" href="{{ URL::asset('assets/vendor/font-awesome/css/font-awesome.css') }}" />
		<link rel="stylesheet" href="{{ URL::asset('assets/vendor/magnific-popup/magnific-popup.css') }}" />
		<link rel="stylesheet" href="{{ URL::asset('assets/vendor/bootstrap-datepicker/css/datepicker3.css') }}" />

		<!-- Theme CSS -->
		<link rel="stylesheet" href="{{ URL::asset('assets/stylesheets/theme.css') }}" />

		<!-- Skin CSS -->
		<link rel="stylesheet" href="{{ URL::asset('assets/stylesheets/skins/default.css') }}" />

		<!-- Theme Custom CSS -->
		<link rel="stylesheet" href="{{ URL::asset('assets/stylesheets/theme-custom.css') }}">

		<!-- Head Libs -->
		<script src="{{ URL::asset('assets/vendor/modernizr/modernizr.js') }}"></script>
        <title>Sunu Assurance Nigeria - @yield('title')</title>
	</head>
    <body>
        <div id="content">
            @yield('content')
        </div>
        <!-- Vendor -->
    		<script src="{{ URL::asset('assets/vendor/jquery/jquery.js') }}"></script>
            <script src="{{ URL::asset('assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js') }}"></script>
            <script src="{{ URL::asset('assets/vendor/bootstrap/js/bootstrap.js') }}"></script>
            <script src="{{ URL::asset('assets/vendor/nanoscroller/nanoscroller.js') }}"></script>
            <script src="{{ URL::asset('assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
            <script src="{{ URL::asset('assets/vendor/magnific-popup/magnific-popup.js') }}"></script>
            <script src="{{ URL::asset('assets/vendor/jquery-placeholder/jquery.placeholder.js') }}"></script>
			<script src="{{ URL::asset('assets/vendor/jquery-validation/jquery.validate.js') }}"></script>
			<script src="{{ URL::asset('assets/vendor/lodash/lodash.js') }}"></script>
            
            <!-- Theme Base, Components and Settings -->
            <script src="{{ URL::asset('assets/javascripts/theme.js') }}"></script>
            
            <!-- Theme Custom -->
            <script src="{{ URL::asset('assets/javascripts/theme.custom.js') }}"></script>
            
            <!-- Theme Initialization Files -->
            <script src="{{ URL::asset('assets/javascripts/theme.init.js') }}"></script>

			<!-- Specific page scripts -->
			<script src="{{ URL::asset('assets/javascripts/forms/constants.js') }}"></script>
			@yield('pagescripts')
    </body>
	</html>