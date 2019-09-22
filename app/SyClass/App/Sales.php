<?php
namespace App\SyClass\App;
use \App\SyClass\DB\FacCRUD;
use \App\SyModels;
/**
 * @author john jairo cortes garcia <johnmax11@hotmail.com>
 * @created_at 19-08-2017
 */
class Sales{
    
    private $_interfaceResponse;
    
    /**
     * 
     * @param type $objInterfaceResponse
     */
    public function __construct($objInterfaceResponse) {
        // verificamos si hay interface de respuesta
        if($objInterfaceResponse != null){
            $this->_interfaceResponse = $objInterfaceResponse;
        }
    }
    
    /**
     * mostrar las ventas diarias
     * 
     * @param type $origenClass
     * @return type
     * @throws \Exception
     */
    public function getSalesAllDiary(){
        try{
            $arrDataSalesDiary = (new FacCRUD(new SyModels\SalesDiary()))
                    ->read(null,null,array(array('date_sale','DESC')));
            
            // recorremos los datos y totalizamos las ventas
            $arrResponseJson = array();
            $nRowsSales = count($arrDataSalesDiary);
            for($i=0;$i<$nRowsSales;$i++){
                
                // consultamos el total  de cada dia
                $arrResultSum = (new FacCRUD(new SyModels\SalesDiaryDetails()))
                        ->read(
                            array(array('sales_diary_id',$arrDataSalesDiary[$i]->id)),
                            array('sales_diary_id'),
                            null,
                            null,
                            array(
                                array('SUM','price_sale'),
                                array('SUM','discount'),
                                array('COUNT','price_sale')
                            )
                        );
                
                // consultamos el cliente
                $objFacCli = (new SyModels\Clients($arrDataSalesDiary[$i]->clients_id));
                
                // consultamos el vendedor
                $objFacUs = (new SyModels\SysUsers($arrDataSalesDiary[$i]->users_sale_id));
                
                // set columns response
                $arrResponseJson[$i] = new \stdClass();
                $arrResponseJson[$i]->id = $arrDataSalesDiary[$i]->id;
                $arrResponseJson[$i]->cant_prod = number_format($arrResultSum[0]->count_price_sale);
                $arrResponseJson[$i]->total_ventas = number_format($arrResultSum[0]->sum_price_sale);
                $arrResponseJson[$i]->total_descuentos = number_format($arrResultSum[0]->sum_price_sale-$arrResultSum[0]->sum_discount);
                $arrResponseJson[$i]->cliente = $objFacCli->get()->name;
                $arrResponseJson[$i]->vendedor = $objFacUs->get()->email;
                $arrResponseJson[$i]->date_sale = $arrDataSalesDiary[$i]->date_sale;
            }
            
            // set response
            return $this->_interfaceResponse->callBackResponse(array("rows"=>$arrResponseJson));
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * 
     * @param type $idsale
     * @return type
     * @throws \Exception
     */
    public function getSaleById($idsale){
        try{
            $objFacC = new FacCRUD(new SyModels\SalesDiary($idsale));
            
            $str = "";
            $str .= "<div class='row' style='font-size:8pt;'>";
            $str .= "   <div class='col-sm-4'>";
            $str .= "       <b>Fecha:</b> ".$objFacC->get()->date_sale;
            $str .= "   </div>";
            $objFacUs = (new SyModels\SysUsers($objFacC->get()->users_sale_id));
            $str .= "   <div class='col-sm-4' style='font-size:8pt;'>";
            $str .= "       <b>Vendedor:</b> ".$objFacUs->get()->email;
            $str .= "   </div>";
            $objFacCli = (new SyModels\Clients($objFacC->get()->clients_id));
            $str .= "   <div class='col-sm-4' style='font-size:8pt;'>";
            $str .= "       <b>Cliente:</b> ".$objFacCli->get()->name." - ".$objFacCli->get()->phone;
            $str .= "   </div>";
            $str .= "</div>";
            
            // consultamos los productos de la venta
            $objFacDe = new FacCRUD(new SyModels\SalesDiaryDetails());
            $arrDSaDet = $objFacDe->read(array(array('sales_diary_id',$objFacC->get()->id)));
            
            $str .= "<div class='row'>";
            $str .= "   <div class='col-sm-12'>";
            $str .= "    <table style='width:100%;font-size:8pt;'>";
            $str .= "       <tr>";
            $str .= "           <th>&nbsp;</th>";
            $str .= "           <th>Codigo</th>";
            $str .= "           <th>Nombre</th>";
            $str .= "           <th>Vr. Compra</th>";
            $str .= "           <th>Vr. Venta</th>";
            $str .= "           <th>Desc</th>";
            $str .= "           <th>Total</th>";
            $str .= "       </tr>";
            
            $nR = count($arrDSaDet);
            $totalDcto = $totalVenta = 0;
            for($i=0;$i<$nR;$i++){
                // consultamos el producto
                $objFaProd = (new SyModels\Products($arrDSaDet[$i]->products_id));
                // categoria producto
                $objFacCate = (new SyModels\CategoryProducts($objFaProd->get()->category_products_id));
                
                $str .= "       <tr>";
                $str .= "           <th>".($i+1)."</th>";
                $str .= "           <td>".$objFacCate->get()->prefix." ".str_pad($objFaProd->get()->code_product,4,"0",STR_PAD_LEFT)."</td>";
                $str .= "           <td>".ucwords(mb_strtolower($objFaProd->get()->name))."</td>";
                $str .= "           <td>".number_format($arrDSaDet[$i]->price_buy)."</td>";
                $totalVenta += $arrDSaDet[$i]->price_sale;
                $totalDcto += $arrDSaDet[$i]->discount;
                $str .= "           <td>".number_format($arrDSaDet[$i]->price_sale)."</td>";
                $str .= "           <td>".number_format($arrDSaDet[$i]->discount)."</td>";
                
                $vrT = ($arrDSaDet[$i]->price_sale - $arrDSaDet[$i]->discount);
                $str .= "           <td>".number_format($vrT)."</td>";
                $str .= "       </tr>";
            }
            $str .= "       <tr>";
            $str .= "           <td>&nbsp;</td>";
            $str .= "           <td>&nbsp;</td>";
            $str .= "           <td>&nbsp;</td>";
            $str .= "           <td>&nbsp;</td>";
            $str .= "           <td><b>".number_format($totalVenta)."</b></td>";
            $str .= "           <td><b>".number_format($totalDcto)."</b></td>";

            $vrT = ($totalVenta - $totalDcto);
            $str .= "           <td><b>".number_format($vrT)."</b></td>";
            $str .= "       </tr>";
            $str .= "    </table>";
            $str .= " </div>";
            $str .= "</div>";
            
            // set response
            return $this->_interfaceResponse->callBackResponse(array('html'=>$str));
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * ingresamos los productos a la base de datos
     * 
     * @param type $dataRequest
     * @return type
     * @throws \Exception
     */
    public function setVentaProductos($dataRequest){
        try{
            \DB::beginTransaction();
            
            $objFacCrud = new \App\Facades\FacCRUD(new \SalesDiary());
            $idLastSales = $objFacCrud->create((object)array(
                'clients_id'=>$dataRequest->selCliente,
                'date_sale'=>$dataRequest->txtFechaVenta,
                'users_sale_id'=>$dataRequest->selVendedor
            ));
            
            // recorremos los productos a ingresar
            for($i=0;$i < $dataRequest->hdnContRows;$i++){
                if(!isset($dataRequest->{"selCodigo-".$i})){
                    continue;
                }
                
                // sacamos los datos del producto
                $objFacCrud = new \App\Facades\FacCRUD(new \Products($dataRequest->{"selCodigo-".$i}));
               
                // insertamos el detalle del registro
                $objFacCrudSales = new \App\Facades\FacCRUD(new \SalesDiaryDetails());
                $objFacCrudSales->create(
                    (object)array(
                        'sales_diary_id'=>$idLastSales,
                        'products_id'=>$dataRequest->{"selCodigo-".$i},
                        'price_buy'=>$objFacCrud->get()->value_buy,
                        'price_sale'=>$objFacCrud->get()->value_sell,
                        'discount'=>str_replace(',','',$dataRequest->{"txtDescuento-".$i})
                    )
                );
                    
                // descontamos del inventario la cantidad vendida
                $objFacProduct = new FacProducts($this->_orClass);
                $objFacProduct->setSaleInventoryProduct($dataRequest->{"selCodigo-".$i},1,"-","Venta del dia",$dataRequest->txtFechaVenta);
            }
            
            \DB::commit();
            // set response
            return $this->_orClass->callBackResponse();
        } catch (\Exception $ex) {
            \DB::rollBack();
            throw $ex;
        }
    }
    
}