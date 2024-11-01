<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <title>Audiid</title>
        <meta content="Admin Dashboard" name="description" />
        <meta content="Themesbrand" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        @include('layouts.vertical.head')
        {{-- main_general css --}}
        <link href="{{ assets_version('/horizontal/assets/css/main_general/main_general.css') }}" rel="stylesheet" type="text/css" />
    </head>
<body class="fixed-left">
    <!-- Loader -->
    <div id="preloader"><div id="status"><div class="spinner"></div></div></div>
    <div id="wrapper">
        @include('layouts.vertical.header')
        <!-- Start right Content here -->
        <div class="content-page">
            <!-- Start content -->
            <div class="content">
                @include('layouts.vertical.sidebar')
                @yield('content')
            </div>
            @include('layouts.vertical.footer')  
        </div>
    </div>
    @include('layouts.vertical.footer-script')  
</body>
</html>