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
    <div class="section card-panel">
        <h5 class="breadcrumbs-title">Crear evento deportivo</h5>
        <div class="divider"></div>
        <div class="col s12" id="divStatusResponse"></div>
        <div class="row">
            <form id="frmDatos" class="col s12" enctype="multipart/form-data">
                <div class="row">
                    <div class="col s12">
                        <ul class="tabs tab-demo">
                            <li class="tab col s3"><a active href="#basics">Basicos</a></li>
                            <li class="tab col s3"><a href="#author">Autoria</a></li>
                        </ul>
                    </div>
                    <br/><br/><br/>
                    <!-- BASICS -->
                    <div id="basics" class="col s12">
                        <div class="input-field col s12 m4">
                            <select id="selDeporte" name="selDeporte" class="validate">
                                <option value="">Seleccione...</option>
                            </select>
                            <label for="selDeporte">Deporte</label>
                        </div>
                        <div class="input-field col s12 m4">
                            <input id="txtEstado" type="text" value="ACTIVO" class="validate disabled" disabled="disabled"/>
                            <label for="txtEstado">Estado</label>
                        </div>
                        <div class="input-field col s12 m4">
                            <select id="selTipoEvento" name="selTipoEvento" class="validate">
                                <option value="">Seleccione...</option>
                                <option value="C">Competici&oacute;n</option>
                                <option value="R">Recreaci&oacute;n</option>
                            </select>
                            <label for="selTipoEvento">Tipo Evento</label>
                        </div>
                        
                        <!-- tiempo d inicio fin -->
                        <div class="input-field col s12 m4">
                            <i class="material-icons prefix">date_range</i>
                            <input id="txtDateIni" name="txtDateIni" type="text" class="datepicker validate" min="<?php echo @date("Y-m-d"); ?>"/>
                            <label for="txtDateIni">Inicia</label>
                        </div>
                        <div class="input-field col s12 m2">
                            <i class="material-icons prefix">timer</i>
                            <input id="txtTimeIni" name="txtTimeIni" type="text" class="timepicker validate"/>
                            <label for="txtTimeIni">a las</label>
                        </div>
                        <div class="input-field col s12 m4">
                            <i class="material-icons prefix">date_range</i>
                            <input id="txtDateFin" name="txtDateFin" type="text" class="datepicker" min="<?php echo @date("Y-m-d"); ?>"/>
                            <label for="txtDateFin">Termina</label>
                        </div>
                        <div class="input-field col s12 m2">
                            <i class="material-icons prefix">timer</i>
                            <input id="txtTimeFin" name="txtTimeFin" type="text" class="timepicker"/>
                            <label for="txtTimeFin">a las</label>
                        </div>
                        <!-- END tiempo d inicio fin -->
                        
                        <!-- titulo -->
                        <div class="input-field col s12 m6">
                            <input id="txtTituloBig" name="txtTituloBig" type="text" maxlength="255" class="validate"/>
                            <label for="txtTimeFin">Tit. Principal</label>
                        </div>
                        <div class="input-field col s12 m6">
                            <input id="txtTituloDetalle" name="txtTituloDetalle" type="text" maxlength="255" class="validate"/>
                            <label for="txtTimeFin">Tit. Detalle</label>
                        </div>
                        <!-- END titulo -->
                        
                        <!-- imagen -->
                        <div class="input-field col s12 m6">
                            <div class="file-field input-field">
                                <div class="btn teal">
                                    <span>Img. Principal</span>
                                    <input type="file" id="filImagenThumb" name="filImagenThumb"/>
                                </div>
                                <div class="file-path-wrapper">
                                  <input id="txtImagenThumb" class="file-path validate" type="text"  class="validate"/>
                                </div>
                            </div>
                        </div>
                        <div class="input-field col s12 m6">
                            <div class="file-field input-field">
                                <div class="btn teal">
                                    <span>Img. Real</span>
                                    <input type="file" id="filImagenReal" name="filImagenReal" />
                                </div>
                                <div class="file-path-wrapper">
                                  <input id="txtImagenReal" class="file-path validate" type="text">
                                </div>
                            </div>
                        </div>
                        <!-- END imagen -->
                        
                        <!-- coordenadas -->
                        <div class="input-field col s12 m6">
                            <i id="gpsMaps" class="material-icons prefix">gps_fixed</i>
                            <input id="txtEscLatitud" name="txtEscLatitud" type="text" class="validate"/>
                            <label for="txtEscLatitud">Esc. Latitud</label>
                        </div>
                        <div class="input-field col s12 m6">
                            <i class="material-icons prefix">gps_fixed</i>
                            <input id="txtEscLongitud" name="txtEscLongitud" type="text" maxlength="255" class="validate"/>
                            <label for="txtEscLongitud">Esc. Longitud</label>
                        </div>
                        <!-- END coordenadas -->
                        
                        <!-- escenario -->
                        <div class="input-field col s12 m4">
                            <input id="txtEscenarioNombre" name="txtEscenarioNombre" type="text" maxlength="255" class="validate"/>
                            <label for="txtEscenarioNombre">Esc. Nombre</label>
                        </div>
                        <div class="input-field col s12 m4">
                            <input id="txtEscenarioDireccion" name="txtEscenarioDireccion" type="text" maxlength="255" class="validate"/>
                            <label for="txtEscenarioDireccion">Esc. Direcci&oacute;n</label>
                        </div>
                        <div class="input-field col s12 m4">
                            <input id="txtEscenarioBarrio" name="txtEscenarioBarrio" type="text" maxlength="255" class="validate"/>
                            <label for="txtEscenarioBarrio">Esc. Barrio</label>
                        </div>
                        <!-- END escenario -->
                        
                        <!-- contacto -->
                        <div class="input-field col s12 m4">
                            <i class="material-icons prefix">link</i>
                            <input id="txtLink" name="txtLink" type="url" maxlength="255" class="validate"/>
                            <label for="txtLink">Link</label>
                        </div>
                        <div class="input-field col s12 m4">
                            <i class="material-icons prefix">email</i>
                            <input id="txtEmail" name="txtEmail" type="email" maxlength="255" class=""/>
                            <label for="txtEmail">Email</label>
                        </div>
                        <div class="input-field col s12 m4">
                            <i class="material-icons prefix">phone</i>
                            <input id="txtPhone" name="txtPhone" type="number" maxlength="255" class=""/>
                            <label for="txtPhone">Phone</label>
                        </div>
                        <!-- END contacto -->
                        
                        <!-- location -->
                        <div class="input-field col s12 m6">
                            <i class="material-icons prefix">location_on</i>
                            <input id="txtCiudad" name="txtCiudad" type="text" maxlength="255" class="validate"/>
                            <label for="txtCiudad">Ciudad</label>
                        </div>
                        <div class="input-field col s12 m6">
                            <i class="material-icons prefix">attach_money</i>
                            <input id="txtPrecio" name="txtPrecio" type="number" maxlength="255" class="validate"/>
                            <label for="txtPrecio">Precio</label>
                        </div>
                        <!-- END location -->
                        
                        <div class="input-field col s12 m6">
                            <input id="txtTwitter" name="txtTwitter" type="text" maxlength="255" value="@SecDeporteCali @indervalle" class="validate"/>
                            <label for="txtTwitter">Twitter</label>
                        </div>
                        
                    </div> <!-- END TAB BASICS -->
                    
                    <!-- AUTHOR -->
                    <div id="author" class="col s12">
                        
                        <!-- tite article -->
                        <div class="input-field col s12 m6">
                            <input id="txtTitArticulo" name="txtTitArticulo" type="text" maxlength="255" class="validate"/>
                            <label for="txtTitArticulo">Tit. Articulo</label>
                        </div>
                        <div class="input-field col s12 m6">
                            <i class="material-icons prefix">link</i>
                            <input id="txtWebsitePagina" name="txtWebsitePagina" type="url" maxlength="255" class="validate"/>
                            <label for="txtWebsitePagina">Link. Pagina</label>
                        </div>
                        <!-- END tite article
                        
                        <!-- date created -->
                        <div class="input-field col s12 m4">
                            <i class="material-icons prefix">date_range</i>
                            <input id="txtFechaCreacion" name="txtFechaCreacion" type="text" maxlength="255" class="datepicker validate"/>
                            <label for="txtFechaCreacion">F. creaci&oacute;n</label>
                        </div>
                        <div class="input-field col s12 m4">
                            <i class="material-icons prefix">date_range</i>
                            <input id="txtFechaAccedido" name="txtFechaAccedido" type="text" maxlength="255" class="datepicker validate" value="<?php echo @date("Y-m-d"); ?>"/>
                            <label for="txtFechaAccedido">F. Accedido</label>
                        </div>
                        <div class="input-field col s12 m4">
                            <i class="material-icons prefix">link</i>
                            <input id="txtWebsiteArticulo" name="txtWebsiteArticulo" type="url" maxlength="255" class="validate"/>
                            <label for="txtWebsiteArticulo">Link. Articulo</label>
                        </div>
                        <!-- END date created -->
                        
                    </div> <!-- END TAB AUTHOR -->
                    
                    <div class="center">
                        <a id="aSetEventSaveData" class="waves-effect waves-light teal btn">Guardar</a>
                    </div>
                    
                </div> <!-- END  -->
            </form>
        </div>
    </div>

    <!-- Modal Structure -->
    <div id="divModalMapa" class="modal modal-fixed-footer" style="width: 600px; height:500px;">
        <div class="modal-content">
            <h4>Geo referenciaci&oacute;n</h4>
            <table>
                <tr>
                    <td>
                        <label>Direcci&oacute;n: </label>
                        <input style="" type="text" id="direccion" name="direccion" value=""/>
                    </td>
                    <td>
                        <button id="pasar">Buscar</button> 
                    </td>
                </tr>
            </table>
            
            <style>
                #map {
                    width: 550px;
                    height: 400px;
                }
            </style>
            <div id="map"></div>

            <script>
                //$('#pasar').click(function(){
                    
                //});
                document.getElementById("pasar").addEventListener("click", function(){
                    codeAddress();
                    return false;
                }); 

                var map;
                var geocoder;
                var marker;
                function initMap() {
                    geocoder = new google.maps.Geocoder();
                    map = new google.maps.Map(document.getElementById('map'), {
                        center: {lat: 3.4516467, lng: -76.5319854},
                        zoom: 16
                    });
                    var latLng = new google.maps.LatLng(3.4516467,-76.5319854);
                    //creamos el marcador en el mapa
                    marker = new google.maps.Marker({
                        map: map,//el mapa creado en el paso anterior
                        position: latLng,//objeto con latitud y longitud
                        draggable: true //que el marcador se pueda arrastrar
                    });
                    // now attach the event
                    google.maps.event.addListener(marker, 'dragend', function () {
                        // you know you'd be better off with 
                        // marker.getPosition().lat(), right?
                        $("#txtEscLatitud").val(marker.position.lat());
                        $("#txtEscLatitud").change();
                        $("#txtEscLongitud").val(marker.position.lng());
                        $("#txtEscLongitud").change();
                    });
                }
                //funcion que traduce la direccion en coordenadas
                function codeAddress() {
                    //obtengo la direccion del formulario
                    var address = document.getElementById("direccion").value;
                    //hago la llamada al geodecoder
                    geocoder.geocode( { 'address': address}, function(results, status) {

                        //si el estado de la llamado es OK
                        if (status == google.maps.GeocoderStatus.OK) {
                            //centro el mapa en las coordenadas obtenidas
                            map.setCenter(results[0].geometry.location);
                            //coloco el marcador en dichas coordenadas
                            marker.setPosition(results[0].geometry.location);
                            //actualizo el formulario     
                            var ntlat = results[0].geometry.location;
                            $("#txtEscLatitud").val(ntlat.lat());
                            $("#txtEscLatitud").change();
                            $("#txtEscLongitud").val(ntlat.lng());
                            $("#txtEscLongitud").change();
                        } else {
                            //si no es OK devuelvo error
                            alert("No podemos encontrar la direcci&oacute;n, error: " + status);
                        }
                    });
                }
            </script>

            <script src="" async defer></script>
        </div>
        <div class="modal-footer">
            <a href="javascript:void(0)" class="modal-action modal-close waves-effect waves-green btn-flat ">Cerrar</a>
        </div>
    </div>

    
    <!-- Modal Structure -->
    <div id="divModalFacebook" class="modal modal-fixed-footer" style="width: 600px; height:500px;">
        <div class="modal-content">
            <h4>Post Facebook</h4>
            <div id="divPostFacebook"></div>
            <br/>
            <br/>
            <div>
                <label>Url post: </label>
                <input type="hidden" id="hdnidevento" name="hdnidevento"/>
                <input style="" type="text" id="txtUrlFacebook" name="txtUrlFacebook"/>
                <a id="aSendUpdateUrlFacebook" href="javascript:void(0)">Actualizar url facebook</a>
            </div>
        </div>
        <div class="modal-footer">
            <a href="javascript:void(0)" class="modal-action modal-close waves-effect waves-green btn-flat ">Cerrar</a>
        </div>
    </div>
@stop