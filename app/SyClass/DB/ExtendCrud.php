<?php
namespace App\SyClass\DB;
class ExtendCrud{
    private $_showFkValue = false;
    private $_objParent = null;
    private $_class = null;
    
    public function __construct($objParent,$class,$showFkValue) {
        $this->_objParent = $objParent;
        $this->_class = substr($class,strrpos($class,"\\")+1);
        $this->_showFkValue = $showFkValue;
    }
    
    /**
     * inserta en la tabla
     * 
     * @author john jairo cortes garcia <johnmax11@hotmail.com>
     * @created_at 17-04-2016
     * @param type $ArrParametros
     * @return type
     * @throws Exception
     */
    public function insertCustom($arrParametros = array()){
        try{
            // sacamos las columnas
            $strTable = strtolower(substr(preg_replace('/([a-z0-9])?([A-Z])/','$1_$2',$this->_class),1));
            $arrColumns = \Schema::getColumnListing($strTable);
            foreach ($arrColumns as $key => $value) {
                if(isset($arrParametros->$value) || !in_array($value,array('created_by','created_at','updated_by','updated_at'))){
                    (array_key_exists($value,$arrParametros)?$this->_objParent->$value = $arrParametros->$value:true);
                }else{
                    switch($value){
                        case "created_by":
                            $this->_objParent->$value = (isset(\Auth::user()->id)?\Auth::user()->id:1);
                            break;
                        case "created_at":
                            $this->_objParent->$value = @date('Y-m-d H:i:s');
                            break;
                    }
                }
            }
            //insertamos
            $this->_objParent->save();
            //retornamos el id conseguido
            return $this->_objParent->id;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * actualiza la tabla
     * 
     * @author john jairo cortes garcia <johnmax11@hotmail.com>
     * @created_at 17-04-2016
     * @param type $ArrParametros
     * @return type
     * @throws Exception
     */
    public function updateCustom($arrParametros = array(),$arrWhere=array()){
        try{
            //sacamos las columnas
            $arrSet = new \stdClass();
            $strTable = strtolower(substr(preg_replace('/([a-z0-9])?([A-Z])/','$1_$2',$this->_class),1));
            $arrColumns = \Schema::getColumnListing($strTable);
            foreach ($arrColumns as $key => $value) {
                if(isset($arrParametros->$value) || !in_array($value,array('created_by','created_at','updated_by','updated_at'))){
                    (array_key_exists($value,$arrParametros)?$arrSet->$value = $arrParametros->$value:true);
                }else{
                    switch($value){
                        case "updated_by":
                            $arrSet->$value = (isset(\Auth::user()->id)?\Auth::user()->id:1);
                            break;
                        case "updated_at":
                            $arrSet->$value = @date('Y-m-d H:i:s');
                            break;
                    }
                }
            }
            
            $strW = "";
            $numId = null;
            if(is_object($arrWhere) && count((array)$arrWhere)>0){
                //recorremos las condiciones del where
                foreach ($arrWhere as $key => $value) {
                    if(!is_array($value)){
                        $strW .= "where('$key','=','$value')->";
                    }else{
                        $strW .= "where('$key','$value[0]','$value[1]')->";
                    }
                    // buscamos el id
                    if($key == 'id'){
                        $numId = $value;
                    }
                }
            }
            
            // veiificamos el id
            if($numId!=null){
                // sacamos el pre de los datos cuando exista update por id
                $arrDataPrev = $this->_objParent->where('id','=',$numId)->get()->toArray();
            }
            
            // actualizamos
            $objM = $this->_objParent;
            eval('$objM::'.($strW!=""?$strW:"where('id','>','0')->").'update((array)json_decode(\''.json_encode($arrSet).'\'));');
            
            // tablas audit
            if($numId != null && count($arrDataPrev)>0){
                $arrDataPos = $this->_objParent->where('id','=',$numId)->get()->toArray();
                $objAudit = new \App\SyClass\DB\TablesAudit($this->_objParent,$this->_class,$arrDataPrev,$arrDataPos);
            }
            //retornamos el id actualizado
            return true;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * selecciona de una tabla
     * 
     * @author john jairo cortes garcia <johnmax11@hotmail.com>
     * @date 17-04-2016
     * @param type $arrWhere
     * @param type $arrGroupBy
     * @param type $arrOrderBy
     * @param type $arrLimit
     * @return type
     * @throws Exception
     */
    public function selectCustom($arrWhere = null,$arrGroupBy=null,$arrOrderBy=null,$arrLimit=null,$arrColumns=null){
        try{
            /**set query*/
            $objQuery = new FacExtendSQL($this->_showFkValue);
            
            $objQuery->strTable = strtolower(substr(preg_replace('/([a-z0-9])?([A-Z])/','$1_$2',$this->_class),1));
            //verificamos fk values
            if($this->_showFkValue && $arrColumns == null){
                $arrColumns = \Schema::getColumnListing($objQuery->strTable);
            }
            /**construct query*/
            $strExQy = $objQuery->setQuery($arrColumns,$arrWhere, $arrGroupBy, $arrOrderBy, $arrLimit,$this->_showFkValue);
            //echo '$arrResult = $this->_objParent'.$strExQy.'->toSql();';//exit;
            eval('$arrResult = $this->_objParent'.$strExQy.'->get();');
            return $arrResult;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * borra ua o mas filas cd acuerdo a las condiciones where
     * 
     * @author john jairo cortes garcia <johnmax11@hotmail.com>
     * @created_at 17-04-2016
     * @param type $arrParameters
     * @return boolean
     * @throws Exception
     */
    public function deleteCustom($arrWhere = null) {
        try {
            /** set query */
            $objQuery = new FacExtendSQL();
            $strExQy = $objQuery->setQuery(null,$arrWhere);
            eval('$arrResult = $this->_objParent' . $strExQy . '->delete();');

            return true;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
}