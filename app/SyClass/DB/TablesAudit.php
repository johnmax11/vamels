<?php
namespace App\SyClass\DB;
/**
 * Description of TablesAuditories
 *
 * @author programador1
 */
class TablesAudit extends \Eloquent {
    
    /**
     * constructor de la clase
     * 
     * @param type $objNew
     * @param type $nModel
     * @return type
     */
    public function __construct($objUpd,$nModel,$dataPrev,$dataPost) {
        $strModelN = strtolower(substr(preg_replace('/([a-z0-9])?([A-Z])/','$1_$2',$nModel),1));
        if(!$this->existTable($strModelN.'_audit')){
            return;
        }
        $arrDataOrigin = $dataPrev[0];//$objNew->getOriginal();
        $arrDataNew = $dataPost[0];//$objUpd->toArray();
        $arrDataIn = array();
        $cont = 0;
        
        foreach ($arrDataOrigin as $key => $value) {
            if($arrDataOrigin[$key] !== $arrDataNew[$key]){
                $arrDataIn[$cont]['parent_id'] = $arrDataOrigin['id'];
                $arrDataIn[$cont]['field_column'] = $key;
                $arrDataIn[$cont]['before_value'] = ($value==""||$value==null?null:$value);
                $arrDataIn[$cont]['after_value'] = $arrDataNew[$key];
                $arrDataIn[$cont]['created_by'] = (isset(\Auth::user()->id)?\Auth::user()->id:1);
                $arrDataIn[$cont]['created_at'] = $arrDataNew['updated_at'];
                $cont++;
            }
        }
        //
        \DB::table($strModelN.'_audit')->insert($arrDataIn);
    }
    
    /**
     * valida la existencia de una tabla
     * 
     * @author john airo cortes garcia <john.cortes@syslab.so>
     * @date 17-04-2016
     * @param type $nTable
     * @return type
     * @throws Exception
     */
    private function existTable($nTable){
        try{
            return \Schema::hasTable($nTable);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
}
