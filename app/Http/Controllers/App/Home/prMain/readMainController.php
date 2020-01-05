<?php
namespace App\Http\Controllers\App\Home\prMain;

use Illuminate\Routing\Controller;
use App\SyClass\Helpers\Utilities;
use App\SyClass\Helpers\Response;
use App\SyClass\System\Security;
use App\SyClass\System\Interfaces\InterfaceResponse;

class readMainController extends Controller
                            implements InterfaceResponse{

    private $_objResponse;
    private $_objFvs;
    
    public function __construct() {
        
    }
    
    /**
     * Create a new controller instance.
     *
     * @return void
    */
    public function main(){
        try{
            $arrDTask = null;
            // verificamos si es testigo
            if(\Auth::user()->security_roles_id == 1){
                // consultamos las tareas pendientes por hacer
                $arrDTask = (new \App\SyClass\App\Witnesses(null, $this))
                    ->getTasks();
            }
            
            return \View::make(Utilities::getCleanView(__NAMESPACE__))
                    ->with('arrDTask',$arrDTask)
                    ->with('datosRecurso',(new Security())->readDataRecursosView(__NAMESPACE__));
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
