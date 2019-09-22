@section('script')
    <?php
        $furl = "js/Clients/Management/readManagement.js";
    ?>
    @include ('app._templates.includes')
    
    {!! HTML::script('js/_helpers/DataTables-1.10.6/media/js/jquery.dataTables.js') !!}
    {!! HTML::style('js/_helpers/DataTables-1.10.6/media/css/jquery.dataTables.min.css') !!}
    {!! HTML::script('js/_helpers/DataTables-1.10.6/extensions/Responsive/js/dataTables.responsive.js') !!}
    {!! HTML::style('js/_helpers/DataTables-1.10.6/extensions/Responsive/css/dataTables.responsive.css') !!}
    {!! HTML::script('js/_helpers/DataTables-1.10.6/extensions/TableTools/js/dataTables.tableTools.js') !!}
    
    {!! HTML::script($furl) !!}
@show
@extends('app._templates.master')
@section ('content')
<div  id="content-wrapper" class="content-wrapper">
    <article class="">
        <!-- Content Header (Page header) -->
        <section id="content-header" class="content-header">
          <h1>
            {{$datosRecurso['act_n']}}
            <small>{{$datosRecurso['desc_act']}}</small>
          </h1>
          <ol class="breadcrumb">
            <li><i class="fa {{$datosRecurso['ico']}}"></i> {{$datosRecurso['mod_n']}}</li>
            <li id="liActionActive" class="active">{{$datosRecurso['act_n']}}</li>
          </ol>
        </section>
        
        <section class="content">
            <div class="row">
                <section class="col-md-12">
                    <div class="box box-custom">
                        <div class="box-header with-border">
                            <h3 class="box-title">Clientes</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <div id='divGrillaPrincipal' class='dataTables_wrapper'></div>
                        </div>
                    </div>
                </section>
            </div>
        </section>
    </article>
</div>
@stop