<?php
namespace App\SyClass\DB;
class FacCRUD{
    
    private $_obj;
    public function __construct($obj) {
        $this->_obj = $obj;
    }
    
    public function create($arrParameters = null){
        try{
            return $this->_obj->sCreate($arrParameters);;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    public function read($arrWhere=null, $arrGroupBy=null, $arrOrderBy=null, $arrLimit=null, $arrCols=null){
        try{
            return $this->_obj->sRead($arrWhere, $arrGroupBy, $arrOrderBy, $arrLimit, $arrCols);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    public function update($arrParameters = null,$arrWhere = null){
        try{
            $this->_obj->sUpdate($arrParameters, $arrWhere);
            return true;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    public function delete($arrWhere = null){
        try{
            $this->_obj->sDelete($arrWhere);
            return true;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    public function get(){
        try{
            return $this->_obj->get();
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
}

