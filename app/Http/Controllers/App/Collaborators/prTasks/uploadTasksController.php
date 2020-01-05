<?php
namespace App\Http\Controllers\App\Collaborators\prTasks;

use Illuminate\Routing\Controller;
use App\SyClass\Helpers\Utilities;
use App\SyClass\Helpers\Response;
use App\SyClass\System\Security;
use App\SyClass\System\Interfaces\InterfaceResponse;
class uploadTasksController extends Controller
                        implements InterfaceResponse{

    private $_objResponse;
    
    public function __construct() {}
    
    /**
     * retorna la imagen del witnesses
     * 
     * @return type
     * @throws \Exception
     */
    public function uplImagenTasks(){
        try{
            $idUpd = Utilities::getSessionIdUpdate(\Request::input('_token_update'));
            if($idUpd == false){
                return $this->callBackResponse(
                    array(
                        'msgResponseFirst'=>"Error campo de control TOKEN, actualize la pagina y vuelva a intentarlo",
                    ),true);
            }
            
            $reglas = array(
                "nameField"=>"required",
                "file"=>"required"
            );
            $alias = array(
                "nameField"=>"nombre del campo",
                "file"=>"nombre del archivo"
            );

            
            return (new \App\SyClass\App\Witnesses(null, $this))
                    ->uploadImageTasks((object)\Request::all(), (int)$idUpd);
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
