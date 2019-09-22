<?php
namespace App\Http\Controllers\App\Inventory\prWarehouse;
use Illuminate\Routing\Controller;
class updateWarehouseController extends Controller
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
    public function main($id){
        try{
            // verificamos session
            if(!$this->_objFvs->verifySessionRol()){
                return $this->_objFvs->getProcessError();
            }
            return \View::make(\Utilities::getCleanView(__NAMESPACE__))
                    ->with('datosRecurso',$this->_objFvs->getRecursoArray())
                    ->with('_token_update',\Utilities::setSessionTokenUpdate($id));
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * actualiza lo datos del producto
     * 
     * @return type
     * @throws \Exception
     */
    public function saveDataUpdateInventory(){
        try{
            // verificamos session
            if(!$this->_objFvs->verifySessionRol()){
                return $this->_objFvs->getProcessError();
            }
            
            // validamos los datos
            $rspValData = $this->validateSaveDataUpdateInventory();
            if($rspValData != null){
                return $rspValData;
            }
            
            $idUpd = \Utilities::getSessionIdUpdate(\Request::input('_token_update'));
            $objFProd = new \App\Facades\App\FacProducts($this);
            return $objFProd->setUpdateInventoryProduct((object)\Request::all(),$idUpd);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * 
     * @return type
     * @throws \Exception
     */
    private function validateSaveDataUpdateInventory(){
        try{
            // validamos los campos
            $reglas = array(
                'txtCantidadNueva'=>'required',
                'selMotivo'=>'required'
            );
            
            $alias = array(
                'txtCantidadNueva'=>'Cantidad nueva',
                'selMotivo'=>'Motivo de la actualizacion'
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
