@section('script')
    <!-- include javascript local view -->
    <?php
        $arrUrl = explode("/",\Request::url());
        if(count($arrUrl)==9){
            $furl = ucfirst($arrUrl[6])."/".ucfirst($arrUrl[7])."/".$arrUrl[8].ucfirst($arrUrl[7]).".js";
        }elseif(count($arrUrl)==10){
            $furl = ucfirst($arrUrl[6])."/".ucfirst($arrUrl[6])."/".$arrUrl[8].ucfirst($arrUrl[7]).".js";
        }
        $arrExtraScript = array(
            
        );
        
    ?>
@show
@extends('app._templates.master')
@section ('content')

<div  id="card-panel" class="section card-panel">
    <h5 class="breadcrumbs-title">Escrutinios Concejo</h5>
    <div class="divider"></div><br/>
    <div class="col s12" id="divStatusResponse"></div>
    
    <div class="row">
        <form id="frmDatos" class="col s12" enctype="multipart/form-data">
            
            <div class="row">
                <div class="input-field col s12 m6">
                    <select id="selDepartamento" name="selDepartamento" class="validate verify">
                        <option value="31">VALLE</option>
                    </select>
                    <label for="selDepartamento">Departamento</label>
                </div>
                <div class="input-field col s12 m6">
                    <select id="selMunicipio" name="selMunicipio" class="validate verify">
                        <option value="1">CALI</option>
                    </select>
                    <label for="selMunicipio">Municipio</label>
                </div>
            </div> <!-- END ROW PRINCIPAL -->
            
            <div class="row">
                <div class="input-field col s12 m6">
                    <select id="selZona" name="selZona" class="validate verify">
                        <option value="">...</option>
                    </select>
                    <label for="selZona">Zona</label>
                </div>
                <div class="input-field col s12 m6">
                    <select id="selPuesto" name="selPuesto" class="validate verify">
                        <option value="">...</option>
                    </select>
                    <label for="selPuesto">Puesto</label>
                </div>
            </div> <!-- END ROW PRINCIPAL -->
            
            <div class="row">
                <div id="divTables"></div>
            </div>
            
        </form> <!-- END FORM -->
                            
        <!-- div de botton -->
        <div class="form-horizontal">
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <a id="bttnSave" href="javascript:void(0)" class="waves-effect waves-light btn red">
                        Guardar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@stop