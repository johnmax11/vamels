<?php
namespace App\Http\Controllers\App\Collaborators\prWitnesses;

use Illuminate\Routing\Controller;
use App\SyClass\Helpers\Utilities;
use App\SyClass\Helpers\Response;
use App\SyClass\System\Security;
use App\SyClass\System\Interfaces\InterfaceResponse;
class createWitnessesController extends Controller
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
            // borramos los datos de imagenes cargadas
            (new \App\SyClass\System\TmpMiscelaneos())->setDeleteTmpMiscelaneos(array(
                array("syslab_session_id",\Session::get('sys_users_income_id')),
                array("sys_users_id",\Auth::user()->id),
                array("parameters","filImagen1"),
            ));
            (new \App\SyClass\System\TmpMiscelaneos())->setDeleteTmpMiscelaneos(array(
                array("syslab_session_id",\Session::get('sys_users_income_id')),
                array("sys_users_id",\Auth::user()->id),
                array("parameters","filImagen2"),
            ));
            
            // consultar el id del municipio
            
            return \View::make(Utilities::getCleanView(__NAMESPACE__))
                        ->with('datosRecurso',(new Security())->readDataRecursosView(__NAMESPACE__));
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * salvar los datos de venta d productos
     * 
     * @return type
     * @throws \Exception
     */
    public function saveData(){
        try{
            
            $reglas = array(
                "txtNumeroIdentificacion"=>"required|min:6|max:255",
                "txtPrimerApellido"=>"required|min:3|max:255",
                "txtSegundoApellido"=>"max:255",
                "txtPrimerNombre"=>"required|min:3|max:255",
                "txtSegundoNombre"=>"max:255",
                
                "numNumeroTelefono"=>"required|min:7|max:10",
                "hdnPuestoVotacion"=>"required|numeric",
            );
            $alias = array(
                "txtNumeroIdentificacion"=>"Número de identificación",
                "txtPrimerApellido"=>"Primer apellido",
                "txtSegundoApellido"=>"Segundo apellido",
                "txtPrimerNombre"=>"Primer nombre",
                "txtSegundoNombre"=>"Segundo nombre",
                
                "numNumeroTelefono"=>"Nùmero de teléfono",
                "hdnPuestoVotacion"=>"Id del puesto votación",
            );
            
            // validamos los datos
            $rspValData = Utilities::validate($reglas, $alias);
            if($rspValData != null){
                return $rspValData;
            }
            
            $objWit = new \App\SyClass\App\Witnesses(null, $this);
            return $objWit->saveDataWitnesses((object)\Request::all());
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
