<!DOCTYPE html>
<html>
    <head>
        <!-- Google Tag Manager -->
        <script type="text/javascript">
            (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','GTM-KM9KX2G');
        </script>
        <!-- End Google Tag Manager -->
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <title>Audiid</title>
        <meta content="Admin Dashboard" name="description" />
        <meta content="Themesbrand" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        @include('layouts.horizontal.head')
        {{-- main_general css --}}
        <link href="{{ assets_version('/horizontal/assets/css/main_general/main_general.css') }}" rel="stylesheet" type="text/css" />
        <!-- C3 charts css -->
        <link href="{{ assets_version('/horizontal/assets/plugins/c3/c3.min.css') }}" rel="stylesheet" type="text/css" />
        <!--Morris Chart CSS -->
        <link rel="stylesheet" href="{{ assets_version('/horizontal/assets/plugins/morris/morris.css') }}">
        <!--Animate CSS -->
        <link href="{{ assets_version('/horizontal/assets/plugins/animate/animate.min.css') }}" rel="stylesheet" type="text/css">
        {{-- Select2 --}}
        <link href="{{ assets_version('/horizontal/assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}" rel="stylesheet">
        <link href="{{ assets_version('/horizontal/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
        <link href="{{ assets_version('/horizontal/assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ assets_version('/horizontal/assets/plugins/bootstrap-touchspin/css/jquery.bootstrap-touchspin.min.css') }}" rel="stylesheet" />
    </head>

    <body>
    <!-- Google Tag Manager (noscript) -->
    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KM9KX2G"height="0" width="0" style="display:none;visibility:hidden"></iframe>
    </noscript>
    <!-- End Google Tag Manager (noscript) -->
    <!-- Loader -->
    <div id="preloader"><div id="status"><div class="spinner"></div></div></div>

    <div id="wrapper">
        @include('layouts.horizontal.header')
        <div class="wrapper">
            <div class="container-fluid">
                @yield('breadcrumb')
                @yield('section')
            </div>
        </div>
        @include('layouts.horizontal.footer')   
    </div>
    @include('layouts.horizontal.footer-script')  
    {{-- TOOLTIP --}}
    <script>$(function () {$('[data-toggle="tooltip"]').tooltip()})</script>

    <script src="{{ assets_version('/horizontal/assets/plugins/peity-chart/jquery.peity.min.js') }}"></script>
    <!--C3 Chart-->
    <script type="text/javascript" src="{{ assets_version('/horizontal/assets/plugins/d3/d3.min.js') }}"></script>
    <script type="text/javascript" src="{{ assets_version('/horizontal/assets/plugins/c3/c3.min.js') }}"></script>
    <!-- Jvector Map js -->
    <script src="{{ assets_version('/horizontal/assets/plugins/jvectormap/jquery-jvectormap-2.0.5.min.js') }}"></script>
    <script src="{{ assets_version('/horizontal/assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
    <script src="{{ assets_version('/horizontal/assets/plugins/jvectormap/gdp-data.js') }}"></script>
    <!-- KNOB JS -->
    <script src="{{ assets_version('/horizontal/assets/plugins/jquery-knob/excanvas.js') }}"></script>
    <script src="{{ assets_version('/horizontal/assets/plugins/jquery-knob/jquery.knob.js') }}"></script>
    <!-- Page specific js -->
    <script src="{{ assets_version('/horizontal/assets/pages/dashboard.js') }}"></script>
    <!--Morris Chart-->
    <script src="{{ assets_version('/horizontal/assets/plugins/morris/morris.min.js') }}"></script>
    <script src="{{ assets_version('/horizontal/assets/plugins/raphael/raphael-min.js') }}"></script>
    <script src="{{ assets_version('/horizontal/assets/pages/morris.init.js') }}"></script>
    <!-- Chart JS -->
    <script src="{{assets_version('/horizontal/assets/plugins/chart.js/Chart.min.js') }}"></script>
    <script src="{{assets_version('/horizontal/assets/pages/chartjs.init.js') }}"></script>

    <!-- Required datatable js -->
    <script src="{{ assets_version('/horizontal/assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ assets_version('/horizontal/assets/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Buttons examples -->
    <script src="{{ assets_version('/horizontal/assets/plugins/datatables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ assets_version('/horizontal/assets/plugins/datatables/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ assets_version('/horizontal/assets/plugins/datatables/jszip.min.js') }}"></script>
    <script src="{{ assets_version('/horizontal/assets/plugins/datatables/pdfmake.min.js') }}"></script>
    <script src="{{ assets_version('/horizontal/assets/plugins/datatables/vfs_fonts.js') }}"></script>
    <script src="{{ assets_version('/horizontal/assets/plugins/datatables/buttons.html5.min.js') }}"></script>
    <script src="{{ assets_version('/horizontal/assets/plugins/datatables/buttons.print.min.js') }}"></script>
    <script src="{{ assets_version('/horizontal/assets/plugins/datatables/buttons.colVis.min.js') }}"></script>

    <script src="{{ assets_version('/horizontal/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ assets_version('/horizontal/assets/js/modernizr.min.js') }}"></script>

    <!-- Parsley js -->
    <script type="text/javascript" src="{{ assets_version('/horizontal/assets/plugins/parsleyjs/parsley.min.js') }}"></script>

    <script src="{{ assets_version('/horizontal/assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
    <script src="{{ assets_version('/horizontal/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ assets_version('/horizontal/assets/plugins/select2/js/select2.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets_version('/horizontal/assets/plugins/bootstrap-maxlength/bootstrap-maxlength.js') }}"></script>
    <script src="{{ assets_version('/horizontal/assets/plugins/bootstrap-filestyle/js/bootstrap-filestyle.min.js') }}"></script>
    <script src="{{ assets_version('/horizontal/assets/plugins/bootstrap-touchspin/js/jquery.bootstrap-touchspin.min.js') }}"></script>
    <script type="text/javascript">
        var $zoho=$zoho || {};$zoho.salesiq = $zoho.salesiq || {widgetcode:"9208408520a66f144729e375db4413de32b10e8abb3070bd1f67d327dc07c946", values:{},ready:function(){}};var d=document;s=d.createElement("script");s.type="text/javascript";s.id="zsiqscript";s.defer=true;s.src="https://salesiq.zoho.com/widget";t=d.getElementsByTagName("script")[0];t.parentNode.insertBefore(s,t);d.write("<div id='zsiqwidget'></div>");
    </script>
    <script type="text/javascript">
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        
        gtag('config', 'UA-172320884-1');
    </script>
</body>
</html>