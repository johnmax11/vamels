<?php
namespace App\Http\Controllers\App\Collaborators\prWitnesses;

use Illuminate\Routing\Controller;
use App\SyClass\Helpers\Utilities;
use App\SyClass\Helpers\Response;
use App\SyClass\System\Security;
use App\SyClass\System\AccountData;
use App\SyClass\System\Interfaces\InterfaceResponse;
class readWitnessesController extends Controller
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
                    ->with('dataUsers',(new AccountData(null, null))->getDataProfiles(false))
                    ->with('datosRecurso',(new Security())->readDataRecursosView(__NAMESPACE__));
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * retorna los datos de los testigos creados
     * 
     * @return type
     * @throws \Exception
     */
    public function getData(){
        try{
            return (new \App\SyClass\App\Witnesses(null, $this))
                    ->getDataAll((object)\Request::all());
        } catch (\Exception $ex) {
            throw $ex;
        }
    } // END function
    
    /**
     * consutla un witnesses por id
     * 
     * @param type $id
     * @return type
     * @throws \Exception
     */
    public function getDataById($id){
        try{
            return (new \App\SyClass\App\Witnesses(null, $this))
                    ->getDataWitnessesById($id);
        } catch (\Exception $ex) {
            throw $ex;
        }
    } // END function
    
    public function getStatistics(){
        try{
            return (new \App\SyClass\App\Witnesses(null, $this))
                    ->getDataStatitics();
        } catch (\Exception $ex) {
            throw $ex;
        } // END function
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
