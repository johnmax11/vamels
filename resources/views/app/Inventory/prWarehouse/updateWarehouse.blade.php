@section('script')
    <?php
        $furl = "js/Inventory/Warehouse/updateWarehouse.js";
    ?>
    @include ('app._templates.includes')
    {!! HTML::script('js/_helpers/filestyle-1.0/js/bootstrap-filestyle.min.js') !!}
    {!! HTML::script('js/_helpers/uploader-1.0/js/sy_uploaderFiles.js') !!}
    
    {!! HTML::script($furl) !!}
@show
@extends('app._templates.master')
@section ('content')
<style>
    fieldset.scheduler-border {
        border: 1px groove #ddd !important;
        padding: 0 1.4em 1.4em 1.4em !important;
        margin: 0 0 1.5em 0 !important;
        -webkit-box-shadow:  0px 0px 0px 0px #000;
                box-shadow:  0px 0px 0px 0px #000;
    }

    legend.scheduler-border {
        font-size: 1.2em !important;
        font-weight: bold !important;
        text-align: left !important;

    }
</style>
<div  id="content-wrapper" class="content-wrapper">
    <article class="">
        <!-- Content Header (Page header) -->
        @include ('app._templates.header_content')
        
        <section class="content">
            <div class="row">
                <section class="col-md-12">
                    <div class="box box-custom">
                        <div class="box-header with-border">
                            <h3 class="box-title">Actualizar Registro</h3>
                        </div><!-- /.box-header -->
                        
                        <div class="box-body">
                            <form id="frmDatos" class="form-horizontal" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label class="control-label col-sm-2">Producto</label>
                                    <div class="col-sm-10">
                                        <input type="text" id="txtProducto" disabled="disabled" class="form-control disabled"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2">Actual</label>
                                    <div class="col-sm-10">
                                        <input type="text" id="txtCantidad" disabled="disabled" class="form-control disabled"/>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="control-label col-sm-2">Nuevo<b style="color:red;">*</b></label>
                                    <div class="col-sm-10">
                                        <input type="number" id="txtCantidadNueva" name="txtCantidadNueva" class="form-control validate format-number"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2">Motivo<b style="color:red;">*</b></label>
                                    <div class="col-sm-10">
                                        <select id="selMotivo" name="selMotivo" class="form-control validate">
                                            <option value="">Seleccione...</option>
                                            <option value="Ajuste por inventario">Ajuste por inventario</option>
                                            <option value="Entrada a inventario">Entrada a inventario</option>
                                            <option value="Salida para Vamels">Salida para Vamels</option>
                                            <option value="Entrada desde Vamels">Entrada desde Vamels</option>
                                            <option value="Dado de baja">Dado de baja</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2">Motivo</label>
                                    <div class="col-sm-10">
                                        <textarea id="tarObservaciones" name="tarObservaciones" class="form-control" style="width:100%;"></textarea>
                                    </div>
                                </div>
                            </form>
                            
                            <!-- div de botton -->
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                      <button id="btnGuardar" type="submit" class="btn btn-custom">Guardar Informaci&oacute;n</button>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </section>
            </div>
        </section>
    </article>
</div>


@stop