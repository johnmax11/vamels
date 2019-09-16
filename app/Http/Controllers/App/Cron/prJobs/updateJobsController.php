<?php
namespace App\Http\Controllers\App\Cron\prJobs;

use Illuminate\Routing\Controller;
use App\SyClass\Helpers\Utilities;
use App\SyClass\Helpers\Response;
use App\SyClass\System\Security;
use App\SyClass\System\Interfaces\InterfaceResponse;

class updateJobsController extends Controller
                            implements InterfaceResponse{

    public function __construct() {}
    
    
    /**
     * verifica si hay q enviar push
     * 
     * @return type
     * @throws \Exception
     */
    public function verifyAgendaEvent(){
        try{
            return (new \App\SyClass\App\Events($this))
                        ->verifyPushFBase();
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
