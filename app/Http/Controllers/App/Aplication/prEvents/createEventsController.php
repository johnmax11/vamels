<?php
namespace App\Http\Controllers\App\Aplication\prEvents;

use Illuminate\Routing\Controller;
use App\SyClass\Helpers\Utilities;
use App\SyClass\Helpers\Response;
use App\SyClass\System\Security;
use App\SyClass\System\Interfaces\InterfaceResponse;

class createEventsController extends Controller
                            implements InterfaceResponse{

    public function __construct() {}
    
    /**
     * Create a new controller instance.
     *
     * @return void
    */
    public function main(){
        try{
            /*$fb = new \Facebook\Facebook([
                'app_id' => '411529212677451',
                'app_secret' => '4952bc29f923da3d600252ba13a87b9a',
                'default_graph_version' => 'v2.10',
                'persistent_data_handler'=>new \App\SyClass\System\PersistenceLaravelFacebook(),
                //'default_access_token' => '{access-token}', // optional
            ]);
            $helper = $fb->getRedirectLoginHelper();
            if(!isset($_GET["redirect"])){
                $permissions = ['email', 'user_likes','user_posts'];
                $loginUrl = $helper->getLoginUrl('http://localhost/mpd/public/app/aplication/events/create?redirect=1', $permissions);

                echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';exit;
            }else{
                $_SESSION['FBRLH_state'] = $_GET['state'];
                $helper->getPersistentDataHandler()->set('state', $_GET['state']);
                
                try{
                    $accessToken = $helper->getAccessToken();
                    $r = $fb->get('/me/permissions',$accessToken);
                    echo "<pre>".print_r($r,true)."</pre>";exit;
                    $linkData = [
                        'link' => 'http://www.desarrollolibre.net/blog/tema/50/html/uso-basico-del-canvas',
                        'message' => "Hola Mundoss",
                    ];
                    var_dump($fb->post('/feed', $linkData, $accessToken));
                }catch(Facebook\Exceptions\FacebookResponseException $e) {
                    // When Graph returns an error
                    echo 'Graph returned an error: ' . $e->getMessage();
                } catch (Facebook\Exceptions\FacebookSDKException $e) {
                    // When validation fails or other local issues
                    echo 'Facebook SDK returned an error: ' . $e->getMessage();
                }
                exit;
            }*/

            
            return \View::make(Utilities::getCleanView(__NAMESPACE__))
                    ->with('datosRecurso',(new Security())->readDataRecursosView(__NAMESPACE__));
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * crea un evento
     * 
     * @return type
     * @throws \Exception
     */
    public function saveDataEvent(){
        try{
            // validamos los campos
            $rspValData = $this->validateSaveDataEvent();
            if($rspValData != null){
                return $rspValData;
            }
            
            return (new \App\SyClass\App\Events($this))
                        ->createEventPrincipal((object)\Request::all());
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * valida los datos de grabar atleta
     * 
     * @return type
     * @throws \Exception
     */
    private function validateSaveDataEvent(){
        try{
            // validamos los campos
            $reglas = array(
                'selDeporte'=>'required|numeric',
                'selTipoEvento'=>'required',
                
                'txtDateIni'=>'required|date',
                'txtTimeIni'=>'required',
                
                'txtTituloBig'=>'required',
                'txtTituloDetalle'=>'required',
                
                'txtEscLatitud'=>'required',
                'txtEscLongitud'=>'required',
                
                'txtEscenarioNombre'=>'required',
                'txtEscenarioDireccion'=>'required',
                'txtEscenarioBarrio'=>'required',
                
                'txtLink'=>'required|url',
                
                'txtCiudad'=>'required',
                'txtPrecio'=>'required',
                
                /* datoa autoria */
                'txtTitArticulo'=>'required|max:255',
                'txtWebsitePagina'=>'required|url',
                'txtFechaCreacion'=>'required|date',
                'txtFechaAccedido'=>'required|date',
                'txtWebsiteArticulo'=>'required|url',
            );
            
            // validamos los campos
            $alias = array(
                'selDeporte'=>'Deporte',
                'selTipoEvento'=>'Tipo de evento',
                
                'txtDateIni'=>'Fecha inicial',
                'txtTimeIni'=>'Hora inicial',
                
                'txtTituloBig'=>'Titulo principal',
                'txtTituloDetalle'=>'Titulo detalle',
                
                'txtEscLatitud'=>'Latitud',
                'txtEscLongitud'=>'Longitud',
                
                'txtEscenarioNombre'=>'Nombre escenario',
                'txtEscenarioDireccion'=>'Direccion de escenario',
                'txtEscenarioBarrio'=>'Barrio de escenario',
                
                'txtLink'=>'Link de contacto',
                
                'txtCiudad'=>'Ciudad de contacto',
                'txtPrecio'=>'Precio de contacto',
                
                /* datoa autoria */
                'txtTitArticulo'=>'Titulo articulo',
                'txtWebsitePagina'=>'Dominio de articulo',
                'txtFechaCreacion'=>'Fecha articulo',
                'txtFechaAccedido'=>'Fecha accedido',
                'txtWebsiteArticulo'=>'Website de articulo',
            );
            
            $validar = \Validator::make(\Request::all(), $reglas)->setAttributeNames($alias);
            if($validar->fails()){
                return $this->callBackResponse(
                        array(
                            'msgResponseFirst'=>"Error validando campos",
                            'msgResponseDetails'=>(new Response())->orderDetails($validar->getMessageBag()->toArray(),'error')
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
