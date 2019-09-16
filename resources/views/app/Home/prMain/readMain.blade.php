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
    <div>hola mundo</div>
@stop