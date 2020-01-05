<?php
namespace App\SyClass\App;
use \App\SyClass\DB\FacCRUD;
use \App\SyModels;
/**
 * @author john jairo cortes garcia <johnmax11@hotmail.com>
 * @created_at 19-08-2017
 */
class Params{
    
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
     * datos de los places por autocomplete
     * 
     * @param type $request
     * @return type
     * @throws \Exception
     */
    public function getDataByAutocomplete($request){
        try{
            $arrData = (new FacCRUD(new SyModels\ParamsPlaces()))
                    ->read(
                        array(
                            array("params_departaments_divipol_id",$request->dpto),
                            array("params_cities_divipol_id",$request->city),
                            array("place_name","LIKE","%".$request->q."%")
                        ),
                        null,
                        array(array('place_name','ASC')));
            
            // recorremos los datos y totalizamos las ventas
            $arrResponseJson = array();
            $nRows = count($arrData);
            
            for($i=0;$i<$nRows;$i++){
                
                // set columns response
                $arrResponseJson[$i] = new \stdClass();
                $arrResponseJson[$i]->id = $arrData[$i]->id;
                $arrResponseJson[$i]->value = $arrData[$i]->place_name;
                
                $arrResponseJson[$i]->place_name = $arrData[$i]->place_name;
                $arrResponseJson[$i]->zone = $arrData[$i]->zone;
                $arrResponseJson[$i]->place_number = $arrData[$i]->place_number;
                $arrResponseJson[$i]->state_name = $arrData[$i]->state_name;
                $arrResponseJson[$i]->cities_name = $arrData[$i]->cities_name;
                $arrResponseJson[$i]->params_departaments_divipol_id = $arrData[$i]->params_departaments_divipol_id;
                $arrResponseJson[$i]->params_cities_divipol_id = $arrData[$i]->params_cities_divipol_id;
                $arrResponseJson[$i]->indicador_puesto_id = $arrData[$i]->indicador_puesto_id;
                $arrResponseJson[$i]->comune_number = $arrData[$i]->comune_number;
                $arrResponseJson[$i]->comune_name = $arrData[$i]->comune_name;
                $arrResponseJson[$i]->address = $arrData[$i]->address;
                $arrResponseJson[$i]->tables_count = substr($arrData[$i]->tables_count,0,1);
                
                // consultamos las mesas usadas
                $arrDataTableUsed = (new FacCRUD(new SyModels\Witnesses()))
                ->read(array(
                    array("department_code",$arrData[$i]->params_departaments_divipol_id),
                    array("city_code",$arrData[$i]->params_cities_divipol_id),
                    array("zone_code",$arrData[$i]->zone),
                    array("place_code",$arrData[$i]->place_number)
                ));
                
                $arrResponseJson[$i]->used_tables = "";
                // recorremos y agregamos las mesas usadas
                $nr = count($arrDataTableUsed);
                if($nr>0){
                    for((int)$k=0; $k<$nr ;$k++){
                        $arrResponseJson[$i]->used_tables .= $arrDataTableUsed[$k]->number_table.",";
                    }
                    $arrResponseJson[$i]->used_tables = substr($arrResponseJson[$i]->used_tables,0,strlen($arrResponseJson[$i]->used_tables)-1);
                }
            }
            
            // set response
            return $this->_interfaceResponse->callBackResponse(array("rows"=>$arrResponseJson));
        } catch (\Exception $ex) {
            throw $ex;
        }
    } // END FUNCTION
    
    /**
     * carga de zona por id de city
     * 
     * @param type $request
     * @return type
     * @throws \Exception
     */
    public function getZonesByIdCity($request){
         try{
            $objFacPlaces = (new FacCRUD(new SyModels\ParamsPlaces()));
            $arrData = $objFacPlaces->read(
                array(
                    array("params_departaments_divipol_id", $request->department_id),
                    array("params_cities_divipol_id", $request->cities_id)
                ),
                array("zone"),
                null,
                null,
                array("zone")
            );
             
            $nrows = count($arrData);
            $arrResponseJson = array();
            for($i=0; $i<$nrows ;$i++){
                $arrResponseJson[$i] = new \stdClass();
                
                $arrResponseJson[$i]->id = $arrData[$i]->zone;
                $arrResponseJson[$i]->name = $arrData[$i]->zone;
            }  
             
            // set response
            return $this->_interfaceResponse->callBackResponse(array("rows"=>$arrResponseJson));
         } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * carga los places por id de zona
     * 
     * @param type $request
     * @return type
     * @throws \Exception
     */
    public function getPlacesByIdZone($request){
         try{
            $objFacPlaces = (new FacCRUD(new SyModels\ParamsPlaces()));
            $arrData = $objFacPlaces->read(
                array(
                    array("params_departaments_divipol_id", $request->department_id),
                    array("params_cities_divipol_id", $request->cities_id),
                    array("zone", $request->zone),
                ),
                array("place_number","place_name"),
                null,
                null,
                array("place_number","place_name")
            );
             
            $nrows = count($arrData);
            $arrResponseJson = array();
            for($i=0; $i<$nrows ;$i++){
                $arrResponseJson[$i] = new \stdClass();
                
                $arrResponseJson[$i]->id = $arrData[$i]->place_number;
                $arrResponseJson[$i]->name = $arrData[$i]->place_number." - ".$arrData[$i]->place_name;
            }  
             
            // set response
            return $this->_interfaceResponse->callBackResponse(array("rows"=>$arrResponseJson));
        } catch (\Exception $ex) {
            throw $ex;
        }
    } // END FUNCTION
    
    /**
     * carga las tables d un puesto
     * 
     * @return type
     * @throws \Exception
     */
    public function getTablesByIdPlace($request){
        try{
            $objFacPlaces = (new FacCRUD(new SyModels\ParamsPlaces()));
            $arrData = $objFacPlaces->read(
                array(
                    array("params_departaments_divipol_id", $request->department_id),
                    array("params_cities_divipol_id", $request->cities_id),
                    array("zone", $request->zone),
                    array("place_number", $request->place),
                )
            );
             
            $nrows = count($arrData);
            $arrResponseJson = array();
            for($i=0; $i<$nrows ;$i++){
                $arrResponseJson[$i] = new \stdClass();
                
                $arrResponseJson[$i]->tables_count = $arrData[$i]->tables_count;
            }  
             
            // set response
            return $this->_interfaceResponse->callBackResponse(array("rows"=>$arrResponseJson));
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
}