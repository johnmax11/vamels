@section('script')
    <!-- include javascript local view -->
    <?php
        $arrUrl = explode("/",\Request::url());
        if(isset($arrUrl[8])){
            $furl = ucfirst($arrUrl[6])."/".ucfirst($arrUrl[7])."/".$arrUrl[8].ucfirst($arrUrl[7]).".js";
        }else{
            $furl = ucfirst($arrUrl[5])."/".ucfirst($arrUrl[6])."/".$arrUrl[7].ucfirst($arrUrl[6]).".js";
        }
    ?>
@show
@extends('app._templates.master')
@section ('content')
    <div id="work-collections">
        <div class="row">
            @if(\Auth::user()->security_roles_id == 1)
                <div class="col s12 m12 l6">
                    <ul id="issues-collection" class="collection z-depth-1">
                        <li class="collection-item avatar">
                            <i class="material-icons red accent-2 circle">people</i>
                            <h6 class="collection-header m-0">Testigos</h6>
                            <p>Ingresados</p>
                        </li>
                        <li class="collection-item">
                            <div class="row">
                                <div class="col s7">
                                    <p class="collections-title">
                                      <strong>#102</strong> Home Page</p>
                                    <p class="collections-content">Web Project</p>
                                </div>
                                <div class="col s2">
                                    <span class="task-cat deep-orange accent-2">P1</span>
                                </div>
                                <div class="col s3">
                                    <div class="progress">
                                        <div class="determinate" style="width: 70%"></div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            @endif
            @if(\Auth::user()->security_roles_id == 1)
                @for($j=0;$j<count($arrDTask);$j++)
                    <div class="col s12 m12">
                        <div class="card gradient-45deg-light-red-cyan gradient-shadow">
                            <div class="card-content black-text">
                                <span class="card-title">CT:{{$arrDTask[$j]->id}} - Mesa # {{$arrDTask[$j]->number_table}}</span>
                                <div>
                                    {{$arrDTask[$j]->place_name}}
                                </div>
                                <div style="font-style:italic;">
                                    Zona: {{$arrDTask[$j]->zone_code}} 
                                    Puesto: {{$arrDTask[$j]->place_code}}
                                </div>
                                <div>
                                    {{$arrDTask[$j]->city_name}} / {{$arrDTask[$j]->department_name}}
                                </div>
                                <div style="font-family:verdana;font-size:8pt;">
                                    {{$arrDTask[$j]->address_place}}
                                </div>
                                <hr/>
                                <div>
                                    @if($arrDTask[$j]->arr_images == null)
                                        <h6>No haz cargado ninguna imagen ;(</h6>
                                    @else
                                        @php 
                                            $cor = null;
                                            $corPrint = "";
                                        @endphp
                                        @for($i=0;$i<count($arrDTask[$j]->arr_images);$i++)
                                            @if($cor != $arrDTask[$j]->arr_images[$i]->corporation)
                                                @php
                                                    $cor = $arrDTask[$j]->arr_images[$i]->corporation;
                                                    $corPrint = $arrDTask[$j]->arr_images[$i]->corporation;
                                                @endphp
                                                @if($cor == "COO")
                                                    @php
                                                        $corPrint = "CON";
                                                    @endphp
                                                @endif
                                                <b style="font-size:8pt;">{{$corPrint}}</b>
                                            @endif
                                            
                                            <a href="{{Config::get('syslab.path_url_web')}}/app/collaborators/tasks/download/{{$arrDTask[$j]->arr_images[$i]->id_task}}/{{$arrDTask[$j]->arr_images[$i]->photo}}" class="waves-effect waves-light" target="_blank">
                                                <i class="material-icons postfix" style="color:green;font-size:32px;">image</i>
                                            </a>
                                        @endfor
                                    @endif
                                </div>
                            </div>
                            <div class="card-action">
                                <a href="{{Config::get('syslab.path_url_web')}}/app/collaborators/tasks/update/{{$arrDTask[$j]->id}}" class="waves-effect waves-light btn red">Ingresar</a>
                            </div>
                        </div>
                    </div>
                @endfor
            @endif
        </div>
    </div>
@stop