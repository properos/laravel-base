@include('themes.'.(isset($theme)?$theme:'default').'.layouts.header')
<body>
    <div>
        @include('themes.'.(isset($theme)?$theme:'default').'.layouts.top-menu')
        <div class="content">
            @yield('content')
        </div>
    </div>
    @include('themes.'.(isset($theme)?$theme:'default').'.layouts.footer')
    @yield('specific_vendor_footer') 
    <script src="{!! asset('be/js/core.js') !!}" type="text/javascript"></script>
    <script src="/themes/default/js/jquery-3.3.1.min.js"></script>
    <script src="/themes/default/js/bootstrap.min.js"></script>
    <script src="/be/vendor/loadingoverlay.min.js"></script>
	<script src="/be/vendor/loadingoverlay_progress.min.js"></script>
    @yield('local_script')
</body>
</html>
