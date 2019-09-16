    @include ('app._templates.header_preload')
    <!-- //////////////////////////////////////////////////////////////////////////// -->
    <!-- START HEADER -->
    <header id="header" class="page-topbar">
      <!-- start header nav-->
      <div class="navbar-fixed">
        <nav class="navbar-color teal">
            <div class="nav-wrapper">
                <ul class="right hide-on-med-and-down">
                    <li>
                        <a href="javascript:void(0);" class="waves-effect waves-block waves-light toggle-fullscreen" title="Pantalla completa">
                            <i class="material-icons">settings_overscan</i>
                        </a>
                    </li>
                    <!--<li>
                        <a href="javascript:void(0);" class="waves-effect waves-block waves-light profile-button" data-activates="profile-dropdown">
                            <span class="avatar-status avatar-online">
                                <img src="{{Config::get('syslab.path_url_web')}}/images/sin_imagen.png" alt="avatar">
                                <i></i>
                            </span>
                        </a>
                    </li>-->
                </ul>
                <!-- profile-dropdown -->
                <ul id="profile-dropdown" class="dropdown-content">
                    <li>
                        <a href="#" class="grey-text text-darken-1">
                          <i class="material-icons">face</i> Perfl
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
            </div>
        </nav>
      </div>
    </header>
    <!-- END HEADER -->