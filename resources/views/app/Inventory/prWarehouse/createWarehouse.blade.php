@section('script')
    <?php
        $furl = "js/Inventory/Warehouse/createWarehouse.js";
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
                            <h3 class="box-title">Crear Registro</h3>
                        </div><!-- /.box-header -->
                        
                        <div class="box-body">
                            <form id="frmDatos" class="form-horizontal" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label class="control-label col-sm-2">Producto</label>
                                    <div class="col-sm-10">
                                        <select id="selProducto" name="selProducto" class="form-control">
                                            <option value="">...</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2">Cantidad</label>
                                    <div class="col-sm-10">
                                        <input type="text" id="txtCantidad" name="txtCantidad" class="form-control validate format-number"/>
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