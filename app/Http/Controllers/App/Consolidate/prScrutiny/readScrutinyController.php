<?php
namespace App\Http\Controllers\App\Consolidate\prScrutiny;

use Illuminate\Routing\Controller;
use App\SyClass\Helpers\Utilities;
use App\SyClass\Helpers\Response;
use App\SyClass\System\Security;
use App\SyClass\System\AccountData;
use App\SyClass\System\Interfaces\InterfaceResponse;
class readScrutinyController extends Controller
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
     * consultar las zonas
     * 
     * @throws \Exception
     */
    public function getDataZonesByIdCity(){
        try{
            return (new \App\SyClass\App\Params($this))
                ->getZonesByIdCity((object)\Request::all());
        } catch (\Exception $ex) {
            throw $ex;
        }
    } // END FUNCTION
    
    /**
     * 
     * @return type
     * @throws \Exception
     */
    public function getDataPlazesByIdZones(){
        try{
            return (new \App\SyClass\App\Params($this))
                ->getPlacesByIdZone((object)\Request::all());
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * 
     * @return type
     * @throws \Exception
     */
    public function getDataTablesByIdPlaces(){
        try{
            return (new \App\SyClass\App\Params($this))
                ->getTablesByIdPlace((object)\Request::all());
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
