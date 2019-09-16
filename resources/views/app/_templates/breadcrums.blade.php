@if($datosRecurso['module'] != "home")
    <nav id="navBreadCrums">
        <div class="nav-wrapper teal accent-4">
            <div class="col s12">
                
                <span class="breadcrumb">
                    <a href="{{\Config::get('syslab.path_url_web')}}/app/home/main/read">
                        <i class="material-icons">&nbsp;home</i>
                    </a>
                </span>
                <span class="breadcrumb">{{$datosRecurso['n_module']}}</span>
                <span class="breadcrumb">{{$datosRecurso['n_action']}}</span>
            </div>
        </div>
    </nav>
@endif