    <!--================================================================================
          Item Name: Laravel - JJCortes - Materialize
          Version: 1.0
          Author: JJCortes <johnmax11@hotmail.com>
      ================================================================================ -->
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="msapplication-tap-highlight" content="no">
        <meta name="_token" content="{{{ Session::token() }}}">
        <title>
            {{(isset($datosRecurso['n_action'])?$datosRecurso['n_action']." |":"")}} {{Config::get('syslab.title')}}
        </title>
        <!-- Favicons-->
        <link rel="icon" href="{{Config::get('syslab.path_url_web')."/".Config::get('syslab.favicon')}}" sizes="32x32">
        <meta name="msapplication-TileColor" content="#E80404">
        <!-- For Windows Phone -->
        <!-- CORE CSS-->
        <link href="{{Config::get('syslab.path_url_web')}}/js/_helpers/materialize-admin-4.0/materialize-admin/css/themes/collapsible-menu/materialize.css" type="text/css" rel="stylesheet">
        <link href="{{Config::get('syslab.path_url_web')}}/js/_helpers/materialize-admin-4.0/materialize-admin/css/themes/collapsible-menu/style.css" type="text/css" rel="stylesheet">
        <!-- Custome CSS-->
        <link href="{{Config::get('syslab.path_url_web')}}/js/_helpers/materialize-admin-4.0/materialize-admin/css/custom/custom.css" type="text/css" rel="stylesheet">
        <!-- INCLUDED PLUGIN CSS ON THIS PAGE -->
        <link href="{{Config::get('syslab.path_url_web')}}/js/_helpers/materialize-admin-4.0/materialize-admin/vendors/perfect-scrollbar/perfect-scrollbar.css" type="text/css" rel="stylesheet">
        <link href="{{Config::get('syslab.path_url_web')}}/js/_helpers/materialize-admin-4.0/materialize-admin/vendors/flag-icon/css/flag-icon.min.css" type="text/css" rel="stylesheet">
    </head>
    