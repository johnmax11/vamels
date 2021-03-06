<?php
namespace App\Http\Controllers\App\Clients\prManagement;

use Illuminate\Routing\Controller;
use App\SyClass\Helpers\Utilities;
use App\SyClass\Helpers\Response;
use App\SyClass\System\Security;
use App\SyClass\System\Interfaces\InterfaceResponse;
class readManagementController extends Controller
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
     * consulta los clientes de la base de datos
     * 
     * @return type
     * @throws \Exception
     */
    public function getDataAllClients(){
        try{
            // verificamos session
            if(!$this->_objFvs->verifySessionRol()){
                return $this->_objFvs->getProcessError();
            }
            
            $objFacCli = new \App\Facades\App\FacClients($this);
            return $objFacCli->getAllClientsGrid();
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * busca los productos por el codigo ingresado
     * 
     * @return type
     * @throws \Exception
     */
    public function getDataClientAutocomplete(){
        try{
            // validamos los campos
            $reglas = array(
                'q'=>'required',
            );

            $alias = array(
                'q'=>'Query de busqueda',
            );
            
            // validamos los datos
            $rspValData = Utilities::validate($reglas, $alias);
            if($rspValData != null){
                return $rspValData;
            }
            
            $objCli = new \App\SyClass\App\Clients($this);
            return $objCli->getClientsByAutoComplete(\Request::input('q'));
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
