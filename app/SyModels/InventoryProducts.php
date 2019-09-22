<?php
/**
 * Description of products
 *
 * @author programador1
 */
namespace App\SyModels;
class InventoryProducts extends \Eloquent {
    //put your code here
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'inventory_products';
    public $timestamps = false;
    private $_showFkValue = false;
    public $extSQL = null;
    private $_obj = null;
    
    public function __construct($id=null,$showFkValue=false) {
        $this->_showFkValue = $showFkValue;
        $this->extSQL = new \App\SyClass\DB\ExtendCrud($this,__CLASS__,$this->_showFkValue);
        if($id != null){
            $this->findOrCreate("id",$id);
        }
    }
    
    
    // Put this in any model and use
    // Modelname::findOrCreate($id);
    public function findOrCreate($column,$id)
    {
        $obj = static::where($column , '=', $id)->first();
        if($obj){
            $obj::updating(function($objN){
                $objAudit = new \App\Models\Helpers\TablesAudit($objN,__CLASS__);
            });
        }
        $this->_obj = $obj;
        //$obj = static::find($id);
        return $obj ? $obj : new static;
    }
    
    public function get(){
        try{
            return $this->_obj;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * 
     * @param type $value
     * @throws \Exception
     */
    public function setShowFkValues($value){
        try{
            $this->_showFkValue = $value;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * 
     * @return type
     * @throws \Exception
     */
    public function getShowFkValues(){
        try{
            return $this->_showFkValue;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /*******************************************/
    /*******************************************/
    // crud //
    
    /**
     * inserta los datos de la tabla
     * 
     * @author John Jairo Cortes Garci <johnmax11@hotmail.com>
     * @created_at 17-04-2016
     * @modified_at 17-04-2016 
     * @param type $arrParameters
     * @return type
     * @throws \Exception
     */
    public function sCreate($arrParameters = null){
        try{
            return $this->extSQL->insertCustom($arrParameters);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * lee los datos de la tabla
     * 
     * @author John Jairo Cortes Garci <johnmax11@hotmail.com>
     * @created_at 17-04-2016
     * @modified_at 17-04-2016
     * @param type $arrParamters
     * @return type
     * @throws \Exception
     */
    public function sRead($arrParametros = array(),$arrGroupBy=array(),$arrOrderBy=array(),$arrLimit=array(),$arrColumns=null){
        try{
            $arrData =  $this->extSQL->selectCustom(
                            (is_object($arrParametros)?\utilities::getColumnsParameters($arrParametros):$arrParametros),
                            $arrGroupBy,
                            $arrOrderBy,
                            $arrLimit,
                            $arrColumns
                        );
            
            return $arrData;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * actualiza los datos de la tabla
     * 
     * @author John Jairo Cortes Garci <johnmax11@hotmail.com>
     * @created_at 17-04-2016
     * @modified_at 17-04-2016
     * @param type $arrParameters
     * @param type $arrWhere
     * @return type
     * @throws \Exception
     */
    public function sUpdate($arrParameters = null,$arrWhere = null){
        try{
            return $this->extSQL->updateCustom($arrParameters,$arrWhere);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * borra los datos de la tabla
     * 
     * @author John Jairo Cortes Garci <johnmax11@hotmail.com>
     * @created_at 17-04-2016
     * @modified_at 17-04-2016
     * @param type $arrParameters
     * @return type
     * @throws \Exception
     */
    private function sDelete($arrParameters = null){
        try{
            return $this->extSQL->deleteCustom($arrParameters);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
}
