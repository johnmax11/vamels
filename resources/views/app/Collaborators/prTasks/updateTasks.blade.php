@section('script')
    <!-- include javascript local view -->
    <?php
        $arrUrl = explode("/",\Request::url());
        
        if(count($arrUrl)==9){
            $furl = ucfirst($arrUrl[5])."/".ucfirst($arrUrl[6])."/".$arrUrl[7].ucfirst($arrUrl[6]).".js";
        }elseif(count($arrUrl)==10){
            $furl = ucfirst($arrUrl[6])."/".ucfirst($arrUrl[7])."/".$arrUrl[8].ucfirst($arrUrl[7]).".js";
        }
        $arrExtraScript = array(
            '<script src="'.Config::get('syslab.path_url_web').'/js/_helpers/materialize-admin-4.0/materialize-admin/vendors/jquery-ui.custom.js"></script>',
            //'<link href="'.Config::get('syslab.path_url_web').'/js/_helpers/uploader-2.0/css/uploadfile.css" rel="stylesheet">',
            '<script src="'.Config::get('syslab.path_url_web').'/js/_helpers/uploader-3.0/jquery.fileupload.js"></script>',
            //'<script type="text/javascript" src="'.Config::get('syslab.path_url_web').'/js/_helpers/uploader-1.0/js/sy_uploaderFiles.js"></script>',
            '<link href="'.Config::get('syslab.path_url_web').'/js/_helpers/materialize-admin-4.0/materialize-admin/vendors/dropify/css/dropify.min.css" type="text/css" rel="stylesheet">',
            '<script type="text/javascript" src="'.Config::get('syslab.path_url_web').'/js/_helpers/materialize-admin-4.0/materialize-admin/vendors/dropify/js/dropify.min.js"></script>',
            '<link href="'.Config::get('syslab.path_url_web').'/js/_helpers/materialize-admin-4.0/materialize-admin/vendors/sweetalert/dist/sweetalert.css" type="text/css" rel="stylesheet">',
            '<script type="text/javascript" src="'.Config::get('syslab.path_url_web').'/js/_helpers/materialize-admin-4.0/materialize-admin/vendors/sweetalert/dist/sweetalert.min.js"></script>'
        );
        
    ?>
@show
@extends('app._templates.master')
@section ('content')

<div  id="card-panel" class="section card-panel">
    <h5 class="breadcrumbs-title">Completar tarea</h5>
    <div class="divider"></div><br/>
    <div class="col s12" id="divStatusResponse"></div>
    
    <div class="row">
        <input type="hidden" id="hdnIdUs" name="hdnIdUs" value="{{\Auth::user()->id}}"/>
        <input type="hidden" id="hdnArrImg" name="hdnArrImg" value="{{json_encode($arrImgTasks)}}"/>
        <form id="frmDatos" class="col s12" enctype="multipart/form-data">
            
            <div class="row">
                <div id="tabs" class="col s12">
                    <ul class="tabs">
                        <li class="tab col s2"><a class="active gradient-45deg-green-teal white-text" href="#alcaldia" style="font-size:9pt;">Alcaldia</a></li>
                        <li class="tab col s2"><a class="gradient-45deg-red-pink white-text" href="#gobernacion" style="font-size:9pt;">Gobernación</a></li>
                        <li class="tab col s2"><a class="gradient-45deg-purple-deep-orange white-text" href="#concejo" style="font-size:9pt;">Concejo</a></li>
                        <li class="tab col s2"><a class="gradient-45deg-amber-amber white-text" href="#asamblea" style="font-size:9pt;">Asamblea</a></li>
                        <li class="tab col s2"><a class="gradient-45deg-blue-indigo white-text" href="#jal" style="font-size:9pt;">JAL</a></li>
                    </ul>
                    
                    <!-- END TAB ALCALDIA -->
                    <div id="alcaldia" class="col s12 green lighten-4">
                        <div class="section">
                            <div class="section">
                                <div class="row">
                                    <!-- START imagen 1 -->
                                    <div class="col s12 m12">
                                        <input type="file" id="filImagenALC" name="filImagenALC" class="dropify" accept=".png,.jpg,.jpeg" data-allowed-file-extensions="jpg png jpeg"/>
                                    </div>
                                    <!-- END imagen 1 -->

                                    @php
                                        $conR = ($arrImgTasks!=null?count($arrImgTasks):0);
                                    @endphp
                                    @for($i=0; $i<$conR; $i++)
                                        @if($arrImgTasks[$i]->corporation == "ALC")
                                            <div class="col s12 m12">
                                                <input 
                                                    type="file" 
                                                    id="filImagenALC{{$arrImgTasks[$i]->id_image_task}}" 
                                                    class="dropify" 
                                                    accept=".png,.jpg,.jpeg" 
                                                    data-allowed-file-extensions="jpg png jpeg" 
                                                    id-image="{{$arrImgTasks[$i]->id_image_task}}"
                                                    data-default-file="{{Config::get('syslab.path_url_web')}}/app/collaborators/tasks/download/{{(int)$arrImgTasks[$i]->id_task}}/{{$arrImgTasks[$i]->photo}}" 
                                                    data-show-remove="false"
                                                />
                                            </div>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div> 
                    <!-- END TAB ALCALDIA -->

                    <!-- END TAB GOBERNACION -->
                    <div id="gobernacion" class="col s12 red lighten-4">
                        <div class="section">
                            <div class="section">
                                <div class="row">
                                    <!-- START imagen 1 -->
                                    <div class="col s12 m12">
                                        <input type="file" id="filImagenGOB" name="filImagenGOB" class="dropify" accept=".png,.jpg,.jpeg" data-allowed-file-extensions="jpg png jpeg"/>
                                    </div>
                                    <!-- END imagen 1 -->

                                    @php
                                        $conR = ($arrImgTasks!=null?count($arrImgTasks):0);
                                    @endphp
                                    @for($i=0; $i<$conR; $i++)
                                        @if($arrImgTasks[$i]->corporation == "GOB")
                                            <div class="col s12 m12">
                                                <input 
                                                    type="file" 
                                                    id="filImagenGOB{{$arrImgTasks[$i]->id_image_task}}" 
                                                    class="dropify" 
                                                    accept=".png,.jpg,.jpeg" 
                                                    data-allowed-file-extensions="jpg png jpeg" 
                                                    id-image="{{$arrImgTasks[$i]->id_image_task}}"
                                                    data-default-file="{{Config::get('syslab.path_url_web')}}/app/collaborators/tasks/download/{{(int)$arrImgTasks[$i]->id_task}}/{{$arrImgTasks[$i]->photo}}" 
                                                    data-show-remove="false"
                                                />
                                            </div>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div> 
                    <!-- END TAB GOBERNACION -->

                    <!-- END TAB CONCEJO -->
                    <div id="concejo" class="col s12 purple lighten-4">
                        <div class="section">
                            <div class="section">
                                <div class="row">
                                    <!-- START imagen 1 -->
                                    <div class="col s12 m12">
                                        <input type="file" id="filImagenCOO" name="filImagenCOO" class="dropify" accept=".png,.jpg,.jpeg" data-allowed-file-extensions="jpg png jpeg"/>
                                    </div>
                                    <!-- END imagen 1 -->

                                    @php
                                        $conR = ($arrImgTasks!=null?count($arrImgTasks):0);
                                    @endphp
                                    @for($i=0; $i<$conR; $i++)
                                        @if($arrImgTasks[$i]->corporation == "COO")
                                            <div class="col s12 m12">
                                                <input 
                                                    type="file" 
                                                    id="filImagenCOO{{$arrImgTasks[$i]->id_image_task}}" 
                                                    class="dropify" 
                                                    accept=".png,.jpg,.jpeg" 
                                                    data-allowed-file-extensions="jpg png jpeg" 
                                                    id-image="{{$arrImgTasks[$i]->id_image_task}}"
                                                    data-default-file="{{Config::get('syslab.path_url_web')}}/app/collaborators/tasks/download/{{(int)$arrImgTasks[$i]->id_task}}/{{$arrImgTasks[$i]->photo}}" 
                                                    data-show-remove="false"
                                                />
                                            </div>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div> 
                    <!-- END TAB CONCEJO -->

                    <!-- START TAB ASAMBLEA -->
                    <div id="asamblea" class="col s12 amber lighten-4">
                        <div class="section">
                            <div class="section">
                                <div class="row">
                                    <!-- START imagen 1 -->
                                    <div class="col s12 m12">
                                        <input type="file" id="filImagenASA" name="filImagenASA" class="dropify" accept=".png,.jpg,.jpeg" data-allowed-file-extensions="jpg png jpeg"/>
                                    </div>
                                    <!-- END imagen 1 -->

                                    @php
                                        $conR = ($arrImgTasks!=null?count($arrImgTasks):0);
                                    @endphp
                                    @for($i=0; $i<$conR; $i++)
                                        @if($arrImgTasks[$i]->corporation == "ASA")
                                            <div class="col s12 m12">
                                                <input 
                                                    type="file" 
                                                    id="filImagenASA{{$arrImgTasks[$i]->id_image_task}}" 
                                                    class="dropify" 
                                                    accept=".png,.jpg,.jpeg" 
                                                    data-allowed-file-extensions="jpg png jpeg" 
                                                    id-image="{{$arrImgTasks[$i]->id_image_task}}"
                                                    data-default-file="{{Config::get('syslab.path_url_web')}}/app/collaborators/tasks/download/{{(int)$arrImgTasks[$i]->id_task}}/{{$arrImgTasks[$i]->photo}}" 
                                                    data-show-remove="false"
                                                />
                                            </div>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div> 
                    <!-- END TAB ASAMBLEA -->

                    <!-- START TAB JAL -->
                    <div id="jal" class="col s12 blue lighten-4">
                        <div class="section">
                            <div class="section">
                                <div class="row">
                                    <!-- START imagen 1 -->
                                    <div class="col s12 m12">
                                        <input type="file" id="filImagenJAL" name="filImagenJAL" class="dropify" accept=".png,.jpg,.jpeg" data-allowed-file-extensions="jpg png jpeg"/>
                                    </div>
                                    <!-- END imagen 1 -->

                                    @php
                                        $conR = ($arrImgTasks!=null?count($arrImgTasks):0);
                                    @endphp
                                    @for($i=0; $i<$conR; $i++)
                                        @if($arrImgTasks[$i]->corporation == "JAL")
                                            <div class="col s12 m12">
                                                <input 
                                                    type="file" 
                                                    id="filImagenJAL{{$arrImgTasks[$i]->id_image_task}}" 
                                                    class="dropify" 
                                                    accept=".png,.jpg,.jpeg" 
                                                    data-allowed-file-extensions="jpg png jpeg" 
                                                    id-image="{{$arrImgTasks[$i]->id_image_task}}"
                                                    data-default-file="{{Config::get('syslab.path_url_web')}}/app/collaborators/tasks/download/{{(int)$arrImgTasks[$i]->id_task}}/{{$arrImgTasks[$i]->photo}}" 
                                                    data-show-remove="false"
                                                />
                                            </div>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div> 
                    <!-- END TAB JAL -->
                </div>
                
            </div> <!-- END ROW PRINCIPAL -->
        </form> <!-- END FORM -->
                            
        <!-- div de botton -->
        <div class="form-horizontal">
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <a href="{{Config::get('syslab.path_url_web')}}/app/home/main/read" class="waves-effect waves-light btn red">
                        << Volver
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@stop