<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta name="viewport" content="width=device-width" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>{{env('APP_NAME')}} - @yield('title') </title>

	<style>
		#packages-class p {
			color: #777777 !important; 
		}
		#packages-class  h1,h2,h3, h4,h5,h6{
			color: inherit;
			word-wrap: normal !important;
			font-family: Helvetica, Arial, sans-serif;
			font-weight: normal !important;
		}
		#packages-class h1 {
			font-size: 34px !important;
        }

		#packages-class h2 {
			font-size: 30px !important;
        }

		#packages-class h3 {
			font-size: 28px !important;
        }

		#packages-class h4 {
			font-size: 24px !important;
        }

		#packages-class h5 {
			font-size: 20px !important;
        }
		#packages-class h6 {
			font-size: 18px !important;
        }
        #packages-class hr {
			/*max-width: 480px;*/
			height: 0 !important;
			border-right: 0 !important;
			border-top: 0 !important;
			border-bottom: 1px solid #cacaca !important;
			border-left: 0 !important;
			margin: 15px 35px !important;
			clear: both ;
        }
		#packages-class .text-capitalize {
			text-transform: capitalize !important;
        }
        #packages-class .btn-primary {
            background: #0088ff;
            background-image: -webkit-linear-gradient(top, #0088ff, #0088ff) !important;
            background-image: -moz-linear-gradient(top, #0088ff, #0088ff) !important;
            background-image: -ms-linear-gradient(top, #0088ff, #0088ff) !important;
            background-image: -o-linear-gradient(top, #0088ff, #0088ff) !important;
            background-image: linear-gradient(to bottom, #0088ff, #0088ff) !important;
            -webkit-border-radius: 10 !important;
            -moz-border-radius: 10 !important;
            border-radius: 10px !important;
            font-family: Arial;
            color: #ffffff !important;
            font-size: 20px !important;
            padding: 10px 20px 10px 20px !important;
            text-decoration: none !important;
        }

        #packages-class .btn-primary:hover {
            background: #3cb0fd !important;
            background-image: -webkit-linear-gradient(top, #3cb0fd, #3498db) !important;
            background-image: -moz-linear-gradient(top, #3cb0fd, #3498db) !important;
            background-image: -ms-linear-gradient(top, #3cb0fd, #3498db) !important;
            background-image: -o-linear-gradient(top, #3cb0fd, #3498db) !important;
            background-image: linear-gradient(to bottom, #3cb0fd, #3498db) !important;
            text-decoration: none !important;
        }
        #packages-class .packages-w15{
            width: 15px !important;
        }
        #packages-class .packages-w325{
            width: 325px !important;
        }
        #packages-class .packages-w580{
            width: 580px !important;
        }
        #packages-class .packages-w640{
            width: 640px !important;
            font-family:'Helvetica',sans-serif;
            border-collapse:collapse;
        }
        #packages-class .m-0{
            margin:0 !important;
        }
        #packages-class .m-10{
            margin:10px !important;
        }
        #packages-class .m-15{
            margin:15px !important;
        }
        #packages-class .m-25{
            margin:25px !important;
        }
        #packages-class .m-35{
            margin:35px !important;
        }
        #packages-class .m-t-0{
            margin-top:0 !important;
        }
        #packages-class .m-t-10{
            margin-top:10px !important;
        }
        #packages-class .m-t-15{
            margin-top:15px !important;
        }
        #packages-class .m-t-25{
            margin-top:25px !important;
        }
        #packages-class .m-t-35{
            margin-top:35px !important;
        }
        #packages-class .m-b-0{
            margin-bottom:0 !important;
        }
        #packages-class .m-b-10{
            margin-bottom:10px !important;
        }
        #packages-class .m-b-15{
            margin-bottom:15px !important;
        }
        #packages-class .m-b-25{
            margin-bottom:25px !important;
        }
        #packages-class .m-b-35{
            margin-bottom:35px !important;
        }
        #packages-class .m-l-0{
            margin-left:0 !important;
        }
        #packages-class .m-l-10{
            margin-left:10px !important;
        }
        #packages-class .m-l-15{
            margin-left:15px !important;
        }
        #packages-class .m-l-25{
            margin-left:25px !important;
        }
        #packages-class .m-l-35{
            margin-left:35px !important;
        }
        #packages-class .m-r-0{
            margin-right:0 !important;
        }
        #packages-class .m-r-10{
            margin-right:10px !important;
        }
        #packages-class .m-r-15{
            margin-right:15px !important;
        }
        #packages-class .m-r-25{
            margin-right:25px !important;
        }
        #packages-class .m-r-35{
            margin-right:35px !important;
        }
        #packages-class .p-0{
            padding:0 !important;
        }
        #packages-class .p-10{
            padding:10px !important;
        }
        #packages-class .p-15{
            padding:15px !important;
        }
        #packages-class .p-25{
            padding:25px !important;
        }
        #packages-class .p-35{
            padding:35px !important;
        }
        #packages-class .p-t-0{
            padding-top:0 !important;
        }
        #packages-class .p-t-10{
            padding-top:10px !important;
        }
        #packages-class .p-t-15{
            padding-top:15px !important;
        }
        #packages-class .p-t-25{
            padding-top:25px !important;
        }
        #packages-class .p-t-35{
            padding-top:35px !important;
        }
        #packages-class .p-b-0{
            padding-bottom:0 !important;
        }
        #packages-class .p-b-10{
            padding-bottom:10px !important;
        }
        #packages-class .p-b-15{
            padding-bottom:15px !important;
        }
        #packages-class .p-b-25{
            padding-bottom:25px !important;
        }
        #packages-class .p-b-35{
            padding-bottom:35px !important;
        }
        #packages-class .p-l-0{
            padding-left:0 !important;
        }
        #packages-class .p-l-10{
            padding-left:10px !important;
        }
        #packages-class .p-l-15{
            padding-left:15px !important;
        }
        #packages-class .p-l-25{
            padding-left:25px !important;
        }
        #packages-class .p-l-35{
            padding-left:35px !important;
        }
        #packages-class .p-r-0{
            padding-right:0 !important;
        }
        #packages-class .p-r-10{
            padding-right:10px !important;
        }
        #packages-class .p-r-15{
            padding-right:15px !important;
        }
        #packages-class .p-r-25{
            padding-right:25px !important;
        }
        #packages-class .p-r-35{
            padding-right:35px !important;
        }
        
	</style>

     @yield('specific_vendor_header')
</head>

<body id="packages-class">
    <table id="packages-background-table" style="table-layout:fixed;background-color:#ececec" width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
        <tbody>
            <tr style="border-collapse:collapse">
                <td bgcolor="#ececec" align="center">
                    <table class="packages-w640 m-t-0 m-b-0 m-r-10 m-l-10" width="640" cellspacing="0" cellpadding="0" border="0">
                        <tbody>
                            <tr style="border-collapse:collapse">
                                <td class="packages-w640" width="640" height="20"></td>
                            </tr>
                            <tr style="border-collapse:collapse">
                                <td class="packages-w640" width="640">
                                    <table id="packages-top-bar" class="packages-w640" style="border-radius:6px 6px 0px 0px;color:#ffffff;height: 60px;" width="640" cellspacing="0" cellpadding="0" border="0" bgcolor="#ffffff">
                                        <tbody>
                                            <tr style="border-collapse:collapse">
                                                <td class="packages-w15" width="15"></td>
                                                <td class="packages-w325" width="350" valign="middle" align="left">
                                                    <table class="packages-w325" width="350" cellspacing="0" cellpadding="0" border="0">
                                                        <tbody><tr style="border-collapse:collapse"><td class="packages-w325" width="350" height="8"></td></tr>
                                                        </tbody>
                                                    </table>
                                                    @yield('logo')
                                                    <table class="packages-w325" width="350" cellspacing="0" cellpadding="0" border="0">
                                                        <tbody><tr style="border-collapse:collapse"><td class="packages-w325" width="350" height="8"></td></tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                                <td class="packages-w30" width="30"></td>
                                                <td class="packages-w255" width="255" valign="middle" align="right">
                                                    <table class="packages-w255" width="255" cellspacing="0" cellpadding="0" border="0">
                                                        <tbody><tr style="border-collapse:collapse"><td class="packages-w255" width="255" height="8"></td></tr>
                                                        </tbody>
                                                    </table>
                                                    <table cellspacing="0" cellpadding="0" border="0">
                                                        <tbody>
                                                            <tr style="border-collapse:collapse">
                                                                
                                                            </tr>
                                                        </tbody></table>
                                                    <table class="packages-w255" width="255" cellspacing="0" cellpadding="0" border="0">
                                                        <tbody><tr style="border-collapse:collapse"><td class="packages-w255" width="255" height="8"></td></tr>
                                                        </tbody></table>
                                                </td>
                                                <td class="packages-w15" width="15"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr style="border-collapse:collapse;">
                                <td id="packages-header" class="packages-w640" style="height: 110px;" width="640" bgcolor="#008aff" align="center">
                                    <div style="text-align:center;" align="center">
                                        <h1 style="color:#fff;">@yield('header')</h1>
                                    </div>
                                </td>
                            </tr>

                            <tr style="border-collapse:collapse"><td class="packages-w640" width="640" bgcolor="#ffffff" height="30"></td></tr>
                            <tr id="packages-simple-content-row" style="border-collapse:collapse"><td class="packages-w640" width="640" bgcolor="#ffffff">
                                    <table class="packages-w640" width="640" cellspacing="0" cellpadding="0" border="0" align="left">
                                        <tbody><tr style="border-collapse:collapse">
                                                <td class="packages-w30" width="30"></td>
                                                <td class="packages-w580" width="580">

                                                    <table class="packages-w580" width="580" cellspacing="0" cellpadding="0" border="0">
                                                        <tbody>
                                                            @yield('content')
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            {{-- <tr style="border-collapse:collapse"><td class="packages-w640" width="640" bgcolor="#ffffff" height="15"></td></tr> --}}
                            <tr style="border-collapse:collapse">
                                <td class="packages-w640" width="640" align="center">
                                    <table id="packages-footer" class="packages-w640 m-t-0 m-b-0 m-r-10 m-l-10" style="border-radius:0px 0px 6px 6px;" width="640" cellspacing="0" cellpadding="0" border="0" bgcolor="#ffffff">
                                        <tbody>
                                            <tr style="">
                                                <td class="packages-w580">
                                                    <hr>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="packages-w580" width="580">
                                                    @yield('footer')
                                                    <div style="text-align: center;margin-bottom: 5px;">
                                                        <a href="{{ env('APP_URL') }}" style="color: #535252;font-size:15px;">{{ env('APP_DOMAIN') }}</a>
                                                    </div>
                                                    <p id="packages-street-address" style="font-size:11px;line-height:16px;margin-top:0px;margin-bottom:15px;color:#ffffff;white-space:normal" align="center">
                                                        <span></span><br style="line-height:100%">
                                                        <span><strong>Copyright</strong> {{env('APP_NAME')}} &copy; {{(date('Y') > 2018)?'2018 - '. date('Y'):'2018'}}</span>
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr style="border-collapse:collapse"><td class="packages-w580" width="580" height="10"></td></tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr style="border-collapse:collapse"><td class="packages-w640" width="640" height="60"></td></tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>        
</body>

@yield('specific_vendor_footer')

</html>