<?php
namespace App\Http\Controllers\App\Clients\prManagement;
use Illuminate\Routing\Controller;
class createManagementController extends Controller
                        implements \App\Facades\IntResponseController{

    private $_objResponse;
    private $_objFvs;
    
    public function __construct() {
        $this->_objFvs = new \App\Facades\FacVerifySession();
        $this->_objResponse = new \App\Helpers\ResponseCustom();
    }
    
    /**
     * methodo main de retorno del view
     *
     * @return void
    */
    public function main(){
        try{
            // verificamos session
            if(!$this->_objFvs->verifySessionRol()){
                return $this->_objFvs->getProcessError();
            }
            return \View::make(\Utilities::getCleanView(__NAMESPACE__))
                    ->with('datosRecurso',$this->_objFvs->getRecursoArray());
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * crea un cliente nuevo en la base de datos
     * 
     * @return type
     * @throws \Exception
     */
    public function saveDataClients(){
        try{
            // verificamos session
            if(!$this->_objFvs->verifySessionRol()){
                return $this->_objFvs->getProcessError();
            }
            
            // validamos los datos
            $rspValData = $this->validateSaveDataClients();
            if($rspValData != null){
                return $rspValData;
            }
            
            $objFacCli = new \App\Facades\App\FacClients($this);
            return $objFacCli->setCreateClienteNew((object)\Request::all());
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * 
     * @return type
     * @throws \Exception
     */
    private function validateSaveDataClients(){
        try{
            // validamos los campos
            $reglas = array(
                'txtNombreCompleto'=>'required',
                'txtTelefono'=>'required',
            );
            
            $alias = array(
                'txtNombreCompleto'=>'Nombre completo del cliente',
                'txtTelefono'=>'Telefono fijo o celular',
            );
            
            $validar = \Validator::make(\Request::all(), $reglas)->setAttributeNames($alias);
            if($validar->fails()){
                return $this->_objResponse->json(
                        array(
                            'msgResponseFirst'=>"Error validando campos",
                            'msgResponseDetails'=>$this->_objResponse->orderDetails($validar->getMessageBag()->toArray(),'error')
                        ),true,"warning");
            }
            
            return null;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    
    /**
     * interface response json
     * 
     * @param type $jsonResponse
     * @return type
     * @throws \Exception
     */
    public function callBackResponse($jsonResponse = null) {
        try{
            return $this->_objResponse->json($jsonResponse);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
}
