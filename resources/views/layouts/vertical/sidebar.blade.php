
@php
    $perfil = \DB::table('perfil')->where('id', '=' ,auth()->user()->perfil_id)->first();
    //NOTIFICACIONES
    $consultaPlanAccion = \DB::select("SELECT
        lcep.id AS plan_accion_id,
        pre.nombre AS pregunta
        FROM lista_chequeo_ejec_respuestas lcer
        INNER JOIN lista_chequeo_ejecutadas lce ON lce.id = lcer.lista_chequeo_ejec_id
        INNER JOIN lista_chequeo lc ON lc.id = lce.lista_chequeo_id
        INNER JOIN usuario us ON us.id = lce.usuario_id
        INNER JOIN establecimiento esta ON esta.id = us.establecimiento_id
        INNER JOIN empresa empe ON empe.id = esta.empresa_id
        INNER JOIN pregunta pre ON pre.id = lcer.pregunta_id
        INNER JOIN respuesta res ON res.id = lcer.respuesta_id
        INNER JOIN lista_chequeo_ejec_opciones lceo ON lceo.lista_chequeo_ejec_respuestas_id = lcer.id
        INNER JOIN lista_chequeo_ejec_planaccion lcep ON lcep.lista_chequeo_ejec_opciones=lceo.id
        INNER JOIN plan_accion pa ON pa.id=lceo.plan_accion_id
        INNER JOIN plan_accion_automatico paa ON paa.plan_accion_id = pa.id
        WHERE us.cuenta_principal_id=:idCuentaPrincipal AND lce.estado = 2
        ORDER BY lcep.id DESC limit 3",['idCuentaPrincipal' => auth()->user()->cuenta_principal_id]);
    
    
    $usuarioCuentaPrincipal = \DB::table('cuenta_principal')->where('id', '=' ,auth()->user()->cuenta_principal_id)->first();

    $diasFaltantes = 0;
    $isFree=true;
    $mensaje = '';
    switch ($usuarioCuentaPrincipal->plan_id) 
    {
        case 1: //GRATIS
            $isFree=true;
            break;

        case 2:
        case 3:
        case 4:
        $isFree=false;
        $diasFaltantes = \DB::select("SELECT 
            DATEDIFF(pp.fecha_fin,NOW()) AS DIAS_FALTANTES
            FROM plan_pagos AS pp
            WHERE pp.cuenta_principal_id = :idCuentaPrincipal
            ORDER BY id DESC LIMIT 1;",['idCuentaPrincipal' => auth()->user()->cuenta_principal_id]);
            $porcentaje = number_format((100 * intval($diasFaltantes[0]->DIAS_FALTANTES) ) / 30,0);

            $primerPorcentaje = ($porcentaje > 50 ? 50 : $porcentaje);
            $segundoPorcentaje = (($porcentaje <= 50  ? 0 : ($porcentaje - 50 > 25 ? 25 : $porcentaje - 50)));
            $tercerPorcentaje = (($porcentaje - 50 > 25 ? (($porcentaje - 50) - $segundoPorcentaje) : 0));
            if($diasFaltantes[0]->DIAS_FALTANTES < 0)
                $mensaje = "Tu suscripción venció hace ".(INTVAL($diasFaltantes[0]->DIAS_FALTANTES) * -1)." días.";
            else
                $mensaje = "Te quedan ".$diasFaltantes[0]->DIAS_FALTANTES." días para finalizar tu suscripción";
            break;
        
        default:
            # code...
            break;
    }

    $img = (is_null(auth()->user()->url_imagen) ? assets_version('/vertical/assets/images/users/circle_logo_audiid.png') : assets_version("/imagenes/usuarios/".auth()->user()->url_imagen));

@endphp
<!-- Top Bar Start -->
<div class="topbar">

<nav class="navbar-custom">
    <!-- Search input -->
    <div class="search-wrap" id="search-wrap">
        <div class="search-bar">
            <input class="search-input" type="search" placeholder="Search" />
            <a href="#" class="close-search toggle-search" data-target="#search-wrap">
                <i class="mdi mdi-close-circle"></i>
            </a>
        </div>
    </div>

    <ul class="list-inline float-right mb-0">
        @if ($perfil->id == 1)
        
            <!-- notification-->
            <li class="list-inline-item dropdown notification-list" id="dashboard-tour-1">
                <a class="nav-link dropdown-toggle arrow-none waves-effect" data-toggle="dropdown" href="#" role="button"
                aria-haspopup="false" aria-expanded="false">
                    <i class="ion-ios7-bell noti-icon"></i>
                    <span class="badge badge-danger noti-icon-badge">{{ COUNT($consultaPlanAccion) }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-arrow dropdown-menu-lg">
                    <!-- item-->
                    <div class="dropdown-item noti-title">
                        <h5>Notificación ({{ COUNT($consultaPlanAccion) }})</h5>
                    </div>

                    @foreach ($consultaPlanAccion as $itemNotificacion)
                        <!-- item-->
                        @if($itemNotificacion->plan_accion_id != null)
                        <a href="{{ route('Plan_action_Filter',$itemNotificacion->plan_accion_id)}}" class="dropdown-item notify-item active">
                            <div class="notify-icon bg-danger"><i class="mdi mdi-alert-circle"></i></div>
                            <p class="notify-details"><b>Realiza seguimientos:</b><small class="text-muted">{{ $itemNotificacion->pregunta }} </small></p>
                        </a>
                        @endif
                    @endforeach

                    <!-- All-->
                    <a href="{{ route('Plan_action') }}" class="dropdown-item notify-item">
                        Ver todo
                    </a>

                </div>
            </li>
        @endif
        
        <!-- User-->
        <li class="list-inline-item dropdown notification-list">
            <a class="nav-link dropdown-toggle arrow-none waves-effect nav-user" data-toggle="dropdown" href="#" role="button"
               aria-haspopup="false" aria-expanded="false">
               <img src="{{ $img }}" alt="user" class="rounded-circle">
            </a>
            <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                <a href="#" class="dropdown-item ellipseText" data-toggle="tooltip" data-placement="left" title="{{ auth()->user()->nombre_completo }}" href="#"><i class="dripicons-user text-muted"></i> {{ auth()->user()->nombre_completo }} <span class="rol">{{ $perfil->nombre }}</span></a>
                {{-- @if ($perfil->id == 1)
                    <a class="dropdown-item" href="{{ route('Account_Settings') }}"><i class="text-muted"></i> Cuenta </a>
                @endif --}}
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{route('Logout')}}"><i class="dripicons-exit text-muted"></i> {{ trans('menumessages.menuuserlogout') }}</a>
            </div>
        </li>

    </ul>
    
    <!-- Page title -->
    <ul class="list-inline menu-left mb-0">
        <li class="list-inline-item">
            <button type="button" class="button-menu-mobile open-left waves-effect">
                <i class="ion-navicon"></i>
            </button>
        </li>
        <li class="hide-phone list-inline-item app-search">
            @yield('breadcrumb') 
        </li>
    </ul>
    <div class="clearfix"></div>
    
    {{-- @if (!$isFree)
    <div class="tiempoLine" title="{{ $mensaje }}" data-toggle="tooltip" data-placement="top">
            <div class="progress">
                <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $primerPorcentaje }}%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $segundoPorcentaje }}%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $tercerPorcentaje }}%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    @endif --}}
    

</nav>
</div>
<!-- Top Bar End -->