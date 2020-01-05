@section('script')
    <!-- include javascript local view -->
    <?php
        $arrUrl = explode("/",\Request::url());
        if(isset($arrUrl[8])){
            $furl = ucfirst($arrUrl[6])."/".ucfirst($arrUrl[7])."/".$arrUrl[8].ucfirst($arrUrl[7]).".js";
        }else{
            $furl = ucfirst($arrUrl[5])."/".ucfirst($arrUrl[6])."/".$arrUrl[7].ucfirst($arrUrl[6]).".js";
        }
        $arrExtraScript = array(
            '<!-- plugins adicionales -->',
            '<link href="'.Config::get('syslab.path_url_web').'/js/_helpers/materialize-admin-4.0/materialize-admin/vendors/data-tables/css/jquery.dataTables.min.css" type="text/css" rel="stylesheet">',
            '<!-- data-tables -->',
            '<script type="text/javascript" src="'.Config::get('syslab.path_url_web').'/js/_helpers/materialize-admin-4.0/materialize-admin/vendors/data-tables/js/jquery.dataTables.min.js"></script>'    
        );
    ?>
@show
@extends('app._templates.master')
@section ('content')
<div class="section">
    <h5 class="breadcrumbs-title">Listado Actual</h5>
    <div class="divider"></div><br/>
    <div class="col s12" id="divStatusResponse"></div>
    
    <div id="table-datatables" class="card-panel">
        
        @if(\Session::get('name_security_roles_id') == 1)
            <div class="row">
                <div class="input-field col s12 m3">
                    <select id="selUsuarios" class="">
                        <option value="" selected>Seleccione...</option>
                        @for($i=0;$i<count($dataUsers);$i++)
                            <option value="{{$dataUsers[$i]->users_id}}" >
                                {{$dataUsers[$i]->first_name}}
                            </option>
                        @endfor
                    </select>
                    <label for="selCiudad">Usuario</label>
                </div>
            </div>
        @endif
        
        <div class="row">
            <div id="divGrillaPrincipal" class="col s12">
                <div class="progress">
                    <div class="indeterminate red"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop