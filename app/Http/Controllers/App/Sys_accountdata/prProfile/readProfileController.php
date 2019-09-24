<?php
namespace App\Http\Controllers\App\Sys_accountdata\prProfile;

use Illuminate\Routing\Controller;
use App\SyClass\Helpers\Utilities;
use App\SyClass\Helpers\Response;
use App\SyClass\System\Security;
use App\SyClass\System\Interfaces\InterfaceResponse;
class readProfileController extends Controller
                        implements InterfaceResponse{

    private $_objResponse;
    
    public function __construct() {}
    
    /**
     * methodo main de retorno del view
     *
     * @return void
    */
    public function main(){
        try{
            return \View::make(Utilities::getCleanView(__NAMESPACE__))
                        ->with('datosRecurso',(new Security())->readDataRecursosView(__NAMESPACE__));
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * retorna los datos de la vey
     * 
     * @return type
     * @throws \Exception
     */
    public function getDataProfiles(){
        try{
            return (new \App\SyClass\System\AccountData(null, $this))
                    ->getDataProfiles((object)\Request::all());
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
