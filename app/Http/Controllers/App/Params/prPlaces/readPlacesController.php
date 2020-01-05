<?php
namespace App\Http\Controllers\App\Params\prPlaces;

use Illuminate\Routing\Controller;
use App\SyClass\Helpers\Utilities;
use App\SyClass\Helpers\Response;
use App\SyClass\System\Security;
use App\SyClass\System\Interfaces\InterfaceResponse;
class readPlacesController extends Controller
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
     * retorna los datos por autocomplete
     * 
     * @return type
     * @throws \Exception
     */
    public function autocomplete(){
        try{
            // validamos datos
            // validamos los campos
            $reglas = array(
                'q'=>'required|min:3',
            );
            
            $alias = array(
                'q'=>'Query de consulta',
            );
            
            // validamos los datos
            $rspValData = Utilities::validate($reglas, $alias);
            if($rspValData != null){
                return $rspValData;
            }
            
            return (new \App\SyClass\App\Params($this))
                    ->getDataByAutocomplete((object)\Request::all());
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
