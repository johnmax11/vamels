<?php
namespace App\Http\Controllers\App\Sys_process\prMenu;

use Illuminate\Routing\Controller;
use App\SyClass\Helpers\Response;
use App\SyClass\System\Interfaces\InterfaceResponse;

class readMenuController extends Controller
                            implements InterfaceResponse{

    private $_objResponse;
    private $_objFvs;
    
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
     * se encarga de crear el menuz de la izquierda
     * 
     * @throws \Exception
     */
    public function getHtmlMenuIzq(){
        try{
            return (new \App\SyClass\System\Menu($this))->constructMenuIzqByRole();
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
