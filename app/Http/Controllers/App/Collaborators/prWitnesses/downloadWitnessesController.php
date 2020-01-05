<?php
namespace App\Http\Controllers\App\Collaborators\prWitnesses;

use Illuminate\Routing\Controller;
use App\SyClass\Helpers\Utilities;
use App\SyClass\Helpers\Response;
use App\SyClass\System\Security;
use App\SyClass\System\Interfaces\InterfaceResponse;
class downloadWitnessesController extends Controller
                        implements InterfaceResponse{

    private $_objResponse;
    
    public function __construct() {}
    
    /**
     * retorna la imagen del witnesses
     * 
     * @return type
     * @throws \Exception
     */
    public function getImagen($idwit, $nimagen){
        try{
            
            return (new \App\SyClass\App\Witnesses(null, $this))
                    ->getImagenByIdWitnesses($idwit, $nimagen);
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
