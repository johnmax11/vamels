<?php
namespace App\Http\Controllers\App\Inventory\prWarehouse;
use Illuminate\Routing\Controller;
class readWarehouseController extends Controller
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
     * consultar el inventario de productos
     * 
     * @return type
     * @throws \Exception
     */
    public function getDataWarehouse(){
        try{
            // verificamos session
            if(!$this->_objFvs->verifySessionRol()){
                return $this->_objFvs->getProcessError();
            }
            
            $objFacSales = new \App\Facades\App\FacProducts($this);
            return $objFacSales->getDataInventoryAll();
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * consulta los datos de un warehouse
     * 
     * @param type $idInvProd
     * @return type
     * @throws \Exception
     */
    public function getInventoryProductsById($idInvProd){
        try{
            // verificamos session
            if(!$this->_objFvs->verifySessionRol()){
                return $this->_objFvs->getProcessError();
            }
            
            $idUpd = \Utilities::getSessionIdUpdate(\Request::input('_token_update'));
            if($idUpd == false){
                return $this->_objResponse->json(
                array(
                    'msgResponseFirst'=>"Error campo de control TOKEN, actualize la pagina y vuelva a intentarlo",
                  ),true);
            }
            if($idInvProd != $idUpd){
                return $this->_objResponse->json(
                array(
                    'msgResponseFirst'=>"Error campo de control TOKEN(2), actualize la pagina y vuelva a intentarlo",
                  ),true);
            }
            
            $objFacPr = new \App\Facades\App\FacProducts($this);
            return $objFacPr->getInventoryProductsById($idInvProd);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * consulta el track del inventory product
     * 
     * @return type
     * @throws \Exception
     */
    public function getDetailsTrack(){
        try{
            // verificamos session
            if(!$this->_objFvs->verifySessionRol()){
                return $this->_objFvs->getProcessError();
            }
            
            // validamos los datos
            $rspValData = $this->validateGetDetailsTrack();
            if($rspValData != null){
                return $rspValData;
            }
            
            $objFProd = new \App\Facades\App\FacProducts($this);
            return $objFProd->getTrackInventoryProd(\Request::input('idinvprod'));
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * 
     * @return type
     * @throws \Exception
     */
    private function validateGetDetailsTrack(){
        try{
            // validamos los campos
            $reglas = array(
                'idinvprod'=>'required',
            );
            
            $alias = array(
                'idinvprod'=>'Id de inventario track',
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
            return $this->_objResponse->json(array('rows'=>$jsonResponse));
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
}
