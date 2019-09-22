<?php
namespace App\Http\Controllers\App\Inventory\prProducts;
use Illuminate\Routing\Controller;
class readProductsController extends Controller
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
     * consulta todos los productos
     * 
     * @return type
     * @throws \Exception
     */
    public function getProductsAll(){
        try{
            // verificamos session
            if(!$this->_objFvs->verifySessionRol()){
                return $this->_objFvs->getProcessError();
            }
            
            $objFacSales = new \App\Facades\App\FacProducts($this);
            return $objFacSales->getProductsAll();
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
    public function getProductsByCode(){
        try{
            // verificamos session
            if(!$this->_objFvs->verifySessionRol()){
                return $this->_objFvs->getProcessError();
            }
            
            // validamos los datos
            $rspValData = $this->validateGetProductsByCode();
            if($rspValData != null){
                return $rspValData;
            }
            
            $objFacSales = new \App\Facades\App\FacProducts($this);
            return $objFacSales->getProductsByCode(\Request::input('q'));
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * 
     * @return type
     * @throws \Exception
     */
    private function validateGetProductsByCode(){
        try{
            // validamos los campos
            $reglas = array(
                'q'=>'required',
            );
            
            $alias = array(
                'q'=>'Query de busqueda',
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
     * saca los datos del producto por id
     * 
     * @param type $idProduct
     * @return type
     * @throws \Exception
     */
    public function getProductsById($idProduct){
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
            if($idProduct != $idUpd){
                return $this->_objResponse->json(
                array(
                    'msgResponseFirst'=>"Error campo de control TOKEN(2), actualize la pagina y vuelva a intentarlo",
                  ),true);
            }
            
            $objFacPr = new \App\Facades\App\FacProducts($this);
            return $objFacPr->getProductsById($idProduct);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * consulta los productos que no esta en inventario
     * 
     * @return type
     * @throws \Exception
     */
    public function getProductsByInventory(){
        try{
            // verificamos session
            if(!$this->_objFvs->verifySessionRol()){
                return $this->_objFvs->getProcessError();
            }
            
            $objFacPr = new \App\Facades\App\FacProducts($this);
            return $objFacPr->getDataProductsByInventoryNotExist();
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
