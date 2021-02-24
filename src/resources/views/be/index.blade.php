@extends('be.layouts.main') 

@section('specific_vendor_header')
<link rel="stylesheet" type="text/css" href="/be/app-assets/vendors/css/extensions/unslider.css">
<link rel="stylesheet" type="text/css" href="/be/app-assets/vendors/css/weather-icons/climacons.min.css">
<link rel="stylesheet" type="text/css" href="/be/app-assets/fonts/meteocons/style.min.css">
<link rel="stylesheet" type="text/css" href="/be/app-assets/vendors/css/charts/morris.css">
<link rel="stylesheet" type="text/css" href="/be/app-assets/css/core/colors/palette-gradient.min.css">
<link rel="stylesheet" type="text/css" href="/be/app-assets/fonts/simple-line-icons/style.min.css">
<link rel="stylesheet" type="text/css" href="/be/app-assets/css/core/colors/palette-gradient.min.css">
<link rel="stylesheet" type="text/css" href="/be/app-assets/css/pages/timeline.min.css">
@endsection
 
 
@section('local_styles')
<link rel="stylesheet" type="text/css" href="/be/app-assets/css/core/colors/palette-gradient.css">
@endsection
 
@section('content')

@endsection
 
@section('specific_vendor_footer')
<script src="/be/app-assets/vendors/js/charts/raphael-min.js" type="text/javascript"></script>
<script src="/be/app-assets/vendors/js/charts/morris.min.js" type="text/javascript"></script>
<script src="/be/app-assets/vendors/js/extensions/unslider-min.js" type="text/javascript"></script>
<script src="/be/app-assets/vendors/js/timeline/horizontal-timeline.js" type="text/javascript"></script>
@endsection
 
@section('local_script')
{{-- <script src="/be/app-assets/js/scripts/pages/dashboard-ecommerce.js" type="text/javascript"></script> --}}
{{-- <script src="/be/assets/js/scripts.js" type="text/javascript"></script> --}}
@endsection