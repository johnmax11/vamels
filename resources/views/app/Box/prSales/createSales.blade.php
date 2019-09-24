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
            '<script type="text/javascript" src="'.Config::get('syslab.path_url_web').'/js/_helpers/autocomplete-1.0/js/jquery.autocomplete.min.js"></script>'
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
    <h5 class="breadcrumbs-title">Crear venta</h5>
    <div class="divider"></div><br/>
    <div class="col s12" id="divStatusResponse"></div>
    
    <div class="row">
        <form id="frmDatos" class="col s12" enctype="multipart/form-data">
            <input type="hidden" id="hdnContRows" name="hdnContRows" value="1">
            <div class="row">
                <div class="input-field col s12 m4">
                    <i class="material-icons prefix">date_range</i>
                    <input type="text" id="txtFechaVenta" name="txtFechaVenta" class="datepicker validate" value="" max="<?php echo @date("Y-m-d"); ?>"/>
                    <label for="txtFechaVenta">Fecha</label>
                </div>
                <div class="input-field col s12 m4">
                    <select id="selVendedor" name="selVendedor" class="validate">
                        <option value="">...</option>
                    </select>
                    <label for="selVendedor">Vendedor</label>
                </div>
                <div class="progress input-field col s12 m3">
                            <input type="text" id="txtCliente" name="txtCliente" class="indeterminate validate"/>
                            <label for="txtCliente">Cliente</label>
                </div>
                <div class="input-field col s12 m1">
                    <i id="lblPlusCliente" class="material-icons prefix" style="cursor:pointer;">add_box</i>
                </div>
            </div>
                                
            <div class="row">
                <div class="col s12 m1" style="text-align: center;">
                    <label class="control-label">#</label>
                </div>
                <div class="col s12 m2" style="text-align: center;">
                    <label class="control-label">Codigo</label>
                </div>
                <div class="col s12 m3" style="text-align: center;">
                    <label class="control-label">Vr. Venta</label>
                </div>
                <div class="col s12 m2" style="text-align: center;">
                    <label class="control-label">Descuento</label>
                </div>
                <div class="col s12 m3" style="text-align: center;">
                    <label class="control-label">Total</label>
                </div>
                <div class="col s12 m1">
                    <label class="control-label">&nbsp;</label>
                </div>
            </div>
            
            <div id="divRow0" class="row">
                <div class="input-field col s12 m1" style="text-align: center;">
                    <label id="lblNumeroRow-0" class="form-control">1</label>
                </div>
                <div class="input-field col s12 m2">
                    <select id="selCodigo-0" name="selCodigo-0" class="validate" style="width: 100%;">
                        <option value="">...</option>
                    </select>
                </div>
                <div class="input-field col s12 m3">
                    <i class="material-icons prefix">attach_money</i>
                    <input type="number" id="txtVrVenta-0" name="txtVrVenta-0" class="validate"  value="0" disabled="disabled"/>
                </div>
                <div class="input-field col s12 m2">
                    <i class="material-icons prefix">attach_money</i>
                    <input type="number" id="txtDescuento-0" name="txtDescuento-0" class="validate" value="0"/>
                </div>
                <div class="input-field col s12 m3" style="text-align: center;">
                    <i class="material-icons prefix">attach_money</i>
                    <label id="lblTotal-0" class="label-default">0</label>
                </div>
                <div class="input-field col s12 m1" style="text-align: center;">
                    <i id="lblPlus-0" class="material-icons prefix" style="cursor:pointer;">add_box</i>
                </div>
            </div>

            <div id="divFooter" class="row">
                <div class="input-field col s12 m1">&nbsp;</div>
                <div class="input-field col s12 m4">&nbsp;</div>
                <div class="input-field col s12 m2">&nbsp;</div>
                <div class="input-field col s12 m1">&nbsp;</div>
                <div class="input-field col s12 m2" style="text-align: center;">
                    <i class="material-icons prefix">attach_money</i>
                    <label id="lblTotalFooter" class="form-control label-default">0</label>
                </div>
                <div class="input-field col s12 m1" style="text-align: center;">&nbsp;</div>
            </div>
                                
        </form>
                            
        <!-- div de botton -->
        <div class="form-horizontal">
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <button id="btnGuardar" type="submit" class="btn btn-custom green">Guardar Informaci&oacute;n</button>
                </div>
            </div>
        </div>
    </div>
</div>
@stop