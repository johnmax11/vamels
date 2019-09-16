<?php
namespace App\Http\Controllers\App\Aplication\prEvents;

use Illuminate\Routing\Controller;
use App\SyClass\Helpers\Utilities;
use App\SyClass\Helpers\Response;
use App\SyClass\System\Security;
use App\SyClass\System\Interfaces\InterfaceResponse;

class updateEventsController extends Controller
                            implements InterfaceResponse{

    public function __construct() {}
    
    /**
     * Create a new controller instance.
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
     * crea un evento
     * 
     * @return type
     * @throws \Exception
     */
    public function updatePostFacebookEvento(){
        try{
            return (new \App\SyClass\App\Events($this))
                        ->updateEventFacebook((object)\Request::all());
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
