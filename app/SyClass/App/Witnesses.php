<?php
namespace App\SyClass\App;
use \App\SyClass\DB\FacCRUD;
use \App\SyModels;
/**
 * @author john jairo cortes garcia <johnmax11@hotmail.com>
 * @created_at 19-08-2017
 */
class Witnesses{
    
    private $_interfaceResponse;
    private $id_witnesses;
    
    /**
     * 
     * @param type $objInterfaceResponse
     */
    public function __construct($idWitnesses,$objInterfaceResponse) {
        // verificamos si hay interface de respuesta
        if($objInterfaceResponse != null){
            $this->_interfaceResponse = $objInterfaceResponse;
        }
        if($idWitnesses != null){
            $this->_interfaceResponse = $idWitnesses;
            return new SyModels\Witnesses($idWitnesses);
        }
    }
    
    /**
     * mostrar los testigos
     * 
     * @param type $origenClass
     * @return type
     * @throws \Exception
     */
    public function getDataAll($request){
        try{
            $arrWhere = null;
            if(isset($request->cc) && $request->cc != null){
                $arrWhere = array(
                    array("identification_number",$request->cc)
                );
            }else{
                if(\Auth::user()->security_roles_id != 1){
                    $arrWhere = array(array("created_by",\Auth::user()->id));
                }elseif(\Auth::user()->security_roles_id == 1){
                    $arrWhere = array(array("created_by",$request->municipio_id));
                }
            }
            
            $arrData = (new FacCRUD(new SyModels\Witnesses()))
                    ->read(
                        $arrWhere,
                        null,
                        array(array('id','DESC'))
                    );
            
            // recorremos los datos y totalizamos las ventas
            $arrResponseJson = array();
            $nRows = count($arrData);
            for($i=0;$i<$nRows;$i++){
                
                // set columns response
                $arrResponseJson[$i] = new \stdClass();
                
                $arrResponseJson[$i]->update = true;
                $arrResponseJson[$i]->delete = true;
                $arrResponseJson[$i]->id = $arrData[$i]->id;
                $arrResponseJson[$i]->identification_number = $arrData[$i]->identification_number;
                if(\Auth::user()->security_roles_id == 1){
                    $v1 = ($arrData[$i]->validate_c == null?"<i></i>":'<i class="material-icons prefix" style="font-size:12pt;">looks_one</i>');
                    $v2 = ($arrData[$i]->validate_j == null?"<i></i>":'<i class="material-icons prefix" style="font-size:12pt;">looks_two</i>');
                    $arrResponseJson[$i]->identification_number = 
                             $v1
                            .$v2
                            ."<a href='javascript:void(0)' onclick='(new objPrimary.main()).getModalValidate(".$arrData[$i]->id.", this);'>"
                                .$arrResponseJson[$i]->identification_number
                            ."</a>"
                            ."<span style='display:none;'>"
                            .$arrData[$i]->last_first_name.";"
                            .$arrData[$i]->last_second_name.";"
                            .$arrData[$i]->first_first_name.";"
                            .$arrData[$i]->first_second_name.";"
                            .$arrData[$i]->validate_j.";"
                            ."</span>"
                            ;
                }
                $arrResponseJson[$i]->last_name = ($arrData[$i]->last_first_name ." ".$arrData[$i]->last_second_name);
                $arrResponseJson[$i]->first_name = ($arrData[$i]->first_first_name ." ".$arrData[$i]->first_second_name);
                $arrResponseJson[$i]->phone_number = $arrData[$i]->phone_number;
                
                $arrResponseJson[$i]->detail_place = "<span style='font-size:10pt;'>".$arrData[$i]->place_name."</span><br/>".
                                                     "<div style='font-size:8pt;font-style:italic;'>ZN: ".$arrData[$i]->zone_code." / NP: ".$arrData[$i]->place_code."<br/>".
                                                     "".$arrData[$i]->city_name." / ".$arrData[$i]->department_name."<br/>";
                
                // sacamos las imagenes
                $arrResponseJson[$i]->imagenes  = "";
                $arrResponseJson[$i]->imagenes .= "<div>";
                
                // imagen 1
                if($arrData[$i]->photo_1 != null){
                    $url_1 = \Config::get('syslab.path_url_web')."/app/collaborators/witnesses/download/".$arrData[$i]->id."/".$arrData[$i]->photo_1;
                    $arrResponseJson[$i]->imagenes .= "     1: <a target='_blank' href='".$url_1."'>Ver</a>";
                }
                // imagen 2
                if($arrData[$i]->photo_2 != null){
                    $arrResponseJson[$i]->imagenes .= "<br/>";
                    $url_2 = \Config::get('syslab.path_url_web')."/app/collaborators/witnesses/download/".$arrData[$i]->id."/".$arrData[$i]->photo_2;
                    $arrResponseJson[$i]->imagenes .= "     2: <a target='_blank' href='".$url_2."'>Ver</a>";
                }
                
                $arrResponseJson[$i]->imagenes .= "</div>";
                
                $objUs = (new \App\SyClass\System\Users($arrData[$i]->created_by, null));
                $arrResponseJson[$i]->created_by = $objUs->getObject()->get()->email;
            }
            
            // set response
            return $this->_interfaceResponse->callBackResponse(array("rows"=>$arrResponseJson));
        } catch (\Exception $ex) {
            throw $ex;
        }
    } // END function
    
    /**
     * consulta los datos de un witnesses por su id
     * 
     * @param type $id
     * @throws \Exception
     */
    public function getDataWitnessesById($id){
        try{
            $arrData = (new FacCRUD(new SyModels\Witnesses()))
                        ->read(array(array("id",$id)));
            
            // consultamos el id del puesto
            $objFacPlaces = (new FacCRUD(new SyModels\ParamsPlaces()));
            $arrDataPlace = $objFacPlaces->read(array(
                array("params_departaments_divipol_id",$arrData[0]->department_code),
                array("params_cities_divipol_id",$arrData[0]->city_code),
                array("zone",$arrData[0]->zone_code),
                array("place_number",$arrData[0]->place_code),
            ));
            
            // set id place
            $arrData[0]->places_id = $arrDataPlace[0]->id;
            
            if($arrData[0]->photo_1 != null){
                $arrData[0]->photo_1 = \Config::get('syslab.path_url_web')."/app/collaborators/witnesses/download/".$arrData[0]->id."/".$arrData[0]->photo_1;
            }
            if($arrData[0]->photo_2 != null){
                $arrData[0]->photo_2 = \Config::get('syslab.path_url_web')."/app/collaborators/witnesses/download/".$arrData[0]->id."/".$arrData[0]->photo_2;
            }
            
            return $this->_interfaceResponse->callBackResponse(array("rows"=>$arrData));
        } catch (\Exception $ex) {
            throw $ex;
        }
    } // END function
    
    /**
     * crea un witnesses en base de datos
     * 
     * @param type $request
     * @throws \Exception
     */
    public function saveDataWitnesses($request){
        try{
            // consultamos los datos en divipol
            $objDataDivipol = (new SyModels\ParamsPlaces($request->hdnPuestoVotacion));
            
            $idInsert = (new FacCRUD(new SyModels\Witnesses()))
                ->create((object)array(
                    "sys_account_data_id"=>\Session::get('account_data_id'),
                    "identification_number"=>$request->txtNumeroIdentificacion,
                    "last_first_name"=>trim(ucwords(mb_strtolower($request->txtPrimerApellido))),
                    "last_second_name"=>trim(ucwords(mb_strtolower($request->txtSegundoApellido))),
                    "first_first_name"=>trim(ucwords(mb_strtolower($request->txtPrimerNombre))),
                    "first_second_name"=>trim(ucwords(mb_strtolower($request->txtSegundoNombre))),
                    
                    "phone_number"=>$request->numNumeroTelefono,
                    "political_code"=>$request->selPartidoPolitico,
                    "department_name"=>$objDataDivipol->get()->state_name,
                    "city_name"=>$objDataDivipol->get()->cities_name,
                    "zone_code"=>$objDataDivipol->get()->zone,
                    "place_name"=>$objDataDivipol->get()->place_name,
                    "department_code"=>$request->selDepartamento,
                    "city_code"=>$request->selCiudad,
                    "place_code"=>$objDataDivipol->get()->place_number,
                    "photo_1"=>null,
                    "photo_2"=>null
                ));
            
            $objFacTmpMisc = new \App\SyClass\System\TmpMiscelaneos();
            
            // movemos las imagenes
            // movemos imagen 1
            $strImage1 = $objFacTmpMisc->moveDocumentByParams(
                \Auth::user()->id,
                "filImagen1",
                "witnesses/2019/".$idInsert."/files/img"
            );
            if($strImage1 == null){ 
                //return $this->_interfaceResponse->callBackResponse(array("msgResponseFirst"=>"Problema al guardar la imagen 1"),true,"error");
            }else{
                (new FacCRUD(new SyModels\Witnesses()))
                    ->update(
                        (object)array("photo_1"=>$strImage1),
                        (object)array("id"=>$idInsert)
                    );
            }
            
            // movemos imagen 2
            $strImage2 = $objFacTmpMisc->moveDocumentByParams(
                \Auth::user()->id,
                "filImagen2",
                "witnesses/2019/".$idInsert."/files/img"
            );
            if($strImage2 == null){ 
                //return $this->_interfaceResponse->callBackResponse(array("rows"=>null),true,"error");
            }else{
                (new FacCRUD(new SyModels\Witnesses()))
                    ->update(
                        (object)array("photo_2"=>$strImage2),
                        (object)array("id"=>$idInsert)
                    );
            }
            
            // set response
            return $this->_interfaceResponse->callBackResponse();
        } catch (\Exception $ex) {
            throw $ex;
        }
    } //  END function
    
    /**
     * crea un witnesses en base de datos
     * 
     * @param type $request
     * @throws \Exception
     */
    public function updateDataWitnesses($request, $idWit){
        try{
            // validamos los nombres completos
            if(isset($request->action) && $request->action == "validate_names"){
                (new FacCRUD(new SyModels\Witnesses()))
                    ->update(
                        (object)array(
							"identification_number"=>trim($request->cc),
                            "last_first_name"=>trim(ucwords(mb_strtolower($request->p_app))),
                            "last_second_name"=>trim(ucwords(mb_strtolower($request->s_app))),
                            "first_first_name"=>trim(ucwords(mb_strtolower($request->p_nom))),
                            "first_second_name"=>trim(ucwords(mb_strtolower($request->s_nom))),
                            "first_second_name"=>trim(ucwords(mb_strtolower($request->s_nom))),
                            "validate_c"=>"Y"
                        ),
                        (object)array("id"=>$idWit)
                    );
            }
            else{
                if(isset($request->action) && $request->action == "validate_jurado"){
                    (new FacCRUD(new SyModels\Witnesses()))
                        ->update(
                            (object)array(
                                "validate_j"=>($request->opt_c=="Y"?"Y":"N")
                            ),
                            (object)array("id"=>$idWit)
                        );
                }
                else{
                    // consultamos los datos en divipol
                    $objDataDivipol = (new SyModels\ParamsPlaces($request->hdnPuestoVotacion));

                    (new FacCRUD(new SyModels\Witnesses()))
                        ->update(
                            (object)array(
                                "identification_number"=>$request->txtNumeroIdentificacion,
                                "last_first_name"=>trim(ucwords(mb_strtolower($request->txtPrimerApellido))),
                                "last_second_name"=>trim(ucwords(mb_strtolower($request->txtSegundoApellido))),
                                "first_first_name"=>trim(ucwords(mb_strtolower($request->txtPrimerNombre))),
                                "first_second_name"=>trim(ucwords(mb_strtolower($request->txtSegundoNombre))),

                                "phone_number"=>$request->numNumeroTelefono,
                                "political_code"=>$request->selPartidoPolitico,
                                "department_name"=>$objDataDivipol->get()->state_name,
                                "city_name"=>$objDataDivipol->get()->cities_name,
                                "zone_code"=>$objDataDivipol->get()->zone,
                                "place_name"=>$objDataDivipol->get()->place_name,
                                "department_code"=>$request->selDepartamento,
                                "city_code"=>$request->selCiudad,
                                "place_code"=>$objDataDivipol->get()->place_number
                            ),
                            (object)array("id"=>$idWit)
                        );

                    $objFacTmpMisc = new \App\SyClass\System\TmpMiscelaneos();

                    // movemos las imagenes
                    // movemos imagen 1
                    $strImage1 = $objFacTmpMisc->moveDocumentByParams(
                        \Auth::user()->id,
                        "filImagen1",
                        "witnesses/2019/".$idWit."/files/img"
                    );
                    if($strImage1 == null){ 
                        //return $this->_interfaceResponse->callBackResponse(array("msgResponseFirst"=>"Problema al guardar la imagen 1"),true,"error");
                    }else{
                        (new FacCRUD(new SyModels\Witnesses()))
                            ->update(
                                (object)array("photo_1"=>$strImage1),
                                (object)array("id"=>$idWit)
                            );
                    }

                    // movemos imagen 2
                    $strImage2 = $objFacTmpMisc->moveDocumentByParams(
                        \Auth::user()->id,
                        "filImagen2",
                        "witnesses/2019/".$idWit."/files/img"
                    );
                    if($strImage2 == null){ 
                        //return $this->_interfaceResponse->callBackResponse(array("msgResponseFirst"=>"Problema al guardar la imagen 2"),true,"error");
                    }else{
                        (new FacCRUD(new SyModels\Witnesses()))
                            ->update(
                                (object)array("photo_2"=>$strImage2),
                                (object)array("id"=>$idWit)
                            );
                    }
                } // END else update principal
            } // END else primario
            
            // set response
            return $this->_interfaceResponse->callBackResponse();
        } catch (\Exception $ex) {
            throw $ex;
        }
    } //  END function
    
    /**
     * retoorna una imagen del witnesses
     * 
     * @param type $idwit
     * @param type $nimagen
     * @throws \Exception
     */
    public function getImagenByIdWitnesses($idwit, $nimagen){
        try{
            // carga de archivos img del sistema
            $path = storage_path() . '/app/files/witnesses/2019/'.$idwit.'/files/img/'. $nimagen; // Podés poner cualquier ubicacion que quieras dentro del storage

            if(!\File::exists($path)) abort(404); // Si el archivo no existe
    
            $file = \File::get($path);
            $type = \File::mimeType($path);
    
            $response = \Response::make($file, 200);
            $response->header("Content-Type", $type);
    
            // set response
            return $this->_interfaceResponse->callBackResponse($response, false, "not-json");
        } catch (\Exception $ex) {
            throw $ex;
        }
    } // END function
    
    /**
     * borra un registro del witnesses por su id
     * 
     * @param type $idwit
     * @throws \Exception
     */
    public function deleteWitnessesById($idwit){
        try{
            // validamos que el id pertenezca al usuario session
            $objW = (new SyModels\Witnesses($idwit));
            if($objW->get()->created_by != \Auth::user()->id){
                // set response
                return $this->_interfaceResponse->callBackResponse(array("msgResponseFirst"=>("Usted no puede remover este registro!")), false, "warning");
            }
            
            (new FacCRUD(new SyModels\Witnesses()))->delete(array(array("id",$idwit)));
            
            // set response
            return $this->_interfaceResponse->callBackResponse();
        } catch (\Exception $ex) {
            throw $ex;
        }
    } // END function
    
    /**
     * retorna los datos de las estadisticas
     * 
     * @throws \Exception
     */
    public function getDataStatistics(){
        try{
            // consultamos los usuarios
            $objF = (new FacCRUD(new SyModels\SysUsers()));
            $objF->read(array(
                array()
            ));
        } catch (\Exception $ex) {
            throw $ex;
        }
    } // END function
    
    /**
     * consulta las tareas de un witnessses
     * 
     * @throws \Exception
     */
    public function getTasks(){
        try{
            $objFWTask = (new FacCRUD(new SyModels\WitnessesTasks()));
            $arrData = $objFWTask->read(array(
                array("status_task","P"),
                array("identification_number",\Auth::user()->email)
            ));
            
            $arrResponse = array();
            $nrows = count($arrData);
            for($i=0; $i<$nrows ;$i++){
                // consultamos el addres del place
                $objFPlace = (new FacCRUD(new SyModels\ParamsPlaces()));
                $arrDPlace = $objFPlace->read(array(
                    array("params_departaments_divipol_id",$arrData[$i]->department_code),
                    array("params_cities_divipol_id",$arrData[0]->city_code),
                    array("zone",$arrData[0]->zone_code),
                    array("place_number",$arrData[0]->place_code)
                ));
                
                $arrResponse[$i] = new \stdClass();
                $arrResponse[$i]->id = str_pad($arrData[$i]->id,5,"0",STR_PAD_LEFT);
                $arrResponse[$i]->department_name = $arrData[$i]->department_name;
                $arrResponse[$i]->city_name = $arrData[$i]->city_name;
                $arrResponse[$i]->place_name = $arrData[$i]->place_name;
                $arrResponse[$i]->number_table = $arrData[$i]->number_table;
                $arrResponse[$i]->zone_code = $arrData[$i]->zone_code;
                $arrResponse[$i]->place_code = $arrData[$i]->place_code;
                
                $arrResponse[$i]->address_place = $arrDPlace[0]->address;
                
                // buscamos las imagens que ha cargado
                $arrResponse[$i]->arr_images = $this->getImagesByIdTasks($arrData[$i]->id);
            }
            //echo "<pre>".print_r($arrResponse,true);
            return $arrResponse;
        } catch (\Exception $ex) {
            throw $ex;
        }
    } // END function
    
    /**
     * retorna el array de images
     * 
     * @param type $idtask
     * @return \stdClass
     * @throws \Exception
     */
    public function getImagesByIdTasks($idtask){
        try{
            $objFTImg = (new FacCRUD(new SyModels\WitnessesTasksImages()));
            $arrDimages = $objFTImg->read(
                array(
                    array("witnesses_tasks_id",$idtask),
                    array("status","A")
                ),
                null,
                array(
                    array("order_corporation","ASC"),
                    array("created_at","ASC")
                )
            );
            $arr_images = null;
            if(count($arrDimages)>0){
                $arr_images = array();
                for($k=0; $k<count($arrDimages) ;$k++){
                    $arr_images[$k] = new \stdClass();
                    $arr_images[$k]->id_image_task = $arrDimages[$k]->id;
                    $arr_images[$k]->id_task = $idtask;
                    $arr_images[$k]->photo = $arrDimages[$k]->photo;
                    $arr_images[$k]->corporation = $arrDimages[$k]->corporation;
                }
            }
            
            return $arr_images;
        } catch (\Exception $ex) {
            throw $ex;
        }
    } // END FUNCTION
    
    /**
     * 
     * @param type $request
     * @throws \Exception
     */
    public function uploadImageTasks($request, $idTask){
       try{
            $nameField = $request->nameField;

            // verificar cargue d imagen
            if (count(\Request::file($nameField))==0){
                return $this->_interfaceResponse->callBackResponse(array('msgResponseFirst'=>'El archivo no subio correctamente'),true);
            }
            
            // verificar id task pertenciente
            if($this->verifyIdTask($idTask) == false){
                // set response
                return $this->_interfaceResponse->callBackResponse(array("rows"=>null),true,"error");
            }
           
            switch(substr($nameField,strlen($nameField)-3)){
                case "ALC": $orderCorp = 1; break;
                case "GOB": $orderCorp = 2; break;
                case "COO": $orderCorp = 3; break;
                case "ASA": $orderCorp = 4; break;
                case "JAL": $orderCorp = 5; break;
            }
            // agregar imagen
            $objFac = (new FacCRUD(new SyModels\WitnessesTasksImages()));
            $lastId = $objFac->create((object)array(
                "witnesses_tasks_id"=>$idTask,
                "identification_number"=>\Auth::user()->email,
                "status"=>"A",
                "corporation"=>substr($nameField,strlen($nameField)-3),
                "order_corporation"=>$orderCorp
            ));
           
            ////////////////////////////////////////////////////////////////
            // movemos el archivo a la carpeta definitiva
            $uuid = \App\SyClass\Helpers\Utilities::create_guid();
            $name = \Request::file($nameField)->getClientOriginalName();
            $extension = \Request::file($nameField)->getClientOriginalExtension();
            $size = round(((\Request::file($nameField)->getSize()/1024)/1024),2);
            $ext_r = $extension;
            
            $nfile = (substr($nameField,strlen($nameField)-3))."_".@date('Ymd-His').'_'.$uuid.'.'.$ext_r;
            $rutaC = 'witnesses/tasks/'.$idTask."/".substr($nameField,strlen($nameField)-3);
            
            // verificamos folder
            (new \App\SyClass\System\TmpMiscelaneos())->verifyCreateFolder($rutaC);
            
            // ruta del archivo
            $path_n = storage_path().'/app/files/'.$rutaC;
            if($size>1){
                // resize image and save
                $image = new \App\SyClass\Helpers\SimpleImage();
                $image->load(\Request::file($nameField));
                $image->resize(1024, 1524);
                $image->save($path_n."/".$nfile);
            }else{
                \Request::file($nameField)->move($path_n, $nfile);
            }
            
            // actualizamos el nombre de la imagen
            (new FacCRUD(new SyModels\WitnessesTasksImages()))
                ->update(
                    (object)array("photo"=>$nfile),
                    (object)array("id"=>$lastId)
                );
           
            // set response
            return $this->_interfaceResponse->callBackResponse(array("rows"=>$lastId));
        } catch (\Exception $ex) {
            throw $ex;
        }
    } // ND FUNCTION
    
    /**
     * 
     * @return boolean
     * @throws \Exception
     */
    public function verifyIdTask($id){
        try{
            // verificamos si la tarea es de este usuario
            $objFacWit = (new FacCRUD(new SyModels\WitnessesTasks()));
            $arrDataTas = $objFacWit->read(array(
                array("identification_number",\Auth::user()->email),
                array("id",(int)$id)
            ));
            
            if(count($arrDataTas) == 0){
                return false;
            }
            
            return true;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * borra un tsk por el id
     * 
     * @param type $idtask
     * @throws \Exception
     */
    public function deleteTaskImageById($idtaskimage){
        try{
            // consultamos los datos d la imagen
            $objTImg = (new FacCRUD(new SyModels\WitnessesTasksImages($idtaskimage)));
            
            // verificar si el task es del user
            $rsp = $this->verifyIdTask($objTImg->get()->witnesses_tasks_id);
            
            if($rsp == true){
                (new FacCRUD(new SyModels\WitnessesTasksImages()))
                    ->update(
                        (object)array("status"=>"D"),
                        (object)array("id"=>$idtaskimage)
                    );
            }else{
                return $this->_interfaceResponse->callBackResponse(array('msgResponseFirst'=>'UD no puede borrar esta imagen!'),true);
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * 
     * @param type $idwittask
     * @param type $nimagen
     * @throws \Exception
     */
    public function downloadImagenByIdTask($idwittask, $nimagen){
        try{
            // carga de archivos img del sistema
            $path = storage_path() . '/app/files/witnesses/tasks/'.$idwittask.'/'.substr($nimagen,0,3).'/'. $nimagen; // Podés poner cualquier ubicacion que quieras dentro del storage

            if(!\File::exists($path)) abort(404); // Si el archivo no existe
    
            $file = \File::get($path);
            $type = \File::mimeType($path);
    
            $response = \Response::make($file, 200);
            $response->header("Content-Type", $type);
    
            // set response
            return $this->_interfaceResponse->callBackResponse($response, false, "not-json");
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
}