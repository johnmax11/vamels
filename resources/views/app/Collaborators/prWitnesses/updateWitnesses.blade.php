@section('script')
    <!-- include javascript local view -->
    <?php
        $arrUrl = explode("/",\Request::url());
        if(isset($arrUrl[8])){
            $furl = ucfirst($arrUrl[5])."/".ucfirst($arrUrl[6])."/".$arrUrl[7].ucfirst($arrUrl[6]).".js";
        }
        
        $arrExtraScript = array(
            '<script type="text/javascript" src="'.Config::get('syslab.path_url_web').'/js/_helpers/autocomplete-1.0/js/jquery.autocomplete.min.js"></script>',
            '<script type="text/javascript" src="'.Config::get('syslab.path_url_web').'/js/_helpers/uploader-1.0/js/sy_uploaderFiles.js"></script>',
            '<link href="'.Config::get('syslab.path_url_web').'/js/_helpers/materialize-admin-4.0/materialize-admin/vendors/sweetalert/dist/sweetalert.css" type="text/css" rel="stylesheet">',
            '<script type="text/javascript" src="'.Config::get('syslab.path_url_web').'/js/_helpers/materialize-admin-4.0/materialize-admin/vendors/sweetalert/dist/sweetalert.min.js"></script>'
        );
    ?>
    
@show
@extends('app._templates.master')
@section ('content')
<style>
    .autocomplete-suggestions { border: 1px solid #999; background: #FFF; overflow: auto; }
    .autocomplete-suggestion { padding: 2px 5px; white-space: nowrap; overflow: hidden; }
    .autocomplete-selected { background: #F0F0F0; }
    .autocomplete-suggestions strong { font-weight: normal; color: #3399FF; }
    .autocomplete-group { padding: 2px 5px; }
    .autocomplete-group strong { display: block; border-bottom: 1px solid #000; }s
</style>
<div  id="card-panel" class="section card-panel">
    <h5 class="breadcrumbs-title">Actualizar testigo</h5>
    <div class="divider"></div><br/>
    <div class="col s12" id="divStatusResponse"></div>
    
    <div class="row">
        <input type="hidden" id="hdnIdUs" name="hdnIdUs" value="{{\Auth::user()->id}}"/>
        <form id="frmDatos" class="col s12" enctype="multipart/form-data">
            <div class="row">
                <div class="input-field col s12 m1">
                    <input type="number" id="txtId" name="txtId" disabled="deisabled"/>
                    <label for="txtId">ID</label>
                </div>
                <div class="input-field col s12 m4">
                    <i class="material-icons prefix">datepicker</i>
                    <input type="text" id="txtFechaCreacion" name="txtFechaCreacion" disabled="deisabled"/>
                    <label for="txtFechaCreacion">Creado el</label>
                </div>
                <div class="input-field col s12 m4">
                    <i class="material-icons prefix">closed_caption</i>
                    <input type="number" id="txtNumeroIdentificacion" name="txtNumeroIdentificacion" class="validate verify"/>
                    <input type="hidden" id="hdnNumeroIdentificacion"/>
                    <label for="txtNumeroIdentificacion">Número identificación</label>
                </div>
            </div>
            
            <div class="row">
                <div class="input-field col s12 m3">
                    <input type="text" id="txtPrimerApellido" name="txtPrimerApellido" class="validate verify"/>
                    <label for="txtPrimerApellido">Primer apellido</label>
                </div>
                <div class="input-field col s12 m3">
                    <input type="text" id="txtSegundoApellido" name="txtSegundoApellido" class="validate"/>
                    <label for="txtSegundoApellido">Segundo apellido</label>
                </div>
                <div class="input-field col s12 m3">
                    <input type="text" id="txtPrimerNombre" name="txtPrimerNombre" class="validate verify"/>
                    <label for="txtPrimerNombre">Primer nombre</label>
                </div>
                <div class="input-field col s12 m3">
                    <input type="text" id="txtSegundoNombre" name="txtSegundoNombre" class="validate"/>
                    <label for="txtSegundoNombre">Segundo nombre</label>
                </div>
            </div>
            
            <div class="row">
                <div class="input-field col s12 m3">
                    <i class="material-icons prefix">phone</i>
                    <input type="number" id="numNumeroTelefono" name="numNumeroTelefono" class="validate verify"/>
                    <label for="numNumeroTelefono">Número teléfono</label>
                </div>
                <div class="input-field col s12 m5">
                    <select id="selPartidoPolitico" name="selPartidoPolitico" class="validate verify">
                        <option value="9">Partido social de la unidad nacional</option>
                    </select>
                    <label for="selPartidoPolitico">Partido politico</label>
                </div>
                <div class="input-field col s12 m4">
                    <select id="selDepartamento" name="selDepartamento" class="validate verify">
                        <option value="31">Valle del cauca</option>
                    </select>
                    <label for="selDepartamento">Departamento</label>
                </div>
            </div>
            
            <div class="row">
                <div class="input-field col s12 m3">
                    <select id="selCiudad" name="selCiudad" class="validate"></select>
                    <label for="selCiudad">Municipio</label>
                </div>
                <div class="input-field col s12 m9">
                    <i class="material-icons prefix">add_location</i>
                    <input type="text" id="txtPuestoVotacion" class="validate verify"/>
                    <input type="hidden" id="hdnPuestoVotacion" name="hdnPuestoVotacion" class=" verify"/>
                    <input type="hidden" id="hdnPuestoVotacionMesas"/>
                    <input type="hidden" id="hdnPuestoVotacionMesasUsed"/>
                    <label id="lblPuestoVotacion" for="txtPuestoVotacion">Puesto votación</label>
                </div>
            </div>
            
            <div class="row">
                <div class="input-field col s12 m4">
                    <div class="file-field input-field">
                        <div class="btn blue">
                            <span>Foto 1</span>
                            <input type="file" id="filImagen1" name="filImagen1" accept=".png,.jpg,.jpeg,.pdf" />
                        </div>
                        <div class="file-path-wrapper">
                          <input id="txtImagen1" class="file-path validate" type="text"/>
                        </div>
                    </div>
                </div>
                <div class="input-field col s12 m1">
                    <div id="succ-filImagen1"  class="file-field input-field" style="display:none;">
                        <i class="material-icons" style="color:green;font-size:32px;">check_circle</i>
                    </div>
                </div>
                <div class="input-field col s12 m1">
                    <a id="aImagen1" href="" target="_blank" style="display:none;">
                        Ver
                        <i class="material-icons" style="color:red;font-size:32px;">file_download</i>
                    </a>
                </div>
                
                <div class="input-field col s12 m4">
                    <div class="file-field input-field">
                        <div class="btn blue">
                            <span>Foto 2</span>
                            <input type="file" id="filImagen2" name="filImagen2" accept=".png,.jpg,.jpeg,.pdf" />
                        </div>
                        <div class="file-path-wrapper">
                          <input id="txtImagen2" class="file-path validate" type="text">
                        </div>
                    </div>
                </div>
                <div class="input-field col s12 m1">
                    <div id="succ-filImagen2" class="file-field input-field" style="display:none;">
                        <i class="material-icons postfix" style="color:green;font-size:32px;">check_circle</i>
                    </div>
                </div>
                <div class="input-field col s12 m1">
                    <a id="aImagen2" href="" target="_blank" style="display:none;">
                        Ver
                        <i class="material-icons" style="color:red;font-size:32px;">file_download</i>
                    </a>
                </div>
                <!-- END imagen -->
            </div>
                                
        </form>
                            
        <!-- div de botton -->
        <div class="form-horizontal">
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <button id="btnGuardar" type="submit" class="btn btn-custom blue">Actualizar Informaci&oacute;n</button>
                </div>
            </div>
        </div>
    </div>
</div>
@stop