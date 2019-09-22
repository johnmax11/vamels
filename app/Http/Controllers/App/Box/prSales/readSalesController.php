<?php
namespace App\Http\Controllers\App\Box\prSales;
use Illuminate\Routing\Controller;
use App\SyClass\Helpers\Utilities;
use App\SyClass\Helpers\Response;
use App\SyClass\System\Security;
use App\SyClass\System\Interfaces\InterfaceResponse;

class readSalesController extends Controller
                        implements InterfaceResponse{

    private $_objResponse;
    private $_objFvs;
    
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
     * retorna los datos de la vey
     * 
     * @return type
     * @throws \Exception
     */
    public function getDataSales(){
        try{
            return (new \App\SyClass\App\Sales($this))
                    ->getSalesAllDiary((object)\Request::all());
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * retorna los datos de un sale por ID
     * 
     * @param type $id
     * @return type
     * @throws \Exception
     */
    public function getDataSaleById($id){
        try{
            // validamos los datos
            $rspValData = $this->validateGetDataSaleById($id);
            if($rspValData != null){
                return $rspValData;
            }
            
            return (new \App\SyClass\App\Sales($this))
                    ->getSaleById($id);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * 
     * @return type
     * @throws \Exception
     */
    private function validateGetDataSaleById($id){
        try{
            // validamos los campos
            $reglas = array('id'=>'required');
            $alias = array('id'=>'Id de la venta');
            
            $validar = \Validator::make(array("id"=>$id), $reglas)->setAttributeNames($alias);
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
