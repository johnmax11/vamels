<?php
namespace App\Http\Controllers\App\Inventory\prProducts;
use Illuminate\Routing\Controller;
class createProductsController extends Controller
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
    
    public function saveDataProduct(){
        try{
            // verificamos session
            if(!$this->_objFvs->verifySessionRol()){
                return $this->_objFvs->getProcessError();
            }
            
            // validamos los datos
            $rspValData = $this->validateSaveDataProduct();
            if($rspValData != null){
                return $rspValData;
            }
            
            $objFProd = new \App\Facades\App\FacProducts($this);
            return $objFProd->setSaveDataProduct((object)\Request::all());
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * 
     * @return type
     * @throws \Exception
     */
    private function validateSaveDataProduct(){
        try{
            // validamos los campos
            $reglas = array(
                'selPrefijo'=>'required',
                'txtCodigo'=>'required',
                'txtNombre'=>'required',
                'txtVrCompra'=>'required',
                'txtVrVenta'=>'required',
            );
            
            $alias = array(
                'selPrefijo'=>'Pefijo del producto',
                'txtCodigo'=>'Codigo de la producto',
                'txtNombre'=>'Nombre del producto',
                'txtVrCompra'=>'Valor de compra del producto',
                'txtVrVenta'=>'Valor de venta del producto',
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
