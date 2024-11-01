@extends('layouts.vertical.master-without-nav')

{{-- @section('css')
<link href="{{ assets_version('/vertical/assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ assets_version('/vertical/assets/css/main_general/main_general.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ assets_version('/vertical/assets/css/registrar/main.css') }}" rel="stylesheet" type="text/css" />
@endsection --}}

@section('content')
    <!-- Begin page -->
        <div class="accountbg"></div>
        <div class="wrapper-page">

            <div class="card">
                <div class="card-body">
                    <h3 class="text-center m-0">
                        <a href="index" class="logo logo-admin"><img src="{{ URL::asset('/vertical/assets/images/logo_new_2023.png') }}" height="30" alt="logo"></a>
                    </h3>

                    <div class="p-3">
                        {{-- <h4 class="text-muted font-18 m-b-5 text-center">¡Gracias!</h4> --}}
                        <p class="text-muted text-center">{{ $mensaje }}
                        </p>

                        <div class="row justify-content-center">
                            <a href="{{ route('Dasboard_Ruta') }} " class="btn btn-success text-center">Regresar a Audiid</a>
                        </div>
                        
                    </div>

                </div>
            </div>

            <div class="m-t-40 text-center">
                <p class="text-white">© {{date('Y')}} Audiid <i class="mdi mdi-heart" style="color:#26ae9c"></i></p>
            </div>

        
        </div>

@endsection
{{-- 
@section('script')
<!-- Parsley js -->
<script type="text/javascript" src="{{ assets_version('/vertical/assets/plugins/parsleyjs/parsley.min.js') }}"></script>

<script src="{{ assets_version('/vertical/assets/js/main_general/main.js') }}"></script>
<script src="{{ assets_version('/vertical/assets/plugins/select2/js/select2.min.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ assets_version('/vertical/assets/js/registrar/main.js') }}"></script>

@endsection --}}

