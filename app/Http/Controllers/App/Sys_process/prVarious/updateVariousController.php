<?php
namespace App\Http\Controllers\App\Sys_process\prVarious;

use Illuminate\Routing\Controller;
use App\SyClass\Helpers\Response;
use App\SyClass\System\Interfaces\InterfaceResponse;

class updateVariousController extends Controller
                            implements InterfaceResponse{
    
    public function __construct() {}
    
    /**
     * Create a new controller instance.
     *
     * @return void
    */
    public function main(){
        try{
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * actualiza la session del usuario en session
     * 
     * @return type
     * @throws \Exception
     */
    public function executeProcess(){
        try{
            // actualizamos el tiempo de sesson abierta del usuario
            return (new \App\SyClass\System\Users(null,$this))->updateTimeSession();
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    
    /*********************************++
     * interface response json
     * 
     * @param type $jsonResponse
     * @return type
     * @throws \Exception
     ************************************/
    public function callBackResponse($jsonResponse = null,$error=false,$type="success") {
        try{
            return (new Response())->responseRequest($jsonResponse,$error,$type);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
}
