@section('script')
    <?php
        $furl = "js/Box/Sales/createSales.js";
    ?>
    @include ('app._templates.includes')
    {!! HTML::script('js/_helpers/filestyle-1.0/js/bootstrap-filestyle.min.js') !!}
    {!! HTML::script('js/_helpers/uploader-1.0/js/sy_uploaderFiles.js') !!}
    {!! HTML::script('js/_helpers/datepicker-1.5.1/js/bootstrap-datepicker.js') !!}
    {!! HTML::style('js/_helpers/datepicker-1.5.1/css/datepicker.css') !!}
    
    <!-- Select2 -->
    {!! HTML::style('js/_helpers/template/AdminLTE-2.3.0/plugins/select2/select2.min.css') !!}
    <!-- Select2 -->
    {!! HTML::script('js/_helpers/template/AdminLTE-2.3.0/plugins/select2/select2.full.min.js') !!}
    
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
                            <h3 class="box-title">Crear Registro</h3>
                        </div><!-- /.box-header -->
                        
                        <div class="box-body">
                            <form id="frmDatos" class="form-horizontal" enctype="multipart/form-data">
                                <input type="hidden" id="hdnContRows" name="hdnContRows" value="1">
                                <div class="row">
                                    <div class="col-sm-1">
                                        <label class="control-label">Fecha</label>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            <input type="date" id="txtFechaVenta" name="txtFechaVenta" class="form-control validate" value=""/>
                                            <label for="txtFechaVenta" class="input-group-addon btn">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-1">
                                        <label class="control-label">Vendedor</label>
                                    </div>
                                    <div class="col-sm-2">
                                        <select id="selVendedor" name="selVendedor" class="form-control validate">
                                            <option value="">...</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-1">
                                        <label class="control-label">Cliente</label>
                                    </div>
                                    <div class="col-sm-3">
                                        <select id="selCliente" name="selCliente" class="form-control validate select2">
                                            <option value="">...</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-1">
                                        <label id="lblPlusCliente" class="fa fa-plus-circle" style="cursor:pointer;" title="Crear cliente nuevo"></label>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-sm-1" style="text-align: center;">
                                        <label class="control-label">#</label>
                                    </div>
                                    <div class="col-sm-4" style="text-align: center;">
                                        <label class="control-label">Codigo</label>
                                    </div>
                                    <div class="col-sm-2" style="text-align: center;">
                                        <label class="control-label">Vr. Venta</label>
                                    </div>
                                    <div class="col-sm-2" style="text-align: center;">
                                        <label class="control-label">Descuento</label>
                                    </div>
                                    <div class="col-sm-2" style="text-align: center;">
                                        <label class="control-label">Total</label>
                                    </div>
                                    
                                    <div class="col-sm-1">
                                        <label class="control-label">&nbsp;</label>
                                    </div>
                                </div>
                                <div id="divRow0" class="row">
                                    <div class="col-sm-1" style="text-align: center;">
                                        <label id="lblNumeroRow-0" class="form-control">1</label>
                                    </div>
                                    <div class="col-sm-4">
                                        <select id="selCodigo-0" name="selCodigo-0" class="form-control select2 validate" style="width: 100%;">
                                            <option value="">...</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            <label for="txtVrVenta-0" class="input-group-addon">
                                                <span class="">$</span>
                                            </label>
                                            <input type="text" id="txtVrVenta-0" name="txtVrVenta-0" class="form-control format-number validate" disabled="disabled"/>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            <label for="txtDescuento-0" class="input-group-addon">
                                                <span class="">$</span>
                                            </label>
                                            <input type="text" id="txtDescuento-0" name="txtDescuento-0" class="form-control format-number validate" value="0"/>
                                        </div>
                                    </div>
                                    <div class="col-sm-2" style="text-align: center;">
                                        <div class="input-group">
                                            <label for="lblTotal-0" class="input-group-addon">
                                                <span class="">$</span>
                                            </label>
                                            <label id="lblTotal-0" class="form-control label-default">0</label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-1" style="text-align: center;">
                                        <label id="lblPlus-0" class="fa fa-plus-circle" style="cursor:pointer;"></label>
                                    </div>
                                </div>
                                
                                <div id="divFooter" class="row">
                                    <div class="col-sm-1">&nbsp;</div>
                                    <div class="col-sm-4">&nbsp;</div>
                                    <div class="col-sm-2">&nbsp;</div>
                                    <div class="col-sm-2">&nbsp;</div>
                                    <div class="col-sm-2" style="text-align: center;">
                                        <div class="input-group">
                                            <label for="lblTotalFooter" class="input-group-addon">
                                                <span class="">$</span>
                                            </label>
                                            <label id="lblTotalFooter" class="form-control label-default">0</label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-1" style="text-align: center;">&nbsp;</div>
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