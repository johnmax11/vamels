<?php
/**
 * @author john jairo cortes garcia <john.cortes@syslab.so>
 * @created_at 29-03-2015
 */
namespace App\SyClass\Helpers;
class Utilities{
    
    /**
     * organiza en  varios arrays los parametros recibidos y devuelve un array
     * de arrrays usado para los select custom
     * 
     * @param type $arrParameters
     * @return type
     * @throws \Exception
     */
    static function getColumnsParameters($arrParameters = array()){
        try{
            $arrReturn = array();
            foreach ($arrParameters as $key => $value) {
                $arrReturn[] = array($key,$value);
            }
            return $arrReturn;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * consulta el menu completo
     * 
     * @author john jairo cortes garcia <john.cortes@syslab.so>
     * @created_at 16-04-2015
     * @return type
     * @throws \Exception
     */
    static function getAllMenuByRol(){
        try{
            $objFacSecurityAccess = new App\Facades\FacSecurityAccess();
            return $objFacSecurityAccess->getMenuAccessCompleteParser();
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * implode de array
     * 
     * @author john jairo cortes garcia <john.cortes@syslab.so>
     * @created_at 30-03-2015
     * @param type $namespace
     * @return type
     * @throws Exception
     */
    static function setImplodeFileEx($namespace){
        try{
            $class = str_replace("Controller","",$namespace[6]);
            return $namespace[3]."/".strtolower($namespace[4])."/".$namespace[5].'/'.$class;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * 
     * @author john jairo cortes garcia <john.cortes@syslab.sos>
     * @return type
     * @throws \Exception
     */
    static function getMenuCompleto(){
        try{
            return array(static::getAllMenuByRol());
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /*put your code here*/    
    static function RandomString($length=10){        
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    
    /**
     * filtra una matriz quitando columnas para retornar esta,
     * la matriz tiene  q tener una estrutura definida
     * $matriz->rows
     * 
     * @author john jairo cortes garcia <john.cortes@syslab.so>
     * @param type $matriz
     * @param type $arrEx
     * @return \stdClass
     * @throws Exception
     */
    static function filterArray($matriz,$arrEx,$tipoFilter = "OUT"){
        try{
            $n_array = new stdClass();
            $n_filas = count($matriz->rows);
            for($i=0;$i<$n_filas;$i++){
                //echo print_r($matriz->rows[$i]->attributes,true);
                $fila_n = new stdClass();
                $row_data = $matriz->rows[$i]->getAttributes();
                foreach ($row_data as $key => $value) {
                    if($tipoFilter == "OUT"){
                        if(!in_array($key, $arrEx)){
                            $fila_n->$key = $value;
                        }
                    }else{
                        if(in_array($key, $arrEx)){
                            $fila_n->$key = $value;
                        }
                    }
                }
                $n_array->rows[$i] = $fila_n;
            }
            return $n_array;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    /**
    * A temporary method of generating GUIDs of the correct format for our DB.
    * @return String contianing a GUID in the format: aaaaaaaa-bbbb-cccc-dddd-eeeeeeeeeeee
    *
    * All Rights Reserved.
    * Contributor(s): ______________________________________..
    */
    static function create_guid(){
        $microTime = microtime();
        list($a_dec, $a_sec) = explode(" ", $microTime);

        $dec_hex = dechex($a_dec* 1000000);
        $sec_hex = dechex($a_sec);

        static::ensure_length($dec_hex, 5);
        static::ensure_length($sec_hex, 6);

        $guid = "";
        $guid .= $dec_hex;
        $guid .= static::create_guid_section(3);
        $guid .= '-';
        $guid .= static::create_guid_section(4);
        $guid .= '-';
        $guid .= static::create_guid_section(4);
        $guid .= '-';
        $guid .= static::create_guid_section(4);
        $guid .= '-';
        $guid .= $sec_hex;
        $guid .= static::create_guid_section(6);

        return $guid;
   }

   /**
    * 
    * @param type $characters
    * @return type
    */
   static function create_guid_section($characters){
        $return = "";
        for($i=0; $i<$characters; $i++)
        {
                $return .= dechex(mt_rand(0,15));
        }
        return $return;
   }

   /**
    * 
    * @param type $string
    * @param type $length
    */
   static function ensure_length(&$string, $length){
        $strlen = strlen($string);
        if($strlen < $length)
        {
                $string = str_pad($string,$length,"0");
        }
        else if($strlen > $length)
        {
                $string = substr($string, 0, $length);
        }
   }
   
   /**
    * return el path del archivo clean desde el folder App/
    * 
    * @author john jairo cortes garcia <john.cortes@syslab.so>
    * @created_at 17-04-2016
    * @param type $path
    * @return type
    */
   static function getPathFileClean($path,$sep="\\"){
        try{
            return str_replace('/',$sep,substr($path,strpos($path,'App'),strpos($path,'_factory')-strpos($path,'App')-1));
        }catch(Exception $ex){
           throw $ex;
        }
   }

   /**
    * se encarga de sacar todos los actions asociados a un programa
    * 
    * @author john jairo cortes garcia
    * @created_at 17-0542016
    * @param type $mod
    * @param type $prog
    * @return array
    * @throws Exception
    */
   static function getActionsView($mod,$prog){
       try{
            $arrAct = array();
            $path = base_path("/resources/views/app");
            $dir_handle = @opendir($path) or die("No se pudo abrir $path");
            while ($file = readdir($dir_handle)) {
                if($file == "." || $file == ".." || $file == "index.php" ){ 
                    continue;
                }
                if($file == $mod){
                    // buscamos para abrir la carpeta del programa
                    $dir_handle_prog = @opendir(base_path("/resources/views/app/").$file) or die("No se pudo abrir $file");
                    while ($file_prog = readdir($dir_handle_prog)) {
                        if($file_prog == "." || $file_prog == ".." || $file_prog == "index.php" ){ 
                            continue;
                        }
                        if($file_prog == "pr".ucfirst(strtolower($prog))){
                            // incluimos en los actions todos los archivos de los action de programa
                            $dir_handle_act = @opendir(base_path("/resources/views/app/").$file."/".$file_prog) or die("No se pudo abrir $file_prog");
                            while ($file_act = readdir($dir_handle_act)) {
                                if($file_act == "." || $file_act == ".." || $file_act == "index.php" ){ 
                                    continue;
                                }
                                if(!is_dir(base_path("/resources/views/app/").$file."/".$file_prog."/".$file_act)){
                                    if(strrpos($file_act,ucfirst(strtolower($prog)))){
                                        array_push($arrAct,substr($file_act,0,strrpos($file_act,ucfirst(strtolower($prog)))));
                                    }
                                }
                            }
                        }
                    }
                }
            }
            return $arrAct;
       } catch (Exception $ex) {
           throw $ex;
       }
   }
   
   /**
    * 
    * @author john jairo cortes garcia <johnmax11@hotmail.com>
    * @param type $fecha_inicio
    * @param type $fecha_fin
    * @return type
    * @throws Exception
    */
   static function get_diff_date($fecha_inicio,$fecha_fin){        
        try{
            $dFecIni = str_replace("-","",$fecha_inicio);        
            $dFecFin = str_replace("-","",$fecha_fin);        
            @ereg( "([0-9]{4})([0-9]{1,2})([0-9]{1,2})", $dFecIni, $aFecIni);        
            @ereg( "([0-9]{4})([0-9]{1,2})([0-9]{1,2})", $dFecFin, $aFecFin);        
            $date1 = @mktime(0,0,0,$aFecIni[2], $aFecIni[3], $aFecIni[1]);        
            $date2 = @mktime(0,0,0,$aFecFin[2], $aFecFin[3], $aFecFin[1]);        
            return round(($date2 - $date1) / (60 * 60 * 24));    
        } catch (Exception $ex) {
           throw $ex;
        }
    }
    
    /**
     * 
     * @param DateTime $from
     * @param DateTime $to
     * @return int
     */
    static function number_of_working_days($from, $to) {
        $workingDays = [1, 2, 3, 4, 5]; # date format = N (1 = Monday, ...)
        $holidayDays = ['*-12-25', '*-01-01', '*-05-01']; # variable and fixed holidays

        $from = new DateTime($from);
        $to = new DateTime($to);
        $to->modify('+1 day');
        $interval = new DateInterval('P1D');
        $periods = new DatePeriod($from, $interval, $to);

        $days = 0;
        foreach ($periods as $period) {
            if (!in_array($period->format('N'), $workingDays)) continue;
            if (in_array($period->format('Y-m-d'), $holidayDays)) continue;
            if (in_array($period->format('*-m-d'), $holidayDays)) continue;
            $days++;
        }
        return $days;
    }

    /**
     * construye la cadena correspondiente a la vista necesitada en ese momento
     * 
     * @param type $namespace
     * @param type $action
     * @return string
     * @throws \Exception
     */
    static function getCleanView($namespace){
        try{
            // sacamos la clase
            $arrDClase = explode("\\",debug_backtrace()[1]['class']);
            
            $strCrud = $arrDClase[count($arrDClase)-1];
            // get crud
            $chr = mb_substr ($strCrud, 4, 1, "UTF-8");
            if(ctype_upper($chr)){
                // read
                $crud_action = mb_substr ($strCrud, 0, 4, "UTF-8");
            }else{
                // update o create
                $crud_action = mb_substr ($strCrud, 0, 6, "UTF-8");
            }
            
            $arrD = explode("\\",$namespace);
            // folder principal
            $strView = "app.";
            // folder modulo
            $strView .= $arrD[4].".";
            // folder progama
            $strView .= $arrD[5].".";
            // action
            $strView .= $crud_action.substr($arrD[5],2);
            
            return $strView;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * mostrar el nombre del dia en spanish
     * 
     * @param type $dayInt
     * @return string
     * @throws \Exception
     */
    static function getNameDaySpanish($dayInt){
        try{
            switch((int)$dayInt){
                case 0: return "Domingo";
                case 1: return "Lunes";
                case 2: return "Martes";
                case 3: return "Miercoles";
                case 4: return "Jueves";
                case 5: return "Viernes";
                case 6: return "Sabado";
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * consulta el nombre del mes en spanish
     * 
     * @param type $dayMonth
     * @return string
     * @throws \Exception
     */
    static function getNameMonthSpanish($dayMonth){
        try{
            switch($dayMonth){
                case 1: return "Enero";
                case 2: return "Febrero";
                case 3: return "Marzo";
                case 4: return "Abril";
                case 5: return "Mayo";
                case 6: return "Junio";
                case 7: return "Julio";
                case 8: return "Agosto";
                case 9: return "Septiembre";
                case 10: return "Octubre";
                case 11: return "Noviembre";
                case 12: return "Diciembre";
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    static function setSessionTokenUpdate($id){
        try{
            $token = \Utilities::RandomString()."$".$id."$".\Utilities::RandomString();
            \Session::put($token,$token);
            return $token;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * 
     * @param type $tokenRequest
     * @return boolean
     * @throws \Exception
     */
    static function getSessionIdUpdate($tokenRequest){
        try{
            if(\Session::get($tokenRequest) != null){
                $arrId = explode("$",\Session::get($tokenRequest));
                return $arrId[1];
            }
            return false;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * begin transaccion
     */
    static function begin(){
        \DB::beginTransaction();
    }
    
    /**
     * commit de la transaccion
     */
    static function commit(){
        \DB::commit();
    }
    
    /**
     * rollback de la transaccion
     */
    static function rollback(){
        \DB::rollback();
    }
    
    /**
     * quita los tags html d un string
     * 
     * @param type $str
     * @return type
     * @throws \Exception
     */
    static function quitTagsHtml($str){
        try{
            // buscamos el cierre del primer div
            $n_1 = strpos($str,">");
            $str_1 = substr($str,$n_1+1);
            
            // buscamos la apertura del segundo div
            $n_2 = strpos($str_1,"<");
            $str_2 = trim(substr($str_1,0,$n_2));
            
            return $str_2;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
}