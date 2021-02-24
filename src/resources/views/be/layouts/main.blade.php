@include('be.layouts.header')

<body class="vertical-layout vertical-menu-modern 2-columns   menu-expanded fixed-navbar" data-open="click" data-menu="vertical-menu-modern"
	data-col="2-columns">
	@include ('be.layouts.top-menu')
	@include ('be.layouts.side-menu')
	<div class="app-content content">
		<div class="content-wrapper">
			<div class="content-body">
				@yield('content')
			</div>
	 	</div>
	</div>
	@include ('be.layouts.footer')

	 
	<!-- BEGIN VENDOR JS-->
	<script src="/be/app-assets/vendors/js/vendors.min.js" type="text/javascript"></script>
	<script src="/be/assets/vendor/summernote/summernote.js" type="text/javascript"></script>
	<!-- BEGIN VENDOR JS-->
	<!-- BEGIN PAGE VENDOR JS-->
	<script src="/be/app-assets/vendors/js/extensions/toastr.min.js" type="text/javascript"></script>
	{{-- @yield('local-vendor-js') --}}
	<!-- BEGIN STACK JS-->
	<script src="{!! asset('be/js/core.js') !!}" type="text/javascript"></script>
	<script src="/be/app-assets/js/core/app-menu.min.js" type="text/javascript"></script>
	<script src="/be/app-assets/js/core/app.min.js" type="text/javascript"></script>
	<!-- Specific Page Vendor Scripts -->
    @yield('specific_vendor_footer') 
	<!-- END PAGE VENDOR JS-->
	
	<!-- END STACK JS-->
	<!-- BEGIN PAGE LEVEL JS-->
	
	<!-- END PAGE LEVEL JS-->
	<script src="/be/vendor/loadingoverlay.min.js"></script>
	<script src="/be/vendor/loadingoverlay_progress.min.js"></script>
	@yield('local_script')
</body>