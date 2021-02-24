<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr" lang="{{ app()->getLocale() }}">

<head>
	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>{{ config('app.name', '') }}</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
	<meta name="description" content="Stack admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
	<meta name="keywords" content="admin template, stack admin template, dashboard template, flat admin template, responsive admin template, web app">
	<meta name="author" content="PIXINVENT">
	<link rel="stylesheet" href="{!! asset('be/css/app.css') !!}" /> 
	<link rel="apple-touch-icon" href="/be/app-assets/images/ico/apple-icon-120.png">
	<link rel="shortcut icon" type="image/x-icon" href="/be/app-assets/images/ico/favicon.ico">
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i%7COpen+Sans:300,300i,400,400i,600,600i,700,700i"
		rel="stylesheet">
	<!-- BEGIN VENDOR CSS-->
	<link rel="stylesheet" type="text/css" href="/be/app-assets/css/vendors.css">
	<link rel="stylesheet" type="text/css" href="/be/assets/vendor/summernote/summernote.css">
	@yield('local-vendor-css')
	<!-- END VENDOR CSS-->
	<!-- BEGIN STACK CSS-->
	<link rel="stylesheet" type="text/css" href="/be/app-assets/css/app.min.css">
	<link rel="stylesheet" type="text/css" href="/be/app-assets/css/core/menu/menu-types/vertical-menu-modern.css">
	<link rel="stylesheet" type="text/css" href="/be/app-assets/vendors/css/extensions/toastr.css">
	<!--<link rel="stylesheet" type="text/css" href="/be/app-assets/fonts/simple-line-icons/style.css">-->
	<link rel="shortcut icon" href="{{ asset('favicon.png') }}" >
	{{-- local-stack-css --}}
	@yield('specific_vendor_header') 
	
	<style>
		.main-menu-content,
		.main-menu,
		.menu-fixed,
		.main-menu.menu-dark .navigation,
		.header-navbar,
		.footer.footer-dark {
		/* background-color: #13919b; */
		background-color: #404E67 !important;
		}
		.main-menu.menu-dark .navigation>li.open {
			border-left: 4px solid #404E67 !important;
		}
		.main-menu.menu-dark .navigation>li>ul, .main-menu.menu-dark ul.menu-popout {
			background: #404E67!important;
		}	
		.main-menu.menu-dark .navigation>li ul li.active>a, .main-menu.menu-dark .navigation>li>a> ul li.hover>a, .main-menu.menu-dark .navigation>li ul li:hover>a {
			background-color: #38445A !important;
		}	
		.blue-grey.lighten-2 {
		color: #fff !important;
		}
		.main-menu.menu-dark .navigation>li.active>a {
		color: #fff;
		}
		.main-menu.menu-dark .navigation>li.active>a,
		.main-menu.menu-dark .navigation>li.hover>a,
		.main-menu.menu-dark .navigation>li:hover>a {
		background-color: #38445A  !important;
		}
		.menu-shadow {
		background-color: #404E67 !important;
		}
	</style>
	<!-- END STACK CSS-->
	@yield('local_styles')
</head>