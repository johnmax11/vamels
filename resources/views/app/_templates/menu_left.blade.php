<!-- START LEFT SIDEBAR NAV-->
    <aside id="left-sidebar-nav" class="nav-expanded nav-lock nav-collapsible">
        <div class="brand-sidebar {{Config::get('syslab.style_background')}}">
            <h1 class="logo-wrapper">
                <a href="{{Config::get('syslab.path_url_web')}}/app/home/main/read" class="brand-logo darken-1">
                    <img src="{{Config::get('syslab.path_url_web')}}/images/logo_full.png" alt="{{Config::get('syslab.title')}} logo">
                    <span class="logo-text ">{{Config::get('syslab.title_clean')}}</span>
                </a>
            </h1>
        </div>
        <ul id="slide-out" class="side-nav fixed leftside-navigation">
            <li class="user-details cyan darken-2">
              <div class="row">
                <div class="col col s4 m4 l4">
                    <img src="{{Config::get('syslab.path_url_web')}}/images/sin_imagen.png" alt="avatar" class="circle responsive-img valign profile-image cyan">
                </div>
                <div class="col col s8 m8 l8">
                    <ul id="profile-dropdown-nav" class="dropdown-content">
                        <li>
                            <a href="#" class="grey-text text-darken-1">
                              <i class="material-icons">face</i> Perfil
                            </a>
                        </li>
                        <li>
                            <a href="#" class="grey-text text-darken-1">
                              <i class="material-icons">settings</i> Ajustes
                            </a>
                        </li>
                        <li>
                            <a href="#" class="grey-text text-darken-1">
                              <i class="material-icons">live_help</i> Ayuda
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#" class="grey-text text-darken-1">
                              <i class="material-icons">lock_outline</i> Bloquear
                            </a>
                        </li>
                        <li>
                            <a href="{{Config::get('syslab.path_url_web')}}/logout" class="grey-text text-darken-1">
                              <i class="material-icons">keyboard_tab</i> Salir
                            </a>
                        </li>
                    </ul>
                    <a class="btn-flat dropdown-button waves-effect waves-light white-text profile-btn" href="#" data-activates="profile-dropdown-nav">
                        <span style="text-transform:lowercase !important;">{{\Auth::user()->email}}</span>
                        <i class="mdi-navigation-arrow-drop-down right"></i>
                    </a>
                    <p class="user-roal">{{\Session::get('name_security_roles')}}</p>
                </div>
              </div>
            </li>
            <li class="no-padding center" id="liMenuIzqContainer">
                <div class="preloader-wrapper big active">
                    <div class="spinner-layer spinner-blue">
                        <div class="circle-clipper left">
                            <div class="circle"></div>
                        </div>
                        <div class="gap-patch">
                            <div class="circle"></div>
                        </div>
                        <div class="circle-clipper right">
                            <div class="circle"></div>
                        </div>
                    </div>
                    <div class="spinner-layer spinner-red">
                        <div class="circle-clipper left">
                            <div class="circle"></div>
                        </div>
                        <div class="gap-patch">
                            <div class="circle"></div>
                        </div>
                        <div class="circle-clipper right">
                            <div class="circle"></div>
                        </div>
                    </div>
                    <div class="spinner-layer spinner-yellow">
                        <div class="circle-clipper left">
                            <div class="circle"></div>
                        </div>
                        <div class="gap-patch">
                            <div class="circle"></div>
                        </div>
                        <div class="circle-clipper right">
                            <div class="circle"></div>
                        </div>
                    </div>
                    <div class="spinner-layer spinner-green">
                        <div class="circle-clipper left">
                            <div class="circle"></div>
                        </div>
                        <div class="gap-patch">
                            <div class="circle"></div>
                        </div>
                        <div class="circle-clipper right">
                            <div class="circle"></div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
        <a href="#" data-activates="slide-out" class="sidebar-collapse white valign waves-effect waves-light hide-on-large-only" style="top:-40px !important;">
            <i class="material-icons">menu</i>
        </a>
    </aside>
<!-- END LEFT SIDEBAR NAV-->