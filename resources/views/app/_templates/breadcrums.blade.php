@if($datosRecurso['module'] != "home")
    <nav id="navBreadCrums">
        <div class="nav-wrapper {{Config::get('syslab.style_background')}} accent-4">
            <div class="col s12">
                <span class="breadcrumb">
                    <a href="{{\Config::get('syslab.path_url_web')}}/app/home/main/read">
                        <i class="material-icons">&nbsp;home</i>
                    </a>
                </span>
                <span class="breadcrumb" style="font-size:10pt;">{{$datosRecurso['n_module']}}</span>
                <span class="breadcrumb" style="font-size:10pt;">
                    @if($datosRecurso['back_bttn'] == "Y")
                        <a href="{{\Config::get('syslab.path_url_web')}}/app/{{$datosRecurso['module']}}/{{$datosRecurso['action']}}/read">
                    @endif
                            {{$datosRecurso['n_action']}}
                    @if($datosRecurso['back_bttn'] == "Y")
                        </a>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif