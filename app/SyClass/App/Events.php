<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace App\SyClass\App;
use \App\SyClass\DB\FacCRUD;
use \App\SyModels;
/**
 * Description of Events
 *
 * @author johnm
 */
class Events {
    
    private $_interfaceResponse;
    
    /**
     * 
     * @param type $objInterfaceResponse
     */
    public function __construct($objInterfaceResponse) {
        // verificamos si hay interface de respuesta
        if($objInterfaceResponse != null){
            $this->_interfaceResponse = $objInterfaceResponse;
        }
    }
    
    /**
     * devuelve los datos de la grilla
     * 
     * @return type
     * @throws \Exception
     */
    public function getDataGridPrincipal(){
        try{
            $arrData = (new FacCRUD(new SyModels\EventsSports()))
                ->read(
                    null,
                    null,
                    array(array("id","DESC"))
                );
            
            $arrResponse = array();
            $nRows = count($arrData);
            for($i=0;$i < $nRows;$i++){
                $arrResponse[$i] = new \stdClass();
                
                $arrResponse[$i]->edit = true;
                $arrResponse[$i]->id = $arrData[$i]->id;
                $arrResponse[$i]->type_event = $arrData[$i]->type_event;
                $arrResponse[$i]->tittle = $arrData[$i]->title_big;
                $arrResponse[$i]->status = $arrData[$i]->status;
                $arrResponse[$i]->created_at = $arrData[$i]->created_at;
                
            }
            
            return $this->_interfaceResponse->callBackResponse(array("rows"=>$arrResponse));
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * crea el evento con sus datos
     * 
     * @param type $request
     * @throws \Exception
     */
    public function createEventPrincipal($request){
        try{
            \DB::beginTransaction();
            
            // consultamos el deporte
            $objDeporte = (new FacCRUD(new SyModels\ParamsSports($request->selDeporte)));
            
            // creamos el registro principal
            $lastIdEvent = (new FacCRUD(new SyModels\EventsSports()))
                ->create((object)array(
                    "params_sports_id"=>$request->selDeporte,
                    "status"=>"I",
                    "order_item"=>2,
                    "type_event"=>$request->selTipoEvento,
                    "date_start"=>$request->txtDateIni,
                    "date_end"=>$request->txtDateFin,
                    "time_start"=>$request->txtTimeIni,
                    "time_end"=>$request->txtTimeFin,
                    "title_big"=>$request->txtTituloBig,
                    "title_short"=>$request->txtTituloDetalle,
                    "stage_latitud"=>$request->txtEscLatitud,
                    "stage_longitud"=>$request->txtEscLongitud,
                    "stage_name"=>$request->txtEscenarioNombre,
                    "stage_address"=>$request->txtEscenarioDireccion,
                    "stage_address_2"=>$request->txtEscenarioBarrio,
                    "link_more_information"=>$request->txtLink,
                    "email_contact"=>$request->txtEmail,
                    "phone_contact"=>$request->txtPhone,
                    "city"=>$request->txtCiudad,
                    "price"=>$request->txtPrecio
                ));
            
                // movemos las imagenes subidas
                $arrImg = ($this->moveImagenesEvento($lastIdEvent));
                if(!is_array($arrImg)){
                    return $this->_interfaceResponse->callBackResponse(
                        array(
                            'msgResponseFirst'=>"Error subiendo las imagenes",
                        ),
                        true,
                        'error'
                    );
                }
                
                /// actualizamos las imagenes
                (new FacCRUD(new SyModels\EventsSports()))
                    ->update(
                        (object)array(
                            "image_thumbnail"=>"T".$lastIdEvent.substr($arrImg["thum"],strrpos($arrImg["thum"],".")),
                            "image_real"=>"R".$lastIdEvent.substr($arrImg["real"],strrpos($arrImg["real"],"."))
                        ),
                        (object)array(
                            "id"=>$lastIdEvent
                        )
                    );
                
                /// creamos el registro de autoria
                if(!$this->setCreateDataAutoria($lastIdEvent,$request)){
                    return $this->_interfaceResponse->callBackResponse(
                        array(
                            'msgResponseFirst'=>"Error creando los datos de autoria",
                        ),
                        true,
                        'error'
                    );
                }
                
                //////////////////////////////////////////
                /////////////// creamos el twitter post
                $arrTwitter = (new \App\SyClass\Helpers\NetworkSocial())
                        ->publishTwitter(
                            storage_path()."/app/files/events/".$lastIdEvent."/"."R".$lastIdEvent.substr($arrImg["real"],strrpos($arrImg["real"],".")),
                            (object)array(
                                "title_big"=>$request->txtTituloBig,
                                "link_more_information"=>$request->txtLink,
                                "date_formated"=>
                                    substr($request->txtDateIni,8).
                                    "/".
                                    \App\SyClass\Helpers\Utilities::getNameMonthSpanish(substr($request->txtDateIni,5,2))
                            ),
                            $request->txtTwitter,
                            $objDeporte->get()->name
                );
                
                // publicamos en twitter
                if(!is_array($arrTwitter)){
                    return $this->_interfaceResponse->callBackResponse(
                        array(
                            'msgResponseFirst'=>"Error creando los registros de twitter",
                        ),
                        true,
                        'error'
                    );
                }
                
                // creeamos en la table de redes sociales
                $lastIdRedes = (new FacCRUD(new SyModels\EventsSocialLinks()))
                    ->create((object)array(
                        "events_sports_id"=>$lastIdEvent,
                        "link_url_twitter"=>$arrTwitter["url"]
                    ));
                
                // creamos el registro en wordpress
                if(!$this->savePostWordpress($lastIdEvent)){
                    return $this->_interfaceResponse->callBackResponse(
                        array(
                            'msgResponseFirst'=>"Error creando los registros de wordpress",
                        ),
                        true,
                        'error'
                    );
                }
                
                ////////////////////////////////////////77
                ///////// enviamos el push a los favoritos
                $this->setPushFirebaseFavorites($lastIdEvent);
            
            \DB::commit();
                
            return $this->_interfaceResponse->callBackResponse(array(
                "id_evento"=>$lastIdEvent
            ));
        } catch (\Exception $ex) {
            \DB::rollBack();
            throw $ex;
        }
    }

    /**
     * enviamos el push por firebase
     * 
     * @param type $lastIdEvent
     * @throws \Exception
     */
    public function setPushFirebaseFavorites($lastIdEvent){
        try{
            // consultamos los datos del evento
            $objEvent = (new FacCRUD(new SyModels\EventsSports($lastIdEvent)));
            // consultamos el nombre del deporte
            $objParSports = (new FacCRUD(new SyModels\ParamsSports($objEvent->get()->params_sports_id)));
            $nSportsName = mb_strtoupper($objParSports->get()->name);
            
            // set settings
            $server_key = "AIzaSyARZQkUp5zutOsEcznZts7zGWHr3yPgDps"; // get this from Firebase project settings->Cloud Messaging
            $title = "Mi Parche Deportivo - Evento nuevo";
            $url = 'https://fcm.googleapis.com/fcm/send';
            $headers = array(
                'Content-Type:application/json',
                'Authorization:key='.$server_key
            );
            
            // consultamos el mensaje de envio random
            $arrDMsg = (new FacCRUD(new SyModels\ParamsMsgFavorites()))
                            ->read(
                                null,
                                null,
                                "RANDOM",
                                array(1,1)
                            );
            // set mensaje
            $n_msg = str_replace("SPORT",$nSportsName,$arrDMsg[0]->message);
                
            // set data send
            $ndata = array('title'=>$title,'body'=>$n_msg,'vibrate'=> 1,'sound'=>1,'icon'=>'ic_launcher');
            $fields = array();
            $fields['notification'] = $ndata;
            
            // consultamos los phone q tienen como favorito este deporte
            $arrDPhoneSports = (new FacCRUD(new SyModels\CellphoneSportsFavorites()))
                ->read(
                        array(
                            array("params_sports_id",$objEvent->get()->params_sports_id)
                        )
                );
            
            // recorremos los phones y enviamos msg
            $arr_token = array();
            $nrows = count($arrDPhoneSports);
            for((int)$i=0; $i < $nrows ;$i++){
                // consultamos el phone token
                $arrDphone = (new FacCRUD(new SyModels\Cellphone($arrDPhoneSports[$i]->cellphone_id)));
                
                // configuramos envio y send
                $arr_token[$i] = $arrDphone->get()->token_refreshed; // Token generated from Android device after setting up firebase
            }
            // sett phone token
            $fields['registration_ids'] = $arr_token;
            // send curl msg
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);
            if ($result === FALSE) {
                die('FCM Send Error: ' . curl_error($ch));
            }
            curl_close($ch);
            
            return true;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * envia mensajes mediante cron
     * 
     * @throws \Exception
     */
    public function verifyPushFBase(){
        try{
            // fecha hoy
            $fecha_hoy = @date("Y-m-d");
            
            // hora hoy
            $hora_hoy = @date("H");
            
            // sacamos la fecha despues de 24 horas
            $fecha = date_create($fecha_hoy);
            date_add($fecha, date_interval_create_from_date_string('1 days'));
            $fecha_man = date_format($fecha, 'Y-m-d');
            
            // verificamos agendas antes de 24 horas
            $arrDEventos = (new FacCRUD(new SyModels\EventsSports()))
                ->read(array(
                    array("date_start","=",$fecha_man),
                    array("time_start",">=",$hora_hoy.":00:00"),
                    array("time_start","<=",$hora_hoy.":59:00")
                ));
            // recorremos los eventos
            $nrows = count($arrDEventos);
            for((int)$i=0;$i < $nrows;$i++){
                // sacamos los deportes del evento
                $arrDSport = (new FacCRUD(new SyModels\ParamsSports($arrDEventos[$i]->params_sports_id)));
                
                // buscamos los phones q esten en agenda para este evento
                $arrDPhonesAgenda = (new FacCRUD(new SyModels\EventsPhoneAssist()))
                    ->read(array(array("events_sports_id",$arrDEventos[$i]->id)));
                
                // recorremos los phones
                $nrows_pag = count($arrDPhonesAgenda);
                $arr_d_token_p = array();
                for((int)$j=0; $j < $nrows_pag ;$j++){
                    // sacamos el token del phone
                    $objPhone = (new FacCRUD(new SyModels\Cellphone($arrDPhonesAgenda[$j]->cellphone_id)));
                    $arr_d_token_p[$j] = $objPhone->get()->token_refreshed;
                } // fin for interno
                
                ////////////// preparamos el mensaje ///////////////7
                
                // set settings
                $server_key = "AIzaSyARZQkUp5zutOsEcznZts7zGWHr3yPgDps"; // get this from Firebase project settings->Cloud Messaging
                $title = "Mi Parche Deportivo - ¡Asiste y diviertete!";
                $url = 'https://fcm.googleapis.com/fcm/send';
                $headers = array('Content-Type:application/json','Authorization:key='.$server_key);
                
                // mensaje
                $n_msg = "Recuerda mañana el evento de (( ".$arrDSport->get()->name." )). Ingresa, revisa tu agenda deportiva y ¡Preparate!";
                // set data send
                $ndata = array('title'=>$title,'body'=>$n_msg,'vibrate'=> 1,'sound'=>1,'icon'=>'ic_launcher');
                $fields = array();
                $fields['notification'] = $ndata;
                
                // sett phone token
                $fields['registration_ids'] = $arr_d_token_p;
                // send curl msg
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
                $result = curl_exec($ch);
                if ($result === FALSE) {
                    die('FCM Send Error: ' . curl_error($ch));
                }
                curl_close($ch);
            } // fin for principal de eventos
           
            // verificamos agenda antes de 1 hora
            return $this->buscaEventosProximaHoraSendPush();
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * envia push a los eventos de la proxima hora
     * 
     * @return type
     * @throws \Exception
     */
    public function buscaEventosProximaHoraSendPush(){
        try{
            // fecha hoy
            $fecha_hoy = @date("Y-m-d");
            
            // hora hoy
            $hora_hoy = (@date("H")+1);
            if($hora_hoy == 24){
                $hora_hoy = "00";
            }
            
            // verificamos agendas antes de 24 horas
            $arrDEventos = (new FacCRUD(new SyModels\EventsSports()))
                ->read(array(
                    array("date_start","=",$fecha_hoy),
                    array("time_start",">=",$hora_hoy.":00:00"),
                    array("time_start","<=",$hora_hoy.":59:00")
                ));
            // recorremos los eventos
            $nrows = count($arrDEventos);
            for((int)$i=0;$i < $nrows;$i++){
                // sacamos los deportes del evento
                $arrDSport = (new FacCRUD(new SyModels\ParamsSports($arrDEventos[$i]->params_sports_id)));
                
                // buscamos los phones q esten en agenda para este evento
                $arrDPhonesAgenda = (new FacCRUD(new SyModels\EventsPhoneAssist()))
                    ->read(array(array("events_sports_id",$arrDEventos[$i]->id)));
                
                // recorremos los phones
                $nrows_pag = count($arrDPhonesAgenda);
                $arr_d_token_p = array();
                for((int)$j=0; $j < $nrows_pag ;$j++){
                    // sacamos el token del phone
                    $objPhone = (new FacCRUD(new SyModels\Cellphone($arrDPhonesAgenda[$j]->cellphone_id)));
                    $arr_d_token_p[$j] = $objPhone->get()->token_refreshed;
                } // fin for interno
                
                ////////////// preparamos el mensaje ///////////////7
                
                // set settings
                $server_key = "AIzaSyARZQkUp5zutOsEcznZts7zGWHr3yPgDps"; // get this from Firebase project settings->Cloud Messaging
                $title = "Mi Parche Deportivo - ¡Ya va a empezar!";
                $url = 'https://fcm.googleapis.com/fcm/send';
                $headers = array('Content-Type:application/json','Authorization:key='.$server_key);
                
                // mensaje
                $n_msg = "¡Ya casiii! Inicia tu evento de (( ".$arrDSport->get()->name." )). Ingresa, revisa los detalles y gozatelo";
                // set data send
                $ndata = array('title'=>$title,'body'=>$n_msg,'vibrate'=> 1,'sound'=>1,'icon'=>'ic_launcher');
                $fields = array();
                $fields['notification'] = $ndata;
                
                // sett phone token
                $fields['registration_ids'] = $arr_d_token_p;
                // send curl msg
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
                $result = curl_exec($ch);
                if ($result === FALSE) {
                    die('FCM Send Error: ' . curl_error($ch));
                }
                curl_close($ch);
            } // fin for principal de eventos
            
            return $this->_interfaceResponse->callBackResponse();
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * crea el registro de la autoria del evento
     * 
     * @param type $lastIdEvent
     * @param type $request
     * @throws \Exception
     */
    public function setCreateDataAutoria($lastIdEvent,$request){
        try{
            (new FacCRUD(new SyModels\EventsCredits()))
                ->create((object)array(
                    "events_sports_id"=>$lastIdEvent,
                    "title_article"=>$request->txtTitArticulo,
                    "website_article"=>$request->txtWebsitePagina,
                    "date_created_article"=>$request->txtFechaCreacion,
                    "date_access_article"=>$request->txtFechaAccedido,
                    "website_post_article"=>$request->txtWebsiteArticulo
                ));
            
            return true;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * movemos la imagenes
     *  
     * @param type $idEvent
     * @return boolean
     * @throws \Exception
     */
    public function moveImagenesEvento($idEvent){
        try{
            // movemos imagen del thumbil
            $objFacTmpMisc = new \App\SyClass\System\TmpMiscelaneos();
            $strImage_t = $objFacTmpMisc->moveImageByParams(
                \Auth::user()->id,
                "filImagenThumb",
                "events/".$idEvent,
                null,
                null,
                "T".$idEvent
            );
            
            if($strImage_t != null){
                // movemos imagen del real
                $objFacTmpMisc = new \App\SyClass\System\TmpMiscelaneos();
                $strImage_r = $objFacTmpMisc->moveImageByParams(
                    \Auth::user()->id,
                    "filImagenReal",
                    "events/".$idEvent,
                    null,
                    null,
                    "R".$idEvent
                );
                if($strImage_r != null){
                    return array(
                        "thum"=>$strImage_t,
                        "real"=>$strImage_r
                    );
                }else
                    return false;
            }else{
                return false;
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * mostrar datos del evento por id
     * 
     * @param type $request
     * @throws \Exception
     */
    public function getDataEventoById($request){
        try{
            $arrData = (new FacCRUD(new SyModels\EventsSports()))
                ->read(array(
                    array("id",$request->idevent)
                ));
            // consultamos el nombre del deporte
            $objSprots = (new FacCRUD(new SyModels\ParamsSports($arrData[0]->params_sports_id)));
            $arrData[0]->name_sports = (str_replace(" ","",ucwords($objSprots->get()->name)));
            
            return $this->_interfaceResponse->callBackResponse(array("rows"=>$arrData),false,"not-alert");
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * actualiza con el link d url d facebook post
     * 
     * @param type $request
     * @throws \Exception
     */
    public function updateEventFacebook($request){
        try{
            // actualizamos el lino d faceboook
            (new FacCRUD(new SyModels\EventsSocialLinks()))
                ->update(
                    (object)array(
                        "link_url_facebook"=>$request->url_facebook
                    ),
                    (object)array("events_sports_id"=>$request->idevento)
                );
            
            // actualizamos el evento a estado activo
            (new FacCRUD(new SyModels\EventsSports()))
                ->update(
                    (object)array(
                        "status"=>"A"
                    ),
                    (object)array("id"=>$request->idevento)
                );
            
            return $this->_interfaceResponse->callBackResponse();
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * publica el post en wordpress
     * 
     * @param type $idLastIdEvent
     * @throws \Exception
     */
    public function savePostWordpress($idLastIdEvent){
        try{
            // sacamos el ultimos id del post
            $obj_row = \DB::connection('mysql_wp')->select("SELECT id FROM wpp2_posts ORDER BY id DESC LIMIT 1");
			$lastidpost_e = 56;
            foreach($obj_row as $row) {
                $lastidpost_e = $row->id;
            };
            $lastidpost_e = ($lastidpost_e + 1);
            $arrData = (new FacCRUD(new SyModels\EventsSports()))
                ->read(array(
                    array("id",$idLastIdEvent)
                ));
            // nombre img real
            $imgReal = "R".$arrData[0]->id.substr($arrData[0]->image_real,strrpos($arrData[0]->image_real,"."));
            // consultamos el nombre del deporte
            $objSprots = (new FacCRUD(new SyModels\ParamsSports($arrData[0]->params_sports_id)));
            $arrData[0]->name_sports = (str_replace(" ","",ucwords($objSprots->get()->name)));
            
            // traemos el html
            $html_p = $this->getHtmlPostsWP($arrData);
            // copiamos la imagen
            copy(
                storage_path() . '/app/files/events/'.$arrData[0]->id.'/'. $imgReal,
                "/home/modaplan/public_html/webmpd/wp-content/uploads/2018/eventos/".$imgReal
            );
            
            // ingresamos el post
            \DB::connection('mysql_wp')->insert(
                "INSERT INTO wpp2_posts(".
                    "post_author,".
                    "post_date,".
                    "post_date_gmt,".
                    "post_content,".
                    "post_title,".
                    "post_excerpt,".
                    "post_status,".
                    "comment_status,".
                    "ping_status,".
                    "post_password,".
                    "post_name,".
                    "to_ping,".
                    "pinged,".
                    "post_modified,".
                    "post_modified_gmt,".
                    "post_content_filtered,".
                    "post_parent,".
                    "guid,".
                    "menu_order,".
                    "post_type,".
                    "post_mime_type,".
                    "comment_count".
                ")VALUES(".
                    "?,?,?,?,?,?,?,?,?,?,".
                    "?,?,?,?,?,?,?,?,?,?,".
                    "?,?".
                ")",
                [
                    1,
                    @date("Y-m-d H:i:s"),
                    @date("Y-m-d H:i:s"),
                    $html_p,
                    $arrData[0]->title_big,
                    "",
                    "publish",
                    "open",
                    "open",
                    "",
                    str_replace(" ","-",mb_strtolower($arrData[0]->title_big)),
                    "",
                    "",
                    @date("Y-m-d H:i:s"),
                    @date("Y-m-d H:i:s"),
                    "",
                    0,
                    "http://miparchedeportivo.com/webmpd/?p=".$lastidpost_e,
                    0,
                    "post",
                    "",
                    0
                ]
            );
            
            $typeimage = "image/".(substr($imgReal,(strlen($imgReal)-3))=="jpg"?"jpeg":"png");
            
            // ingresamos la imagen
            \DB::connection('mysql_wp')->insert(
                "INSERT INTO wpp2_posts(".
                    "post_author,".
                    "post_date,".
                    "post_date_gmt,".
                    "post_content,".
                    "post_title,".
                    "post_excerpt,".
                    "post_status,".
                    "comment_status,".
                    "ping_status,".
                    "post_password,".
                    "post_name,".
                    "to_ping,".
                    "pinged,".
                    "post_modified,".
                    "post_modified_gmt,".
                    "post_content_filtered,".
                    "post_parent,".
                    "guid,".
                    "menu_order,".
                    "post_type,".
                    "post_mime_type,".
                    "comment_count".
                ")VALUES(".
                    "?,?,?,?,?,?,?,?,?,?,".
                    "?,?,?,?,?,?,?,?,?,?,".
                    "?,?".
                ")",
                [
                    1,
                    @date("Y-m-d H:i:s"),
                    @date("Y-m-d H:i:s"),
                    "",
                    substr($imgReal,0,(strlen($imgReal)-4)),
                    "",
                    "inherit",
                    "open",
                    "closed",
                    "",
                    substr($imgReal,0,(strlen($imgReal)-4)),
                    "",
                    "",
                    @date("Y-m-d H:i:s"),
                    @date("Y-m-d H:i:s"),
                    "",
                    $lastidpost_e,
                    "http://dashboard.miparchedeportivo.com/public/imgevent/".$arrData[0]->id."/".$imgReal,
                    0,
                    "attachment",
                    $typeimage,
                    0
                ]
            );
            
            // insertmeta key
            \DB::connection('mysql_wp')->insert(
                "INSERT INTO wpp2_postmeta(".
                    "post_id,".
                    "meta_key,".
                    "meta_value".
                ")VALUES(".
                    "?,?,?".
                ")",
                [
                    $lastidpost_e,
                    "_thumbnail_id",
                    ($lastidpost_e+1)
                ]
            );
            
            // imagen
            // insertmeta key
            \DB::connection('mysql_wp')->insert(
                "INSERT INTO wpp2_postmeta(".
                    "post_id,".
                    "meta_key,".
                    "meta_value".
                ")VALUES(".
                    "?,?,?".
                ")",
                [
                    ($lastidpost_e+1),
                    "_wp_attached_file",
                    '2018/eventos/'.$imgReal
                ]
            );
            
            // insertmeta key
            \DB::connection('mysql_wp')->insert(
                "INSERT INTO wpp2_postmeta(".
                    "post_id,".
                    "meta_key,".
                    "meta_value".
                ")VALUES(".
                    "?,?,?".
                ")",
                [
                    ($lastidpost_e+1),
                    "_wp_attachment_metadata",
                    'a:5:{s:5:"width";i:1920;s:6:"height";i:670;s:4:"file";s:57:"2018/eventos/'.$imgReal.'";s:5:"sizes";a:4:{s:9:"thumbnail";a:4:{s:4:"file";s:57:"'.$imgReal.'";s:5:"width";i:150;s:6:"height";i:150;s:9:"mime-type";s:10:"'.$typeimage.'";}s:6:"medium";a:4:{s:4:"file";s:57:"'.$imgReal.'";s:5:"width";i:300;s:6:"height";i:105;s:9:"mime-type";s:10:"'.$typeimage.'";}s:12:"medium_large";a:4:{s:4:"file";s:57:"'.$imgReal.'";s:5:"width";i:768;s:6:"height";i:268;s:9:"mime-type";s:10:"'.$typeimage.'";}s:5:"large";a:4:{s:4:"file";s:58:"'.$imgReal.'";s:5:"width";i:1024;s:6:"height";i:357;s:9:"mime-type";s:10:"'.$typeimage.'";}}s:10:"image_meta";a:12:{s:8:"aperture";s:1:"0";s:6:"credit";s:0:"";s:6:"camera";s:0:"";s:7:"caption";s:0:"";s:17:"created_timestamp";s:1:"0";s:9:"copyright";s:0:"";s:12:"focal_length";s:1:"0";s:3:"iso";s:1:"0";s:13:"shutter_speed";s:1:"0";s:5:"title";s:0:"";s:11:"orientation";s:1:"0";s:8:"keywords";a:0:{}}}'
                ]
            );
            
            // inserta rel categoria
            \DB::connection('mysql_wp')->insert(
                "INSERT INTO wpp2_term_relationships(".
                    "object_id,".
                    "term_taxonomy_id,".
                    "term_order".
                ")VALUES(".
                    "?,?,?".
                ")",
                [
                    ($lastidpost_e),
                    1,
                    0
                ]
            );
            
            // ingresamos los tags del evento
            for($i=5;$i<=27;$i++){
                // inserta rel categoria
                \DB::connection('mysql_wp')->insert(
                    "INSERT INTO wpp2_term_relationships(".
                        "object_id,".
                        "term_taxonomy_id,".
                        "term_order".
                    ")VALUES(".
                        "?,?,?".
                    ")",
                    [
                        ($lastidpost_e),
                        $i,
                        0
                    ]
                );
            }
            
            return true;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * 
     * @param type $data
     * @return string
     * @throws \Exception
     */
    private function getHtmlPostsWP($data){
        try{
            $str = "";
                    
            $str .= "# ".$data[0]->title_big."<br/>";
            $str .= "<br/><br/>";
            $str .= "** Evento **<br/>";
            $str .= "".$data[0]->title_short;
            $str .= "<br/><br/>";
            $str .= '<div class="text_exposed_show">';
            $str .= "   ** Mas informaci&oacute;n **<br/>";
            $str .= '   <a href="'.$data[0]->link_more_information.'" target="_blank" rel="noopener nofollow" data-ft="{&quot;tn&quot;:&quot;-U&quot;}" data-lynx-mode="asynclazy" data-lynx-uri="">'.$data[0]->link_more_information.'</a>';
            $str .= "   <br/><br/>";
            $str .= "   ** Fecha **<br/>";
            $str .= "   ".$data[0]->date_start." a las: ".$data[0]->time_start;
            $str .= "   <br/><br/>";
            $str .= "   ** Lugar **<br/>";
            $str .= "   ".$data[0]->stage_name."<br/>";
            $str .= "   ".$data[0]->stage_address."<br/>";
            $str .= "   ".$data[0]->stage_address_2;
            $str .= "   <br/><br/>";
            $str .= "   ** Ciudad **<br/>";
            $str .= "   ".$data[0]->city;
            $str .= "   <br/><br/>";
            $str .= "   ** Valor **<br/>";
            $str .= "   $ ".$data[0]->price;
            $str .= "   <br/><br/>";
            $str .= '   ==========&gt; Descarga nuestra APP Movil #MiParcheDeportivo y te avisaremos de #EventosDeportivos similares <a href="https://l.facebook.com/l.php?u=https%3A%2F%2Fplay.google.com%2Fstore%2Fapps%2Fdetails%3Fid%3Dcom.eventosdeportes.mpd.miparchedeportivo&amp;h=AT00Lncay9ALaxJAdnilUq0lhisa7j9YG6XAlGsaZu78O8JkVFdsS41MzQwu-pQxK7-6Uneesmvq3sM7yzpL6ZHpFMPR3Y1vqKRbOQnvRUScMgyBBYYb2GBFBlZS1bdGcS-GeQn7MUXVgIReduzYGSU" target="_blank" rel="noopener nofollow" data-ft="{&quot;tn&quot;:&quot;-U&quot;}" data-lynx-mode="asynclazy">https://play.google.com/store/apps/details…</a> &lt;==========';
            $str .= "   <br/><br/>";
            $str .= '   <a class="_58cn" href="https://web.facebook.com/hashtag/miparchedeportivo?source=feed_text" data-ft="{&quot;tn&quot;:&quot;*N&quot;,&quot;type&quot;:104}"><span class="_5afx"><span class="_58cl _5afz" aria-label="numeral">#</span><span class="_58cm">MiParcheDeportivo</span></span></a> <a class="_58cn" href="https://web.facebook.com/hashtag/valledelcauca?source=feed_text" data-ft="{&quot;tn&quot;:&quot;*N&quot;,&quot;type&quot;:104}"><span class="_5afx"><span class="_58cl _5afz" aria-label="numeral">#</span><span class="_58cm">ValleDelCauca</span></span></a> <a class="_58cn" href="https://web.facebook.com/hashtag/cali?source=feed_text" data-ft="{&quot;tn&quot;:&quot;*N&quot;,&quot;type&quot;:104}"><span class="_5afx"><span class="_58cl _5afz" aria-label="numeral">#</span><span class="_58cm">Cali</span></span></a> <a class="_58cn" href="https://web.facebook.com/hashtag/ligasdeportivas?source=feed_text" data-ft="{&quot;tn&quot;:&quot;*N&quot;,&quot;type&quot;:104}"><span class="_5afx"><span class="_58cl _5afz" aria-label="numeral">#</span><span class="_58cm">LigasDeportivas</span></span></a> <a class="_58cn" href="https://web.facebook.com/hashtag/deportes?source=feed_text" data-ft="{&quot;tn&quot;:&quot;*N&quot;,&quot;type&quot;:104}"><span class="_5afx"><span class="_58cl _5afz" aria-label="numeral">#</span><span class="_58cm">Deportes</span></span></a> <a class="_58cn" href="https://web.facebook.com/hashtag/yoamoeldeporte?source=feed_text" data-ft="{&quot;tn&quot;:&quot;*N&quot;,&quot;type&quot;:104}"><span class="_5afx"><span class="_58cl _5afz" aria-label="numeral">#</span><span class="_58cm">YoAmoElDeporte</span></span></a> <a class="_58cn" href="https://web.facebook.com/hashtag/yohagodeporte?source=feed_text" data-ft="{&quot;tn&quot;:&quot;*N&quot;,&quot;type&quot;:104}"><span class="_5afx"><span class="_58cl _5afz" aria-label="numeral">#</span><span class="_58cm">YoHagoDeporte</span></span></a> <a class="_58cn" href="https://web.facebook.com/hashtag/deportemipasion?source=feed_text" data-ft="{&quot;tn&quot;:&quot;*N&quot;,&quot;type&quot;:104}"><span class="_5afx"><span class="_58cl _5afz" aria-label="numeral">#</span><span class="_58cm">DeporteMiPasion</span></span></a> <a class="profileLink" href="https://web.facebook.com/indervalleoficial/?fref=mentions" data-hovercard="/ajax/hovercard/page.php?id=1411993282389538&amp;extragetparams=%7B%22fref%22%3A%22mentions%22%7D" data-hovercard-prefer-more-content-show="1">Indervalle</a> <a class="profileLink" href="https://web.facebook.com/SecDeporteCali/?fref=mentions" data-hovercard="/ajax/hovercard/page.php?id=339533024337&amp;extragetparams=%7B%22fref%22%3A%22mentions%22%7D" data-hovercard-prefer-more-content-show="1">Secretaría del Deporte y la Recreación</a>';
            $str .= '   <a class="_58cn" href="https://web.facebook.com/hashtag/'.$data[0]->name_sports.'?source=feed_text" data-ft="{&quot;tn&quot;:&quot;*N&quot;,&quot;type&quot;:104}"><span class="_5afx"><span class="_58cl _5afz" aria-label="numeral">#</span><span class="_58cm">'.$data[0]->name_sports."</span></span>";
            //$str .= "   <br/><br/>";
            //$str .= '   <img class="alignnone size-medium wp-image-35"  src="http://dashboard.miparchedeportivo.com/public/imgevent/'.$data[0]->id.'/'.$data[0]->image_real.'" title="'.$data[0]->title_big.'"/>';
            $str .= "</div>";
            
            
             
            return $str;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
}
