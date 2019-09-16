<?php

namespace App\Http\Middleware;

use Closure;
use App\SyClass\Helpers\Response;

class VerifyPermissionRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $bolRsp = (new \App\SyClass\System\Security())->verifyPermissionByRole();
        if($bolRsp == 1){
            return $next($request);
        }else{
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                switch($bolRsp){
                    case -1:
                        return (new Response())
                            ->responseRequest(
                                    array(
                                        "msgResponseFirst"=>"Usted no tiene permisos para acceder a este recurso, :(",
                                        "msgResponseSecond"=>array(
                                            "Module"=>array(array("type"=>"error","message"=>\Session::get('action'))),
                                            "Action"=>array(array("type"=>"error","message"=>\Session::get('module'))))
                                    ),
                                    true,
                                    "error"
                            );
                    case -2:
                        return (new Response())
                            ->responseRequest(
                                    array(
                                        "msgResponseFirst"=>"Error: La url que solicito no existe, :("
                                    ),
                                    true,
                                    "error"
                            );
                }
            }else{
                switch($bolRsp){
                    case -1:
                        return redirect('permitsdenied');
                    case -2:
                        return redirect('routerinvalid');
                }
            }
        }
    }
}
