<?php
namespace App\Facades;
namespace App\Facades\App;
/**
 * @author john jairo cortes garcia <johnmax11@hotmail.com>
 * @created_at 26-08-2017
 */
class FacProducts{
    
    private $_orClass;
    public function __construct($orClass){
        $this->_orClass = $orClass;
    }
    
    /**
     * busca los productos por el codigo
     * 
     * @param type $origenClass
     * @param type $strSearch
     * @return type
     * @throws \Exception
     */
    public function getProductsByCode($strSearch){
        try{
            //////// buscamos por codigo producto
            $objFacCrud = new \App\Facades\FacCRUD(new \Products());
            $arrDProd = $objFacCrud->read(
                array(array('code_product','LIKE',"%".$strSearch."%")),
                null,
                array(array('code_product','ASC')),
                null,
                array('id','code_product','name','category_products_id','value_sell')
            );
            
            $arrRsp = array();
            
            /////// verificamos si hay resultados
            if(count($arrDProd) == 0){
                // buscamos por el nombre producto
                $objFacCrud = new \App\Facades\FacCRUD(new \Products());
                $arrDProd = $objFacCrud->read(
                    array(array('name','LIKE',"%".$strSearch."%")),
                    null,
                    array(array('name','ASC')),
                    null,
                    array('id','code_product','name','category_products_id','value_sell')
                );
            }
            
            // recorremos y sacamos prefijo
            $nRows = count($arrDProd);
            for($i=0;$i < $nRows;$i++){
                $objFacCrud = new \App\Facades\FacCRUD(new \CategoryProducts($arrDProd[$i]->category_products_id));
                
                $arrRsp[$i] = new \stdClass();
                $arrRsp[$i]->id = $arrDProd[$i]->id;
                $arrRsp[$i]->code_product = str_pad($arrDProd[$i]->code_product,4,"0",STR_PAD_LEFT);
                $arrRsp[$i]->name = $arrDProd[$i]->name;
                $arrRsp[$i]->value_sell = number_format($arrDProd[$i]->value_sell);
                $arrRsp[$i]->prefix = $objFacCrud->get()->prefix;
                // consultamos la cantidad de inventario
                $objFacIn = new \App\Facades\FacCRUD(new \InventoryProducts());
                $arrDInvP = $objFacIn->read(array(array('products_id',$arrDProd[$i]->id)));
                $arrRsp[$i]->inventory = (isset($arrDInvP[0])?$arrDInvP[0]->quantity:'S/I');
            }
            
            // set response
            return $this->_orClass->callBackResponse($arrRsp);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * consulta todos los productos del sistema
     * 
     * @return type
     * @throws \Exception
     */
    public function getProductsAll(){
        try{
            $objFacC = new \App\Facades\FacCRUD(new \Products());
            $arrData = $objFacC->read(
                array(array('status','A')),
                null,
                array(array('id','ASC')),
                null,
                array('id','category_products_id','code_product','name','value_buy','value_sell','created_at')
            );
            
            $arrRsp = array();
            $nR = count($arrData);
            for($i=0;$i<$nR;$i++){
                $arrRsp[$i] = new \stdClass();
                
                // sacamos el prefix
                $objFPre = new \App\Facades\FacCRUD(new \CategoryProducts($arrData[$i]->category_products_id));
                
                $arrRsp[$i]->id = $arrData[$i]->id;
                $arrRsp[$i]->prefix = $objFPre->get()->prefix;
                $arrRsp[$i]->code_product = $arrData[$i]->code_product;
                $arrRsp[$i]->name = $arrData[$i]->name;
                $arrRsp[$i]->value_buy = number_format($arrData[$i]->value_buy);
                $arrRsp[$i]->value_sell = number_format($arrData[$i]->value_sell);
                $arrRsp[$i]->created_at = $arrData[$i]->created_at;
                
            }
            
            // set response
            return $this->_orClass->callBackResponse($arrRsp);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * get producto por id
     * 
     * @param type $idProduct
     * @return type
     * @throws \Exception
     */
    public function getProductsById($idProduct){
        try{
            $objFacCr = new \App\Facades\FacCRUD(new \Products());
            $arrDPro = $objFacCr->read(
                array(array('id',$idProduct)),
                null,
                null,
                null,
                array('id','category_products_id','code_product','name','value_buy','value_sell')
            );
            
            return $this->_orClass->callBackResponse($arrDPro);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * crea un producto
     * 
     * @param type $dataRequest
     * @throws \Exception
     */
    public function setSaveDataProduct($dataRequest){
        try{
            $objFCr = new \App\Facades\FacCRUD(new \Products());
            $objFCr->create((object)array(
                'category_products_id'=>$dataRequest->selPrefijo,
                'code_product'=>$dataRequest->txtCodigo,
                'name'=>$dataRequest->txtNombre,
                'value_buy'=>str_replace(',','',$dataRequest->txtVrCompra),
                'value_sell'=>str_replace(',','',$dataRequest->txtVrVenta),
                'status'=>'A'
            ));
            
            // set response
            return $this->_orClass->callBackResponse();
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * actualzia un producto por el id
     * 
     * @param type $dataRequest
     * @param type $idUpd
     * @return type
     * @throws \Exception
     */
    public function setUpdateDataProduct($dataRequest,$idUpd){
        try{
            $objFCr = new \App\Facades\FacCRUD(new \Products());
            $objFCr->update(
                (object)array(
                    'category_products_id'=>$dataRequest->selPrefijo,
                    'code_product'=>$dataRequest->txtCodigo,
                    'name'=>$dataRequest->txtNombre,
                    'value_buy'=>str_replace(',','',$dataRequest->txtVrCompra),
                    'value_sell'=>str_replace(',','',$dataRequest->txtVrVenta)
                ),
                (object)array('id'=>$idUpd)
            );
            
            // set response
            return $this->_orClass->callBackResponse();
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * consulta el inventarios del os productos
     * 
     * @throws \Exception
     */
    public function getDataInventoryAll(){
        try{
            $objFacInv = new \App\Facades\FacCRUD(new \InventoryProducts());
            $arrData = $objFacInv->read(array(array('id','>',0)));
            // recorremos y completamos datos
            $nR = count($arrData);
            $arrRsp = array();
            for($i=0;$i<$nR;$i++){
                $arrRsp[$i] = new \stdClass();
                
                $arrRsp[$i]->id = $arrData[$i]->id;
                
                // sacamos los datos del producto
                $objFacPr = new \App\Facades\FacCRUD(new \Products($arrData[$i]->products_id));
                
                // sacamos los datos de la categoria
                $objFacCat = new \App\Facades\FacCRUD(new \CategoryProducts($objFacPr->get()->category_products_id));
                
                $arrRsp[$i]->code = $objFacCat->get()->prefix." ".$objFacPr->get()->code_product;
                $arrRsp[$i]->name = $objFacPr->get()->name;
                $arrRsp[$i]->quantity = $arrData[$i]->quantity;
                
                $arrRsp[$i]->vrcompra = $objFacPr->get()->value_buy;
                $arrRsp[$i]->vrventa = $objFacPr->get()->value_sell;
                
                $arrRsp[$i]->updated_at = $arrData[$i]->updated_at;
                
            }
            // set response
            return $this->_orClass->callBackResponse($arrRsp);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * consulta los producos q no estan en inventario
     * 
     * @return type
     * @throws \Exception
     */
    public function getDataProductsByInventoryNotExist(){
        try{
            $objFacPr = new \App\Facades\FacCRUD(new \Products());
            $arrDataProd = $objFacPr->read(
                array(array('id NOT IN (SELECT products_id FROM inventory_products)')),
                null,
                null,
                null,
                array('id','name','code_product','category_products_id')
            );
            
            $nRows = count($arrDataProd);
            $arrRsp = array();
            for($i=0;$i<$nRows;$i++){
                $arrRsp[$i] = new \stdClass();
                
                $arrRsp[$i]->id = $arrDataProd[$i]->id;
                
                // consultamos el prefijo
                $objFacCat = new \App\Facades\FacCRUD(new \CategoryProducts($arrDataProd[$i]->category_products_id));
                $arrRsp[$i]->name = $objFacCat->get()->prefix." ".$arrDataProd[$i]->code_product." - ".$arrDataProd[$i]->name;
            }
            
            // set response
            return $this->_orClass->callBackResponse($arrRsp);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * inserta inventario producto - cantidad
     * 
     * @param type $dataRequest
     * @return type
     * @throws \Exception
     */
    public function setInventoryProducts($dataRequest){
        try{
            $objFacPrInv = new \App\Facades\FacCRUD(new \InventoryProducts());
            $idLast = $objFacPrInv->create((object)array(
                'products_id'=>$dataRequest->selProducto,
                'quantity'=>$dataRequest->txtCantidad
            ));
            
            // ingresamos en el track
            $this->setInventoryProductsTrack(
                $idLast,
                $dataRequest->txtCantidad,
                "Inventario inicial"
            );
            
            // set response
            return $this->_orClass->callBackResponse();
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * suma o descuenta del inventario
     * 
     * @param type $idProduct
     * @param type $cantidad
     * @param type $oper
     * @throws \Exception
     */
    public function setSaleInventoryProduct($idProduct,$cantidad,$oper,$descr,$fechaventa=null){
        try{
            // sacamos los datos del producto
            $objFacCrProd = new \App\Facades\FacCRUD(new \InventoryProducts());
            $arrDInvPr = $objFacCrProd->read(array(array('products_id',$idProduct)));
            
            // verificamos si existe la relacion
            if(isset($arrDInvPr[0])){
                $objFacCr = new \App\Facades\FacCRUD(new \InventoryProducts());
                $objFacCr->update(
                    (object)array(
                        'quantity'=>(
                            $oper == '+'?
                            $arrDInvPr[0]->quantity + $cantidad:
                            $arrDInvPr[0]->quantity - $cantidad
                        )
                    ),
                    (object)array(
                        'id'=>$arrDInvPr[0]->id
                    )
                );
                
                // ingresamos en el track
                $this->setInventoryProductsTrack(
                    $arrDInvPr[0]->id,
                    ($oper == '+'?$cantidad:($cantidad-($cantidad*2))),
                    $descr,
                    $fechaventa
                );
            }
            
            return true;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * ingresa inventario producto track para seguimiento de inventario
     * 
     * @param type $invProdId
     * @param type $cantidad
     * @param type $descr
     * @return boolean
     * @throws \Exception
     */
    public function setInventoryProductsTrack($invProdId,$cantidad,$descr,$obs='',$fechaventa=null){
        try{
            $objFacIT = new \App\Facades\FacCRUD(new \InventoryProductsTrack());
            $objFacIT->create((object)array(
                'inventory_products_id'=>$invProdId,
                'quantity'=>$cantidad,
                'description'=>$descr,
                'observation'=>$obs,
                'date_sale'=>$fechaventa
            ));
            
            return true;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * consulta inven producto por el id
     * 
     * @param type $invProdId
     * @return type
     * @throws \Exception
     */
    public function getInventoryProductsById($invProdId){
        try{
            $onjFacCInv = new \App\Facades\FacCRUD(new \InventoryProducts($invProdId));
            
            // consultamos el producto
            $objFacPro = new \App\Facades\FacCRUD(new \Products($onjFacCInv->get()->products_id));
            
            // consultamos el prefix
            $objFacPref = new \App\Facades\FacCRUD(new \CategoryProducts($objFacPro->get()->category_products_id));
            
            $arrRsp = array();
            $arrRsp[0] = new \stdClass();
            
            $arrRsp[0]->name = $objFacPref->get()->prefix." ".$objFacPro->get()->code_product." - ".$objFacPro->get()->name;
            $arrRsp[0]->cant_actual = $onjFacCInv->get()->quantity;
            
            // set response
            return $this->_orClass->callBackResponse($arrRsp);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * nuevo movimiento en el inventario
     * 
     * @param type $dataRequest
     * @param type $idUpd
     * @throws \Exception
     */
    public function setUpdateInventoryProduct($dataRequest,$idUpd){
        try{
            // actualizamos la nueva cantidad
            $objFacCr = new \App\Facades\FacCRUD(new \InventoryProducts($idUpd));
            $objFacCr->update(
                (object)array(
                    'quantity'=>
                        $dataRequest->txtCantidadNueva > 0?
                        $objFacCr->get()->quantity + $dataRequest->txtCantidadNueva:
                        $objFacCr->get()->quantity - abs($dataRequest->txtCantidadNueva)
                ),
                (object)array('id'=>$idUpd)
            );
            
            // ingresamos el movimiento en el track
            $this->setInventoryProductsTrack(
                $idUpd,
                $dataRequest->txtCantidadNueva,
                $dataRequest->selMotivo,
                $dataRequest->tarObservaciones
            );
            
            // set response
            return $this->_orClass->callBackResponse();
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * mostrar el track de la consulta
     * 
     * @param type $idInvPro
     * @return type
     * @throws \Exception
     */
    public function getTrackInventoryProd($idInvPro){
        try{
            $objFacC = new \App\Facades\FacCRUD(new \InventoryProductsTrack());
            $arrDataTra = $objFacC->read(array(array('inventory_products_id',$idInvPro)));
            
            $str = "";
            $nRows = count($arrDataTra);
            
            $str = "";
            $str .= "<div class='row' style='font-size:8pt;'>";
            $str .= "   <div class='col-sm-12'>";
            $str .= "    <table class='table-responsive' style='width:100%;'>";
            $str .= "       <tr>";
            $str .= "           <th>&nbsp;</th>";
            $str .= "           <th>&nbsp;Fecha&nbsp;</th>";
            $str .= "           <th>&nbsp;Cant&nbsp;</th>";
            $str .= "           <th>&nbsp;Motivo&nbsp;</th>";
            $str .= "           <th>&nbsp;Obs&nbsp;</th>";
            $str .= "       </tr>";
            for($i=0;$i<$nRows;$i++){
                $str .= "   <tr style='background:".($arrDataTra[$i]->quantity>0?'#D8F5BF':'#F5DEDA').";'>";
                $str .= "       <th>&nbsp;".($i+1)."&nbsp;</th>";
                $str .= "       <th>&nbsp;".($arrDataTra[$i]->date_sale==null?'*'.substr($arrDataTra[$i]->created_at,0,10):$arrDataTra[$i]->date_sale)."&nbsp;</th>";
                $str .= "       <th>&nbsp;".$arrDataTra[$i]->quantity."&nbsp;</th>";
                $str .= "       <th>&nbsp;".$arrDataTra[$i]->description."&nbsp;</th>";
                $str .= "       <th>&nbsp;".$arrDataTra[$i]->observation."&nbsp;</th>";
                $str .= "   </tr>";
            }
            $str .= "    </table>";
            $str .= "   </div>";
            $str .= "</div>";
            
            // set response
            return $this->_orClass->callBackResponse(array('html'=>$str));
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
}