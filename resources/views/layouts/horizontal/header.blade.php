  <!-- Navigation Bar-->
  <header id="topnav">
            <div class="topbar-main">
                <div class="container-fluid">

                    <!-- Logo container-->
                    <div class="logo">
                        <a href="{{route('Dasboard_Ruta')}}" class="logo">
                            <img src="{{ URL::asset('horizontal/assets/images/logo_horizontal_white.png') }}" alt="" height="50">
                        </a>

                    </div>
                    <!-- End Logo container-->

                    <div class="menu-extras topbar-custom">

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
                            <!-- Search -->
                            <li class="list-inline-item dropdown notification-list">
                                <a class="nav-link waves-effect toggle-search" href="#"  data-target="#search-wrap">
                                    <i class="mdi mdi-magnify noti-icon"></i>
                                </a>
                            </li>
                            <!-- Fullscreen -->
                            {{-- <li class="list-inline-item dropdown notification-list hide-phone">
                                <a class="nav-link waves-effect" href="#" id="btn-fullscreen">
                                    <i class="mdi mdi-fullscreen noti-icon"></i>
                                </a>
                            </li> --}}
                            <!-- language-->
                            {{-- <li class="list-inline-item dropdown notification-list hide-phone">
                                <a class="nav-link dropdown-toggle arrow-none waves-effect" data-toggle="dropdown" href="#" role="button"
                                   aria-haspopup="false" aria-expanded="false">
                                    {{ trans('messages.english') }} <img src="{{ URL::asset('horizontal/assets/images/flags/us_flag.jpg') }}" class="ml-2" height="16" alt=""/>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right language-switch">
                                    <a class="dropdown-item" href="#"><img src="{{ URL::asset('horizontal/assets/images/flags/germany_flag.jpg') }}" alt="" height="16"/><span> {{ trans('messages.german') }} </span></a>
                                    <a class="dropdown-item" href="#"><img src="{{ URL::asset('horizontal/assets/images/flags/italy_flag.jpg') }}" alt="" height="16"/><span> {{ trans('messages.italian') }} </span></a>
                                    <a class="dropdown-item" href="#"><img src="{{ URL::asset('horizontal/assets/images/flags/french_flag.jpg') }}" alt="" height="16"/><span> {{ trans('messages.french') }} </span></a>
                                    <a class="dropdown-item" href="#"><img src="{{ URL::asset('horizontal/assets/images/flags/spain_flag.jpg') }}" alt="" height="16"/><span> {{ trans('messages.spanish') }} </span></a>
                                    <a class="dropdown-item" href="#"><img src="{{ URL::asset('horizontal/assets/images/flags/russia_flag.jpg') }}" alt="" height="16"/><span> {{ trans('messages.russian') }} </span></a>
                                </div>
                            </li> --}}
                            <!-- notification-->
                            <li class="list-inline-item dropdown notification-list">
                                <a class="nav-link dropdown-toggle arrow-none waves-effect" data-toggle="dropdown" href="#" role="button"
                                   aria-haspopup="false" aria-expanded="false">
                                    <i class="ion-ios7-bell noti-icon"></i>
                                    <span class="badge badge-danger noti-icon-badge">3</span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-arrow dropdown-menu-lg">
                                    <!-- item-->
                                    <div class="dropdown-item noti-title">
                                        <h5>Notificación (3)</h5>
                                    </div>

                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item active">
                                        <div class="notify-icon bg-success"><i class="mdi mdi-oil-temperature"></i></div>
                                        <p class="notify-details"><b>Temperatura alta</b><small class="text-muted">Un colaborador presenta temperatura alta.</small></p>
                                    </a>

                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        <div class="notify-icon bg-warning"><i class="mdi mdi-home"></i></div>
                                        <p class="notify-details"><b>Colaborador en riesgo</b><small class="text-muted">Uno de tus colaboradores en riesgo, no esta en casa.</small></p>
                                    </a>

                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        <div class="notify-icon bg-info"><i class="mdi mdi-message-alert"></i></div>
                                        <p class="notify-details"><b>Cumplimiento crítico</b><small class="text-muted">Una de las categorías del protocolo de bioseguridad no se esta cumpliendo.</small></p>
                                    </a>

                                    <!-- All-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        Ver todo
                                    </a>

                                </div>
                            </li>
                            <!-- User-->
                            <li class="list-inline-item dropdown notification-list">
                                <a class="nav-link dropdown-toggle arrow-none waves-effect nav-user" data-toggle="dropdown" href="#" role="button"
                                   aria-haspopup="false" aria-expanded="false">
                                    <img src="{{ URL::asset('horizontal/assets/images/users/avatar-1.jpg') }}" alt="user" class="rounded-circle">
                                </a>
                                <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                                    <a class="dropdown-item" href="#"><i class="dripicons-user text-muted"></i> {{ trans('menumessages.menuuserprofile') }}</a>
                                    {{-- <a class="dropdown-item" href="#"><i class="dripicons-wallet text-muted"></i> My Wallet</a> --}}
                                    <a class="dropdown-item" href="#">
                                        {{-- <span class="badge badge-success pull-right m-t-5">5</span> --}}
                                        <i class="dripicons-gear text-muted"></i> {{ trans('menumessages.menuusersettings') }}</a>
                                    {{-- <a class="dropdown-item" href="#"><i class="dripicons-lock text-muted"></i> Lock screen</a> --}}
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{route('Login_Ruta')}}"><i class="dripicons-exit text-muted"></i> {{ trans('menumessages.menuuserlogout') }}</a>
                                </div>
                            </li>
                            <li class="menu-item list-inline-item">
                                <!-- Mobile menu toggle-->
                                <a class="navbar-toggle nav-link">
                                    <div class="lines">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </div>
                                </a>
                                <!-- End mobile menu toggle-->
                            </li>

                        </ul>
                    </div>
                    <!-- end menu-extras -->

                    <div class="clearfix"></div>

                </div> <!-- end container -->
            </div>
            <!-- end topbar-main -->

            <!-- MENU Start -->
            <div class="navbar-custom">
                <div class="container-fluid">
                    <div id="navigation">
                        <!-- Navigation Menu-->
                        <ul class="navigation-menu">

                            <li class="has-submenu">
                                <a href="#"><i class="mdi mdi-account-key"></i>{{ trans('menumessages.menuf') }}</a>
                                <ul class="submenu">
                                    <li><a href="{{ route('Admin_Users') }}">{{ trans('menumessages.submenuff') }}</a></li>
                                    <li><a href="#">{{ trans('menumessages.submenufs') }}</a></li>
                                    <li><a href="#">{{ trans('menumessages.submenuft') }}</a></li>
                                </ul>
                            </li>

                            <li class="has-submenu">
                                <a href="#"><i class="mdi mdi-file-document"></i>{{ trans('menumessages.menus') }}</a>
                                <ul class="submenu">
                                    <li><a href="#">{{ trans('menumessages.submenusf') }}</a></li>
                                    <li><a href="#">{{ trans('menumessages.submenuss') }}</a></li>
                                </ul>
                            </li>

                            <li class="has-submenu">
                                <a href="#"><i class="mdi mdi-comment-check"></i>{{ trans('menumessages.menut') }}</a>
                                <ul class="submenu">
                                    <li><a href="#">{{ trans('menumessages.submenutf') }}</a></li>
                                </ul>
                            </li>

                            <li>
                                <a href="#" target="_blank"><i class="mdi mdi-view-dashboard"></i>{{ trans('menumessages.menufo') }}</a>
                            </li>

                        </ul>
                        <!-- End navigation menu -->
                    </div> <!-- end #navigation -->
                </div> <!-- end container -->
            </div> <!-- end navbar-custom -->
        </header>
        <!-- End Navigation Bar-->