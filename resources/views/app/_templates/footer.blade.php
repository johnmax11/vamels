<!-- START FOOTER -->
    <footer class="page-footer {{Config::get('syslab.style_background')}}">
      <div class="container">
          &copy <?php echo @date('Y'); ?> 
          {!! Config::get('syslab.title_clean') !!} - <i>{!! Config::get('syslab.title_full') !!}</i>
          {!! Config::get('syslab.version') !!} - 
          Powered By&nbsp;
          <a href="" target="_blank" style="color: black;">Me</a>
          
      </div>
      <div class="center container"></div>
      <br/>
    </footer>
    <!-- Modal cmabio de clave -->
    <!-- Modal Structure -->
    <div id="modalChangePassword" class="modal modal-fixed-footer">
        <div class="modal-content">
            <h4>Cambio de contrase&ntilde;a</h4>
            <p>
                Ya que se encuentra con la clave asignada por defecto, debe
                realizar el cambio de contrase&ntilde;a para continuar sin ningun
                inconveniente en la plataforma
            </p>
            <div id="divStatusErrorChangeClave"></div>
            <form id="frmChangePassword">
                <div class="row">
                    <div class="input-field col s12">
                        <input id="txtClaveAntigua" name="txtClaveAntigua" type="password" class="validate">
                        <label for="txtClaveAntigua">Clave actual</label>
                    </div>
                    <div class="input-field col s12">
                        <input id="txtClaveNueva" name="txtClaveNueva" type="password" class="validate">
                        <label for="txtClaveNueva">Clave nueva</label>
                    </div>
                    <div class="input-field col s12">
                        <input id="txtConfirmeClaveNueva" name="txtConfirmeClaveNueva" type="password" class="validate">
                        <label for="last_name">Confirme clave</label>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <a id="aSalvarCambioClave" href="javascript:void(0)" class="modal-action waves-effect waves-green btn-flat">Guardar cambios</a>
        </div>
    </div>
    <!-- END modal cambio clave !-->
    <!-- END FOOTER -->
    <!-- ================================================
    Scripts
    ================================================ -->
    <!-- jQuery Library -->
    <script type="text/javascript" src="{{Config::get('syslab.path_url_web')}}/js/_helpers/materialize-admin-4.0/materialize-admin/vendors/jquery-3.2.1.min.js"></script>
    <!--materialize js-->
    <script type="text/javascript" src="{{Config::get('syslab.path_url_web')}}/js/_helpers/materialize-admin-4.0/materialize-admin/js/materialize.min.js"></script>
    <!--scrollbar-->
    <script type="text/javascript" src="{{Config::get('syslab.path_url_web')}}/js/_helpers/materialize-admin-4.0/materialize-admin/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <!--plugins.js - Some Specific JS codes for Plugin Settings-->
    <script type="text/javascript" src="{{Config::get('syslab.path_url_web')}}/js/_helpers/materialize-admin-4.0/materialize-admin/js/plugins.js"></script>
    <script>
        var public_path = "{{ asset('/') }}";
        var _token = "{{ csrf_token() }}";
    </script>
    <!--custom-script.js - Add your utilities JS-->
    <script type="text/javascript" src="{{Config::get('syslab.path_url_web')}}/js/_helpers/utilities/js/utilities.js"></script>
    <!--custom-script.js - Add your own theme custom JS-->
    <script type="text/javascript" src="{{Config::get('syslab.path_url_web')}}/getfilejs/footer.js"></script>
    <script type="text/javascript" src="{{Config::get('syslab.path_url_web')}}/getfilejs/menu_left.js"></script>
    
    <!-- include javascript extra script -->
    <?php
        if(isset($arrExtraScript)){
            for($i=0;$i<count($arrExtraScript);$i++){
                echo $arrExtraScript[$i];
            }
        }
    ?>
    <!-- include javascript local view -->
    <script type="text/javascript" src="{{Config::get('syslab.path_url_web')}}/getfilejs/app/views/js/{{$furl}}"></script>