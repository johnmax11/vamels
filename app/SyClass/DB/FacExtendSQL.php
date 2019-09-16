<?php
/**
 * se encarga de extender las funcionalidad para conectarse a la BD
 */
namespace App\SyClass\DB;
class FacExtendSQL{
    public $__model = null;
    private $__strSQL = null;
    public $strTable = null;
    public $showFkValues = null;
    
    public function __construct($showFkValues=false) {
        $this->showFkValues = $showFkValues;
    }
    
    public function innerJoin($objJoin){
        echo '-->'.get_class($objJoin);
        
        $arrObj = utilidades::splitAtUpperCase(get_class($objJoin));
        $strTblJoin = '';
        for($i=1;$i<count($arrObj);$i++){
            $strTblJoin .= $arrObj[$i].'_';
        }
        $strTblJoin = substr($strTblJoin,0,strlen($strTblJoin)-1);
        echo '--->'.print_r($arrObj,true);
        $this->__strSQL .= "join(,'=',".$strTblJoin.".)";
        
        /***/
        
        return $this;
    }
    
    /**
     * devuelve el query encargado de armar la parte de SELECT
     * 
     * @param type $arrCols
     * @return string
     * @throws \App\Models\Helpers\Exception
     */
    public function mSelect($arrCols){
        try{
            $strQry = "";
            if(count($arrCols)>0){
                $strQry .= "->select(";
                foreach ($arrCols as $key => $value) {
                    if(!is_array($value)){
                        if(!strpos($value,'fk') ){
                            $strQry .= '"'.$this->strTable.".".$value.'",';
                            if($this->showFkValues == true){
                                if($value == 'created_by'){
                                    $strQry .= '"sys_users.email AS created_by_fk_email","sys_users.security_roles_id AS created_by_fk_security_roles_id",';
                                }
                                if(substr($value,0,3)=='_id'){

                                }
                            }
                        }else{
                            $strQry .= '"'.$value.'",';
                        }
                    }// fin if
                    else{
                        $strQry .= "\DB::raw('".strtoupper($value[0])."(".strtolower($value[1]).") as ".strtolower($value[0])."_".strtolower(isset($value[2])?$value[2]:$value[1])." '),";
                    }
                }
                $strQry = substr($strQry,0,strlen($strQry)-1);
                $strQry .= ")";
            }
            return $strQry;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * se encarga de hacer los el join con las tablas ESTANDARES
     * 
     * @author john jairo cortes garcia <john.cortes@syslab.so>
     * @param type $arrCols
     * @return string
     * @throws \App\Models\Helpers\Exception
     */
    public function mJoin($arrCols){
        try{
            $strQy = "";
            foreach ($arrCols as $key => $value) {
                if(!is_array($value)){
                    if($value == 'created_by'){
                        $strQy .= "->join('sys_users','".$this->strTable.".created_by','=','sys_users.id')";
                    }
                    if(substr($value,0,3)=='_id'){

                    }
                }
            }
            return $strQy;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * crea string con metodos where
     * 
     * @author john jairo cortes garcia <john.cortes@syslab.so>
     * @date 17-04-2016
     * @param type $arrWhere
     * @return type
     * @throws Exception
     */
    public function mWhere($arrWhere){
        try{
            $strQry = "";
            for($i=0;$i<count($arrWhere);$i++){
                $strExp = $arrWhere[$i];
                
                //verificamos si es un campo de la tabla principal
                if(strpos($strExp[0],".")===false){
                    $strExp[0] = $this->strTable.".".$strExp[0];
                }
                
                if(count($strExp)==2){
                    $strQry .= "->where('".$strExp[0]."','=','".$strExp[1]."')";
                }else{
                    if(count($strExp)==1){
                        $strQry .= "->whereRaw('".$strExp[0]."')";
                    }else{
                        if(strtoupper($strExp[2])=="IN" || strtoupper($strExp[2])=="NOT IN"){
                            $arrIn = explode(",",$strExp[1]);
                            $strArrIn = "array(";
                            for($j=0;$j<count($arrIn);$j++){
                                $strArrIn .= $arrIn[$j].",";
                            }
                            $strArrIn = substr($strArrIn,0,strlen($strArrIn)-1);
                            $strArrIn .= ")";
                            if(strtoupper($strExp[1])=="IN"){
                                $strQry .= "->whereIn('".$strExp[0]."',".$strArrIn.")";
                            }elseif(strtoupper($strExp[1])=="NOT IN"){
                                $strQry .= "->whereNotIn('".$strExp[0]."',".$strArrIn.")";
                            }
                        }else{
                            if(strtoupper($strExp[2])=="IS NULL"){
                                $strQry .= "->whereNull('".$strExp[0]."')";
                            }elseif(strtoupper($strExp[2])=="IS NOT NULL"){
                                $strQry .= "->whereNotNull('".$strExp[0]."')";
                            }elseif(count($strExp)==3){
                                $strQry .= "->where('".$strExp[0]."','".$strExp[1]."','".$strExp[2]."')";
                            }
                        }
                    } // fin else
                }
            }
            return ($strQry);
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * concatena las clausulas order by
     * 
     * @author john jairo cortes garcia <john.cortes@syslab.so>
     * @date 17-04-2016
     * @param type $arrORderBy
     * @return type
     * @throws Exception
     */
    public function mOrderBy($arrORderBy){
        try{
            $strQry = "";
            if($arrORderBy == "RANDOM"){
                $strQry .= "->inRandomOrder()";
            }else{
                for($i=0;$i<count($arrORderBy);$i++){
                    $strExp = $arrORderBy[$i];
                    $strQry .= "->orderBy('".$strExp[0]."','".$strExp[1]."')";
                }
            }
            return ($strQry);
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * retorn el group by
     * 
     * @author john jairo cortes garcia <john.cortes@syslab.so>
     * @return type
     * @throws Exception
     */
    public function mGroupBy($arrGroupBy){
        try{
            $strQry = "";
            for($i=0;$i<count($arrGroupBy);$i++){
                $strQry .= "'".$arrGroupBy[$i]."',";
            }
            $strQry = substr($strQry,0,strlen($strQry)-1);
            return "->groupBy(".$strQry.")";
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * return el limit de una consulta
     * 
     * @author john jairo cortes garcia <john.cortes@syslab.so>
     * @param type $arrLimit
     * @return type
     * @throws Exception
     */
    public function mLimit($arrLimit){
        try{
            return "->skip(".$arrLimit[0].")->take(".$arrLimit[1].")";
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * construc del query a execute
     * 
     * @author john jairo cortes garcia <johnmax11@hotmail.com>
     * @date 17-04-2016
     * @param type $arrParametros
     * @param type $arrGroupBy
     * @param type $arrOrderBy
     * @param type $arrLimit
     * @return type
     * @throws Exception
     */
    public function setQuery($arrColumns=null,$arrWhere=null,$arrGroupBy=null,$arrOrderBy=null,$arrLimit=null,$showFkValues=false){
        try{
            $strExQy = "";
            
            //verificamos las columnas
            if($arrColumns != null){
                $strExQy .= $this->mSelect($arrColumns);
                /**verificamos las llaves fk*/
                $strExQy .= $this->mJoin($arrColumns);
            }
            
            //verificamos clausulas where
            if($arrWhere!=null){
                $strExQy .= $this->mWhere($arrWhere);
            }
            
            // veriificamos group by
            if($arrGroupBy!=null){
                $strExQy .= $this->mGroupBy($arrGroupBy);
            }
            
            // verificamos order by
            if($arrOrderBy!=null){
                $strExQy .= $this->mOrderBy($arrOrderBy);
            }
            
            /**verificamos limites**/
            if($arrLimit!=null){
                $strExQy .= $this->mLimit($arrLimit);
            }
            return $strExQy;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}