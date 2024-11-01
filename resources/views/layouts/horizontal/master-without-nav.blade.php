

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <title>Audiid</title>
        @include('layouts.horizontal.head')
        {{-- main_general css --}}
        <link href="{{ assets_version('/horizontal/assets/css/main_general/main_general.css') }}" rel="stylesheet" type="text/css" />
    </head>
    <body class="fixed-left">
        <!-- Loader -->
        <div id="preloader"><div id="status"><div class="spinner"></div></div></div>
        @yield('content')
        @include('layouts.horizontal.footer-script')    
    </body>
</html>
