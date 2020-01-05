<?php
namespace App\Http\Controllers\App\Collaborators\prTasks;

use Illuminate\Routing\Controller;
use App\SyClass\Helpers\Utilities;
use App\SyClass\Helpers\Response;
use App\SyClass\System\Security;
use App\SyClass\System\Interfaces\InterfaceResponse;
class updateTasksController extends Controller
                        implements InterfaceResponse{

    private $_objResponse;
    
    public function __construct() {}
    
    /**
     * methodo main de retorno del view
     *
     * @return void
    */
    public function main($id){
        try{
            if((new \App\SyClass\App\Witnesses(null,null))->verifyIdTask($id) == false){
                return redirect('permitsdenied');
            }
            
            // buscamos las imagenes creadas
            $arrImgTasks = (new \App\SyClass\App\Witnesses(null, null))
                    ->getImagesByIdTasks($id);
            
            return \View::make(Utilities::getCleanView(__NAMESPACE__))
                        ->with('arrImgTasks',$arrImgTasks)
                        ->with('datosRecurso',(new Security())->readDataRecursosView(__NAMESPACE__))
                        ->with('_token_update',Utilities::setSessionTokenUpdate($id));
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
