<?php
namespace App\Http\Controllers\App\Box\prSales;

use Illuminate\Routing\Controller;
use App\SyClass\Helpers\Utilities;
use App\SyClass\Helpers\Response;
use App\SyClass\System\Security;
use App\SyClass\System\Interfaces\InterfaceResponse;

class createSalesController extends Controller
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
     * salvar los datos de venta d productos
     * 
     * @return type
     * @throws \Exception
     */
    public function saveDataServer(){
        try{
            // verificamos session
            if(!$this->_objFvs->verifySessionRol()){
                return $this->_objFvs->getProcessError();
            }
            
            // validamos los datos
            $rspValData = $this->validateSaveDataServer();
            if($rspValData != null){
                return $rspValData;
            }
            
            $objFacSales = new \App\Facades\App\FacSales($this);
            return $objFacSales->setVentaProductos((object)\Request::all());
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * 
     * @return type
     * @throws \Exception
     */
    private function validateSaveDataServer(){
        try{
            // validamos los campos
            $reglas = array(
                'txtFechaVenta'=>'required',
                'selVendedor'=>'required',
                'selCodigo-0'=>'required',
                'txtDescuento-0'=>'required',
                
                'hdnContRows'=>'required'
            );
            
            $alias = array(
                'txtFechaVenta'=>'Fecha de venta',
                'selVendedor'=>'Usuario vendedor',
                'selCodigo-0'=>'Codigo producto',
                'txtDescuento-0'=>'Descuento producto',
                
                'hdnContRows'=>'Contador de filas'
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
