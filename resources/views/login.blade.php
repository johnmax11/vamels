<!DOCTYPE html>
<html lang="en">
    <!--================================================================================
	Item Name: Laravel - JJCortes - Materialize
	Version: 1.0
	Author: JJCortes <johnmax11@hotmail.com>
	Author URL: <johnmax11@hotmail.com>
    ================================================================================ -->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="msapplication-tap-highlight" content="no">
    <meta name="_token" content="{{{ Session::token() }}}">
    <title>
        {{Config::get('syslab.title')}}
    </title>
    <!-- Favicons-->
    <link rel="icon" href="{{Config::get('syslab.path_url_web')."/".Config::get('syslab.favicon')}}" sizes="32x32">
    <meta name="msapplication-TileColor" content="#00bcd4">
    <!-- For Windows Phone -->
    <!-- CORE CSS-->
    <link href="{{Config::get('syslab.path_url_web')}}/js/_helpers/materialize-admin-4.0/materialize-admin/css/themes/collapsible-menu/materialize.css" type="text/css" rel="stylesheet">
    <link href="{{Config::get('syslab.path_url_web')}}/js/_helpers/materialize-admin-4.0/materialize-admin/css/themes/collapsible-menu/style.css" type="text/css" rel="stylesheet">
    <!-- Custome CSS-->
    <link href="{{Config::get('syslab.path_url_web')}}/js/_helpers/materialize-admin-4.0/materialize-admin/css/custom/custom.css" type="text/css" rel="stylesheet">
    <link href="{{Config::get('syslab.path_url_web')}}/js/_helpers/materialize-admin-4.0/materialize-admin/css/layouts/page-center.css" type="text/css" rel="stylesheet">
    <!-- INCLUDED PLUGIN CSS ON THIS PAGE -->
    <link href="{{Config::get('syslab.path_url_web')}}/js/_helpers/materialize-admin-4.0/materialize-admin/vendors/perfect-scrollbar/perfect-scrollbar.css" type="text/css" rel="stylesheet">
  </head>
  <body class="{{Config::get('syslab.style_background')}}">
    <!-- Start Page Loading -->
    <div id="loader-wrapper">
        <div id="loader"></div>
        <div class="loader-section section-left"></div>
        <div class="loader-section section-right"></div>
    </div>
    <!-- End Page Loading -->
    <div id="login-page" class="row">
        <div class="col s12 z-depth-4 card-panel">
            <form class="login-form" action="auth" method="post">
                <input type="hidden" id="_token" name="_token" required value="<?php echo csrf_token(); ?>"/>
                <div class="row">
                    <div class="input-field col s12 center">
                        <img src="{{Config::get('syslab.path_url_web')}}/images/logo_full.png" alt="" class="circle responsive-img valign profile-image-login">
                        <p class="center login-form-text">{{Config::get('syslab.title')}} {{Config::get('syslab.version')}}</p>
                    </div>
                </div>
                @if(\Session::has('error'))
                    <div class="col s12">
                        <div class="card-panel {{(\Session::get('error')=="error"?"red":"green")}}">
                            <span class="text-darken-2 white-text">
                                {{\Session::get('message')}}
                            </span>
                        </div>
                    </div>
                @endif
                <div class="row margin">
                    <div class="input-field col s12">
                        <i class="material-icons prefix pt-5">person_outline</i>
                        <input id="username" name="username" type="text" required/>
                        <label for="username" class="center-align">Usuario</label>
                    </div>
                </div>
                <div class="row margin">
                    <div class="input-field col s12">
                        <i class="material-icons prefix pt-5">lock_outline</i>
                        <input id="password" name="password" type="password" required/>
                        <label for="password">Contrase&ntilde;a</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <button type="submit" class="btn {{Config::get('syslab.style_background_bttn')}} waves-effect waves-light col s12">Ingresar</button>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s6 m6 l6">
                        <p class="margin medium-small"></p>
                    </div>
                    <div class="input-field col s6 m6 l6">
                        <p class="margin right-align medium-small"></p>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- ================================================
    Scripts
    ================================================ -->
    <!-- jQuery Library -->
    <script type="text/javascript" src="{{Config::get('syslab.path_url_web')}}/js/_helpers/materialize-admin-4.0/materialize-admin/vendors/jquery-3.2.1.min.js"></script>
    <!--materialize js-->
    <script type="text/javascript" src="{{Config::get('syslab.path_url_web')}}/js/_helpers/materialize-admin-4.0/materialize-admin/js/materialize.min.js"></script>
    <!--scrollbar-->
    <script type="text/javascript" src="{{Config::get('syslab.path_url_web')}}/js/_helpers/materialize-admin-4.0/materialize-admin/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <!--plugins.js - Some Specific JS codes for Plugin Settings-->
    <script type="text/javascript" src="{{Config::get('syslab.path_url_web')}}/js/_helpers/materialize-admin-4.0/materialize-admin/js/plugins.js"></script>
    <!--custom-script.js - Add your own theme custom JS-->
    <script type="text/javascript" src="{{Config::get('syslab.path_url_web')}}/js/_helpers/materialize-admin-4.0/materialize-admin/js/custom-script.js"></script>
  </body>
</html>